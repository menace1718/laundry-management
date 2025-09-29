<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
$in = json_body();
$action = $in['action'] ?? '';

function get_customer_id($conn, $name){
  $r = $conn->query("SELECT id FROM customers WHERE name='".esc($conn,$name)."' LIMIT 1");
  if ($row = $r->fetch_assoc()) return intval($row['id']);
  return null;
}

if ($action === 'send') {
  $to_name = trim($in['to_name'] ?? '');
  $message = trim($in['message'] ?? '');
  if ($to_name==='' || $message==='') fail('VALIDATION','to_name and message required');
  $cid = get_customer_id($conn, $to_name);
  if (!$cid) fail('NOT_FOUND','customer not found');
  if (!$conn->query("INSERT INTO notifications (to_customer_id, message) VALUES ($cid, '".esc($conn,$message)."')")) fail('DB',$conn->error);
  ok();
}
else if ($action === 'list_by_customer') {
  $to_name = trim($in['to_name'] ?? '');
  $cid = get_customer_id($conn, $to_name);
  if (!$cid) fail('NOT_FOUND','customer not found');
  $res = $conn->query("SELECT n.id, c.name as to_name, n.message, n.created_at FROM notifications n JOIN customers c ON c.id=n.to_customer_id WHERE n.to_customer_id=$cid ORDER BY n.created_at DESC");
  $rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r; ok($rows);
}
else if ($action === 'list_all') {
  $res = $conn->query("SELECT n.id, c.name as to_name, n.message, n.created_at FROM notifications n JOIN customers c ON c.id=n.to_customer_id ORDER BY n.created_at DESC");
  $rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r; ok($rows);
}
else { fail('INVALID_ACTION'); }
?>
