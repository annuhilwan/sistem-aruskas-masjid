<?php
session_start();

// Fungsi check login
function is_login() {
    return isset($_SESSION['user_id']);
}

// Fungsi redirect jika belum login
function check_login() {
    if (!is_login()) {
        header("Location: login.php");
        exit;
    }
}

// Fungsi redirect jika sudah login
function redirect_if_logged_in() {
    if (is_login()) {
        header("Location: index.php");
        exit;
    }
}

// Fungsi logout
function logout() {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
