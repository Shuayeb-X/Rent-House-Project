<?php
session_start();
require_once __DIR__ . "/config.php";
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit("Unauthorized"); }

$id = (int)$_SESSION['user_id'];
$first = $_POST['first_name'] ?? '';
$last  = $_POST['last_name'] ?? '';
$mobile= $_POST['mobile'] ?? '';
$nid   = $_POST['nid'] ?? '';
$email = $_POST['email'] ?? '';

if (!$first||!$last||!$mobile||!$nid||!$email) { exit("All fields are required."); }

$sql = "UPDATE users SET first_name=?, last_name=?, mobile=?, nid=?, email=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $first,$last,$mobile,$nid,$email,$id);
if ($stmt->execute()) { $_SESSION['first_name']=$first; $_SESSION['last_name']=$last; echo "Profile updated."; }
else { echo "Failed to update profile."; }
?>
