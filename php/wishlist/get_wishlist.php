<?php
session_start();
require_once __DIR__ . "/../config.php";
header("Content-Type: application/json");
if (!isset($_SESSION['user_id'])) { http_response_code(403); echo json_encode([]); exit; }

$user_id = (int)$_SESSION['user_id'];
$sql = "SELECT h.* FROM houses h
        JOIN wishlist w ON h.id = w.house_id
        WHERE w.user_id=?
        ORDER BY w.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlist = [];
while ($row = $result->fetch_assoc()) { $wishlist[] = $row; }
echo json_encode($wishlist);
?>
