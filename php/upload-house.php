<?php
session_start();
require_once __DIR__ . "/config.php";
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] ?? '') !== 'owner') { http_response_code(403); exit("Unauthorized"); }

$title = $_POST['title'] ?? '';
$location = $_POST['location'] ?? '';
$category = $_POST['category'] ?? '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
$latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
$longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;
$owner_id = (int)$_SESSION['user_id'];
if (!$title || !$location || !$category || !$price) { exit("All fields are required."); }

$imgPath = null;
if (!empty($_FILES['image']['name'])) {
    $dir = __DIR__ . "/../uploads";
    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
    $safe = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $_FILES['image']['name']);
    $dest = $dir . "/" . $safe;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) { $imgPath = "uploads/" . $safe; }
}

$sql = "INSERT INTO houses (owner_id,title,location,category,price,image_path,latitude,longitude) VALUES (?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssdsss", $owner_id,$title,$location,$category,$price,$imgPath,$latitude,$longitude);
echo $stmt->execute() ? "House uploaded successfully!" : "Failed to upload house.";
?>
