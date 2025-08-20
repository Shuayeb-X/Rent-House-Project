<?php
require_once __DIR__ . '/../config.php';
header("Content-Type: application/json");
$res = $conn->query("SELECT id, first_name, last_name, user_type FROM users ORDER BY id DESC");
$out = [];
while ($row = $res->fetch_assoc()) { $out[] = $row; }
echo json_encode($out);
?>
