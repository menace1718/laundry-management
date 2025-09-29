<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
$in = json_body();
$action = $in['action'] ?? '';

function ensure_customer($conn, $name) {
  $name = trim($name);
  if ($name==='') return null;
  $q = "SELECT id FROM customers WHERE name='".esc($conn,$name)."' LIMIT 1";
  $res = $conn->query($q);
  if ($r = $res->fetch_assoc()) return $r['id'];
  $ins = "INSERT INTO customers (name) VALUES ('".esc($conn,$name)."')";
  if ($conn->query($ins)) return $conn->insert_id;
  return null;
}

if ($action === 'create') {
  $customer_name = $in['customer_name'] ?? '';
  $customer_id = ensure_customer($conn, $customer_name);
  if (!$customer_id) fail('VALIDATION', 'customer required');
  $type = trim($in['type'] ?? '');
  $weight = floatval($in['weight'] ?? 0);
  $price = floatval($in['price'] ?? 0);
  $status = 'received';
  $q = "INSERT INTO orders (customer_id, type, weight, price, status) VALUES (".(int)$customer_id.", '".esc($conn,$type)."', ".($weight?:0).", ".($price?:0).", '".esc($conn,$status)."')";
  if (!$conn->query($q)) fail('DB', $conn->error);
  ok([ 'id' => $conn->insert_id ]);
}
else if ($action === 'list') {
  $status = trim($in['status'] ?? '');
  $where = $status!=='' ? "WHERE o.status='".esc($conn,$status)."'" : '';
  $res = $conn->query("SELECT o.id, c.name as customer_name, o.type, o.weight, o.price, o.status, o.updated_at FROM orders o JOIN customers c ON c.id=o.customer_id $where ORDER BY o.updated_at DESC");
  $rows = [];
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  ok($rows);
}
else if ($action === 'list_by_customer') {
  $customer_name = trim($in['customer_name'] ?? '');
  if ($customer_name === '') fail('VALIDATION', 'customer_name required');
  $res = $conn->query("SELECT o.id, c.name as customer_name, o.type, o.weight, o.price, o.status, o.updated_at FROM orders o JOIN customers c ON c.id=o.customer_id WHERE c.name='".esc($conn,$customer_name)."' ORDER BY o.updated_at DESC");
  $rows = [];
  while ($r = $res->fetch_assoc()) $rows[] = $r;
  ok($rows);
}
else if ($action === 'update_status') {
  $id = intval($in['id'] ?? 0);
  $status = trim($in['status'] ?? '');
  if (!$id || $status==='') fail('VALIDATION');
  if (!$conn->query("UPDATE orders SET status='".esc($conn,$status)."', updated_at=NOW() WHERE id=$id")) fail('DB', $conn->error);
  // Optional: auto notification when ready/delivered
  if (in_array($status, ['ready','delivered'])) {
    $o = $conn->query("SELECT o.id, o.price, c.id as customer_id, c.name as to_name FROM orders o JOIN customers c ON c.id=o.customer_id WHERE o.id=$id")->fetch_assoc();
    if ($o) {
      $msg = $status==='ready' ? ("Your order #".$id." is now Ready for pickup. Total: ₱".number_format($o['price'], 2)) : ("Your order #".$id." has been Delivered. Thank you!");
      $conn->query("INSERT INTO notifications (to_customer_id, message) VALUES (".$o['customer_id'].", '".esc($conn,$msg)."')");
    }
  }
  ok();
}
else {
  fail('INVALID_ACTION');
}
?>
