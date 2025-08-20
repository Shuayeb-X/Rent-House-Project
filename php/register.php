<?php
require_once __DIR__ . "/config.php";

$first = $_POST['first_name'] ?? '';
$last  = $_POST['last_name'] ?? '';
$mobile= $_POST['mobile'] ?? '';
$nid   = $_POST['nid'] ?? '';
$email = $_POST['email'] ?? '';
$pwRaw = $_POST['password'] ?? '';
$type  = $_POST['user_type'] ?? '';

if (!$first||!$last||!$mobile||!$nid||!$email||!$pwRaw||!$type) { exit("All fields are required."); }
$pw = password_hash($pwRaw, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (first_name,last_name,mobile,nid,email,password,user_type) VALUES (?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $first,$last,$mobile,$nid,$email,$pw,$type);

if ($stmt->execute()) { echo "Registration successful! Redirecting to login..."; header("refresh:2;url=../login.html"); }
else { echo "Error: " . $conn->error; }
?>
