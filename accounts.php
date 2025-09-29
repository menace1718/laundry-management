<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';

// Check if user is staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
  fail('UNAUTHORIZED', 'Staff access required');
}

$in = json_body();
$action = $in['action'] ?? '';

if ($action === 'list_all') {
  $res = $conn->query("SELECT u.id, u.username, u.role, u.full_name, u.email, u.phone, u.created_at, u.is_active FROM users u ORDER BY u.created_at DESC");
  $rows = [];
  while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
  }
  ok($rows);
}
else if ($action === 'get_user') {
  $id = intval($in['id'] ?? 0);
  if (!$id) fail('VALIDATION', 'User ID required');
  
  $res = $conn->query("SELECT u.id, u.username, u.role, u.full_name, u.email, u.phone, u.created_at, u.is_active FROM users u WHERE u.id=$id LIMIT 1");
  if (!$res || $res->num_rows === 0) {
    fail('NOT_FOUND', 'User not found');
  }
  
  $user = $res->fetch_assoc();
  ok($user);
}
else if ($action === 'update_user') {
  $id = intval($in['id'] ?? 0);
  $full_name = trim($in['full_name'] ?? '');
  $email = trim($in['email'] ?? '');
  $phone = trim($in['phone'] ?? '');
  $role = trim($in['role'] ?? '');
  
  if (!$id || $full_name === '' || $email === '' || $role === '') {
    fail('VALIDATION', 'ID, full name, email, and role are required');
  }
  
  if (!in_array($role, ['staff', 'customer'])) {
    fail('VALIDATION', 'Role must be staff or customer');
  }
  
  // Check if email is taken by another user
  $check = $conn->query("SELECT id FROM users WHERE email='".esc($conn,$email)."' AND id != $id LIMIT 1");
  if ($check && $check->num_rows > 0) {
    fail('EMAIL_EXISTS', 'Email already used by another user');
  }
  
  $q = "UPDATE users SET full_name='".esc($conn,$full_name)."', email='".esc($conn,$email)."', phone='".esc($conn,$phone)."', role='".esc($conn,$role)."' WHERE id=$id";
  
  if (!$conn->query($q)) {
    fail('DB_ERROR', 'Failed to update user: ' . $conn->error);
  }
  
  // Update customers table if exists
  $conn->query("UPDATE customers SET name='".esc($conn,$full_name)."', email='".esc($conn,$email)."', phone='".esc($conn,$phone)."' WHERE user_id=$id");
  
  ok(['message' => 'User updated successfully']);
}
else if ($action === 'toggle_status') {
  $id = intval($in['id'] ?? 0);
  if (!$id) fail('VALIDATION', 'User ID required');
  
  // Don't allow deactivating self
  if ($id == $_SESSION['user_id']) {
    fail('VALIDATION', 'Cannot deactivate your own account');
  }
  
  $res = $conn->query("SELECT is_active FROM users WHERE id=$id LIMIT 1");
  if (!$res || $res->num_rows === 0) {
    fail('NOT_FOUND', 'User not found');
  }
  
  $user = $res->fetch_assoc();
  $new_status = $user['is_active'] ? 0 : 1;
  
  if (!$conn->query("UPDATE users SET is_active=$new_status WHERE id=$id")) {
    fail('DB_ERROR', 'Failed to update status: ' . $conn->error);
  }
  
  $status_text = $new_status ? 'activated' : 'deactivated';
  ok(['message' => "User $status_text successfully", 'new_status' => $new_status]);
}
else if ($action === 'reset_password') {
  $id = intval($in['id'] ?? 0);
  $new_password = trim($in['new_password'] ?? '');
  
  if (!$id || $new_password === '') {
    fail('VALIDATION', 'User ID and new password required');
  }
  
  if (strlen($new_password) < 6) {
    fail('VALIDATION', 'Password must be at least 6 characters');
  }
  
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
  
  if (!$conn->query("UPDATE users SET password='".esc($conn,$hashed_password)."' WHERE id=$id")) {
    fail('DB_ERROR', 'Failed to reset password: ' . $conn->error);
  }
  
  ok(['message' => 'Password reset successfully']);
}
else if ($action === 'create_staff') {
  $username = trim($in['username'] ?? '');
  $password = trim($in['password'] ?? '');
  $full_name = trim($in['full_name'] ?? '');
  $email = trim($in['email'] ?? '');
  $phone = trim($in['phone'] ?? '');
  
  if ($username === '' || $password === '' || $full_name === '' || $email === '') {
    fail('VALIDATION', 'Username, password, full name, and email are required');
  }
  
  if (strlen($password) < 6) {
    fail('VALIDATION', 'Password must be at least 6 characters');
  }
  
  // Check if username already exists
  $check = $conn->query("SELECT id FROM users WHERE username='".esc($conn,$username)."' LIMIT 1");
  if ($check && $check->num_rows > 0) {
    fail('USERNAME_EXISTS', 'Username already taken');
  }
  
  // Check if email already exists
  $check = $conn->query("SELECT id FROM users WHERE email='".esc($conn,$email)."' LIMIT 1");
  if ($check && $check->num_rows > 0) {
    fail('EMAIL_EXISTS', 'Email already registered');
  }
  
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  
  $q = "INSERT INTO users (username, password, role, full_name, email, phone) VALUES ('".esc($conn,$username)."', '".esc($conn,$hashed_password)."', 'staff', '".esc($conn,$full_name)."', '".esc($conn,$email)."', '".esc($conn,$phone)."')";
  
  if (!$conn->query($q)) {
    fail('DB_ERROR', 'Failed to create staff account: ' . $conn->error);
  }
  
  ok([
    'id' => $conn->insert_id,
    'message' => 'Staff account created successfully'
  ]);
}
else if ($action === 'delete_user') {
  $id = intval($in['id'] ?? 0);
  if (!$id) fail('VALIDATION', 'User ID required');
  
  // Don't allow deleting self
  if ($id == $_SESSION['user_id']) {
    fail('VALIDATION', 'Cannot delete your own account');
  }
  
  // Delete user (cascading will handle related records)
  if (!$conn->query("DELETE FROM users WHERE id=$id")) {
    fail('DB_ERROR', 'Failed to delete user: ' . $conn->error);
  }
  
  ok(['message' => 'User deleted successfully']);
}
else {
  fail('INVALID_ACTION');
}
?>
