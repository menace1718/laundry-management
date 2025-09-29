<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';

$in = json_body();
$action = $in['action'] ?? '';

if ($action === 'login') {
  $username = trim($in['username'] ?? '');
  $password = trim($in['password'] ?? '');
  $role = trim($in['role'] ?? '');
  
  if ($username === '' || $password === '' || $role === '') {
    fail('VALIDATION', 'Username, password, and role are required');
  }
  
  $q = "SELECT id, username, password, role, full_name, email FROM users WHERE username='".esc($conn,$username)."' AND role='".esc($conn,$role)."' AND is_active=1 LIMIT 1";
  $res = $conn->query($q);
  
  if (!$res || $res->num_rows === 0) {
    fail('INVALID_CREDENTIALS', 'Invalid username or password');
  }
  
  $user = $res->fetch_assoc();
  
  if (!password_verify($password, $user['password'])) {
    fail('INVALID_CREDENTIALS', 'Invalid username or password');
  }
  
  // Create session
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['role'] = $user['role'];
  $_SESSION['full_name'] = $user['full_name'];
  $_SESSION['email'] = $user['email'];
  
  ok([
    'id' => $user['id'],
    'username' => $user['username'],
    'role' => $user['role'],
    'full_name' => $user['full_name']
  ]);
}
else if ($action === 'logout') {
  session_destroy();
  ok();
}
else if ($action === 'register') {
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
  
  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  
  // Insert new customer user
  $q = "INSERT INTO users (username, password, role, full_name, email, phone) VALUES ('".esc($conn,$username)."', '".esc($conn,$hashed_password)."', 'customer', '".esc($conn,$full_name)."', '".esc($conn,$email)."', '".esc($conn,$phone)."')";
  
  if (!$conn->query($q)) {
    fail('DB_ERROR', 'Failed to create account: ' . $conn->error);
  }
  
  $user_id = $conn->insert_id;
  
  // Also create entry in customers table
  $conn->query("INSERT INTO customers (name, phone, email, user_id) VALUES ('".esc($conn,$full_name)."', '".esc($conn,$phone)."', '".esc($conn,$email)."', $user_id)");
  
  ok([
    'id' => $user_id,
    'username' => $username,
    'full_name' => $full_name,
    'message' => 'Account created successfully'
  ]);
}
else if ($action === 'check_session') {
  if (isset($_SESSION['user_id'])) {
    ok([
      'id' => $_SESSION['user_id'],
      'username' => $_SESSION['username'],
      'role' => $_SESSION['role'],
      'full_name' => $_SESSION['full_name']
    ]);
  } else {
    fail('NOT_AUTHENTICATED', 'No active session');
  }
}
else {
  fail('INVALID_ACTION');
}
?>
