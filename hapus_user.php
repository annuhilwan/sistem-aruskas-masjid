<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/User.php';

check_login();

$user_id = $_GET['id'] ?? 0;

if (empty($user_id)) {
    header("Location: daftar_user.php");
    exit;
}

$user = new User($conn);

// Prevent deleting yourself
if ($user_id == $_SESSION['user_id']) {
    header("Location: daftar_user.php?error=Anda tidak dapat menghapus akun sendiri!");
    exit;
}

$result = $user->delete_user($user_id);

if ($result['success']) {
    header("Location: daftar_user.php?status=deleted");
} else {
    header("Location: daftar_user.php?error=" . urlencode($result['message']));
}
exit;
?>
