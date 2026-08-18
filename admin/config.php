<?php
session_start();

// Load koneksi database
require_once __DIR__ . '/../config/database.php';

$dbObj = new Database();
$conn = $dbObj->getConnection();

// Proteksi halaman admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}
?>