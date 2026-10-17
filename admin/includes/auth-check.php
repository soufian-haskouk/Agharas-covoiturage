<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
?>