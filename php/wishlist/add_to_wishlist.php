<?php
session_start();
require_once __DIR__ . "/../config.php";
header("Content-Type: text/plain");
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit("Unauthorized"); }

$user_id = (int)$_SESSION['user_id'];
$house_id = (int)($_POST['house_id'] ?? 0);
if (!$house_id) { exit("Invalid house."); }

$sql = "INSERT IGNORE INTO wishlist (user_id, house_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $house_id);
echo $stmt->execute() ? "Added to wishlist." : "Already added or error.";
?>
