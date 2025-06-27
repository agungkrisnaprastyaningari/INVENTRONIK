<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// Debug session
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['log']) || $_SESSION['log'] !== 'true') {
    header('location:login.php');
    exit();
}
?> 