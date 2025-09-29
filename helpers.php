<?php
function json_body() {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}
function send_json($payload) {
  echo json_encode($payload);
  exit;
}
function ok($data = []) { send_json([ 'ok' => true, 'data' => $data ]); }
function fail($code, $message = '') { http_response_code(400); send_json([ 'ok' => false, 'error' => $code, 'message' => $message ]); }
function esc($conn, $v) { return $conn->real_escape_string($v); }
?>
