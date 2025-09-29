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

if ($action === 'create_slot') {
  $customer_name = $in['customer_name'] ?? '';
  $customer_id = ensure_customer($conn, $customer_name);
  if (!$customer_id) fail('VALIDATION','customer required');
  $type = trim($in['type'] ?? 'pickup');
  $slot_time = trim($in['slot_time'] ?? '');
  if ($slot_time==='') fail('VALIDATION','slot_time required');
  $status = 'scheduled';
  $q = "INSERT INTO schedules (customer_id, type, slot_time, status) VALUES (".(int)$customer_id.", '".esc($conn,$type)."', '".esc($conn,$slot_time)."', '".esc($conn,$status)."')";
  if (!$conn->query($q)) fail('DB',$conn->error);
  ok([ 'id'=>$conn->insert_id ]);
}
else if ($action === 'list_all') {
  $res = $conn->query("SELECT s.id, c.name as customer_name, s.type, s.slot_time, s.status FROM schedules s JOIN customers c ON c.id=s.customer_id ORDER BY s.slot_time DESC");
  $rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r; ok($rows);
}
else if ($action === 'list_by_customer') {
  $customer_name = trim($in['customer_name'] ?? '');
  $res = $conn->query("SELECT s.id, c.name as customer_name, s.type, s.slot_time, s.status FROM schedules s JOIN customers c ON c.id=s.customer_id WHERE c.name='".esc($conn,$customer_name)."' ORDER BY s.slot_time DESC");
  $rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r; ok($rows);
}
else if ($action === 'update_status') {
  $id = intval($in['id'] ?? 0);
  $status = trim($in['status'] ?? '');
  if (!$id || $status==='') fail('VALIDATION');
  if (!$conn->query("UPDATE schedules SET status='".esc($conn,$status)."' WHERE id=$id")) fail('DB',$conn->error);
  ok();
}
else { fail('INVALID_ACTION'); }
?>
