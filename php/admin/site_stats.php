<?php
require_once __DIR__ . '/../config.php';
$totalUsers  = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$totalHouses = $conn->query("SELECT COUNT(*) AS total FROM houses")->fetch_assoc()['total'] ?? 0;
$totalReqs   = $conn->query("SELECT COUNT(*) AS total FROM rent_requests")->fetch_assoc()['total'] ?? 0;
echo "<p>Total Users: " . (int)$totalUsers . "</p>";
echo "<p>Total Houses Listed: " . (int)$totalHouses . "</p>";
echo "<p>Total Rent Requests: " . (int)$totalReqs . "</p>";
?>
