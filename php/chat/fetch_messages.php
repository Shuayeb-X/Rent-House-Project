<?php
session_start();
require_once __DIR__ . "/../config.php";
header("Content-Type: application/json");
if (!isset($_SESSION['user_id'])) { http_response_code(403); echo json_encode([]); exit; }

$sender_id = (int)$_SESSION['user_id'];
$receiver_id = (int)($_POST['receiver_id'] ?? 0);

$sql = "SELECT id, sender_id, receiver_id, message, created_at
        FROM messages
        WHERE (sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?)
        ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) { $messages[] = $row; }
echo json_encode($messages);
?>
