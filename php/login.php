<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit("Method Not Allowed"); }
session_start();
require_once __DIR__ . "/config.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$user_type = $_POST['user_type'] ?? '';

$sql = "SELECT id, first_name, last_name, password, user_type FROM users WHERE email=? AND user_type=? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $user_type);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $u = $res->fetch_assoc();
    if (password_verify($password, $u['password'])) {
        $_SESSION['user_id'] = (int)$u['id'];
        $_SESSION['user_type'] = $u['user_type'];
        $_SESSION['first_name'] = $u['first_name'];
        $_SESSION['last_name'] = $u['last_name'];
        if ($u['user_type'] === 'admin')      header("Location: ../dashboard/admin.php");
        elseif ($u['user_type'] === 'owner')  header("Location: ../dashboard/owner.php");
        else                                  header("Location: ../dashboard/renter.php");
        exit();
    } else { echo "Incorrect password!"; }
} else { echo "User not found!"; }
?>
