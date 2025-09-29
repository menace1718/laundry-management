<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
$in = json_body();
$action = $in['action'] ?? '';

if ($action === 'create') {
  $name = trim($in['name'] ?? '');
  if ($name === '') fail('VALIDATION', 'Name required');
  $phone = trim($in['phone'] ?? '');
  $email = trim($in['email'] ?? '');
  $q = "INSERT INTO customers (name, phone, email) VALUES ('".esc($conn,$name)."','".esc($conn,$phone)."','".esc($conn,$email)."')";
  if (!$conn->query($q)) fail('DB', $conn->error);
  ok([ 'id' => $conn->insert_id ]);
}
else if ($action === 'list') {
  $res = $conn->query("SELECT id, name, phone, email FROM customers ORDER BY name");
  $rows = [];
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  ok($rows);
}
else if ($action === 'delete') {
  $id = intval($in['id'] ?? 0);
  if (!$id) fail('VALIDATION', 'Customer ID required');
  
  // Check if customer has orders
  $check = $conn->query("SELECT COUNT(*) as count FROM orders WHERE customer_id = $id");
  if ($check) {
    $row = $check->fetch_assoc();
    if ($row['count'] > 0) {
      fail('VALIDATION', 'Cannot delete customer with existing orders');
    }
  }
  
  // Delete customer
  $q = "DELETE FROM customers WHERE id = $id";
  if (!$conn->query($q)) fail('DB', $conn->error);
  
  if ($conn->affected_rows === 0) {
    fail('NOT_FOUND', 'Customer not found');
  }
  
  ok(['message' => 'Customer deleted successfully']);
}
else {
  fail('INVALID_ACTION');
}
?>
