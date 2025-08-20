<?php
require_once __DIR__ . "/config.php";
$token = $_POST['token'] ?? '';
$newpw = $_POST['new_password'] ?? '';
if (!$token || !$newpw) { exit("Missing fields."); }

$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token=? AND expiry > NOW() LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) { exit("Invalid or expired token."); }
$email = $res->fetch_assoc()['email'];

$hash = password_hash($newpw, PASSWORD_DEFAULT);
$upd = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$upd->bind_param("ss", $hash, $email);

if ($upd->execute()) {
    $del = $conn->prepare("DELETE FROM password_resets WHERE token=?");
    $del->bind_param("s", $token);
    $del->execute();
    echo "Password reset successful.";
} else { echo "Failed to reset password."; }
?>
