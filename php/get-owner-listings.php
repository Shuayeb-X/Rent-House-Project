<?php
session_start();
require_once __DIR__ . "/config.php";
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'owner') { http_response_code(403); exit("Unauthorized"); }

$owner_id = (int)$_SESSION['user_id'];
$sql = "SELECT id, title, location, category, price, image_path FROM houses WHERE owner_id=? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$res = $stmt->get_result();

$html = "";
while ($row = $res->fetch_assoc()) {
    $img = $row['image_path'] ? "<img src='../{$row['image_path']}' alt='' style='max-width:100%;border-radius:8px;margin:6px 0;'/>" : "";
    $html .= "<div class='house-item'>
        <h4>".htmlspecialchars($row['title'])."</h4>
        $img
        <p>Location: ".htmlspecialchars($row['location'])."</p>
        <p>Category: ".htmlspecialchars($row['category'])."</p>
        <p>Price: ".htmlspecialchars($row['price'])." BDT</p>
    </div>";
}
echo $html ?: "<p>No listings yet.</p>";
?>
