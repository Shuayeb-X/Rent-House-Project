<?php
session_start();
require_once __DIR__ . "/config.php";
header("Content-Type: application/json");
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'renter') { http_response_code(403); echo json_encode([]); exit; }

$renter_id = (int)$_SESSION['user_id'];
$sql = "SELECT rr.id, h.title AS house_title, rr.request_date, rr.status
        FROM rent_requests rr
        JOIN houses h ON rr.house_id = h.id
        WHERE rr.renter_id=?
        ORDER BY rr.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $renter_id);
$stmt->execute();
$res = $stmt->get_result();

$out = [];
while ($row = $res->fetch_assoc()) { $out[] = $row; }
echo json_encode($out);
?>
