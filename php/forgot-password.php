<?php
require_once __DIR__ . "/config.php";
$email = $_POST['email'] ?? '';
if (!$email) { exit("Email required."); }

$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

$del = $conn->prepare("DELETE FROM password_resets WHERE email=?");
$del->bind_param("s", $email);
$del->execute();

$ins = $conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
$ins->bind_param("sss", $email, $token, $expiry);
if ($ins->execute()) {
    $link = "http://localhost/renthouse_dbspace_fixed/reset-password.html?token=$token";
    echo "Reset link: $link";
} else { echo "Failed to create reset token."; }
?>
