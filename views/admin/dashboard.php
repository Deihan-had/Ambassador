<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login DAN apakah rolenya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Akses ditolak! Anda bukan Admin.";
    header("Location: ../../views/auth/login.php");
    exit();
}
?>