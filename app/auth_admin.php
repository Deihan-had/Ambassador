<?php
// app/auth_admin.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah session admin sudah terdefinisi
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Jika belum login sebagai admin, lempar/redirect ke halaman login admin
    header("Location: views/auth/admin.php");
    exit();
}
?>