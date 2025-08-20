<?php
session_start();
require_once __DIR__ . "/config.php";
header("Content-Type: text/plain");
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'renter') { http_response_code(403); exit("Unauthorized"); }

$input = json_decode(file_get_contents('php://input'), true);
$house_id = isset($input['house_id']) ? (int)$input['house_id'] : 0;
$renter_id = (int)$_SESSION['user_id'];
if (!$house_id) { exit("Invalid house."); }

$sql = "INSERT INTO rent_requests (renter_id, house_id, request_date, status) VALUES (?, ?, NOW(), 'pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $renter_id, $house_id);
echo $stmt->execute() ? "Request sent!" : "Failed to send request.";
?>
