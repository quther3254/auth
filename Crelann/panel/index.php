<?php
session_start();

// Check if admin is logged in, if not redirect to login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// If logged in, redirect to dashboard
header('Location: dashboard.php');
exit();
?>
