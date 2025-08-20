<?php
session_start();
require_once __DIR__ . "/../config.php";
header("Content-Type: application/json");
if (!isset($_SESSION['user_id'])) { http_response_code(403); echo json_encode(["error"=>"Unauthorized"]); exit; }

$sender_id = (int)$_SESSION['user_id'];
$receiver_id = (int)($_POST['receiver_id'] ?? 0);
$message = trim($_POST['message'] ?? '');
if (!$receiver_id || $message === '') { echo json_encode(["error"=>"Missing fields"]); exit; }

$sql = "INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
echo json_encode(["ok" => $stmt->execute()]);
?>
