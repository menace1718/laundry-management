<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
$in = json_body();
$action = $in['action'] ?? '';

function get_cfg($conn){
  $r = $conn->query("SELECT per_kg, per_shirt, per_pants FROM price_config LIMIT 1");
  if ($row = $r->fetch_assoc()) return $row;
  $conn->query("INSERT INTO price_config (per_kg, per_shirt, per_pants) VALUES (50, 10, 15)");
  return [ 'per_kg'=>50, 'per_shirt'=>10, 'per_pants'=>15 ];
}

if ($action === 'get_config') {
  ok(get_cfg($conn));
}
else if ($action === 'set_config') {
  $per_kg = floatval($in['per_kg'] ?? 0);
  $per_shirt = floatval($in['per_shirt'] ?? 0);
  $per_pants = floatval($in['per_pants'] ?? 0);
  if ($conn->query("UPDATE price_config SET per_kg=$per_kg, per_shirt=$per_shirt, per_pants=$per_pants LIMIT 1") === false){
    // insert if not exists
    $conn->query("INSERT INTO price_config (per_kg, per_shirt, per_pants) VALUES ($per_kg,$per_shirt,$per_pants)");
  }
  ok();
}
else if ($action === 'calculate') {
  $cfg = get_cfg($conn);
  $by = $in['by'] ?? 'weight';
  $total = 0;
  if ($by === 'weight') {
    $weight = floatval($in['weight'] ?? 0);
    $total = $weight * floatval($cfg['per_kg']);
  } else if ($by === 'type') {
    $shirts = intval($in['shirts'] ?? 0);
    $pants = intval($in['pants'] ?? 0);
    $total = $shirts*floatval($cfg['per_shirt']) + $pants*floatval($cfg['per_pants']);
  }
  ok([ 'total' => $total ]);
}
else {
  fail('INVALID_ACTION');
}
?>
