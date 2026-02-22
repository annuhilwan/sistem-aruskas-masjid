<?php
require_once 'config/database.php';
require_once 'config/session.php';

check_login();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout();
}

// Jika akses langsung logout.php
if (strpos($_SERVER['REQUEST_URI'], 'logout.php') !== false) {
    logout();
}
?>
