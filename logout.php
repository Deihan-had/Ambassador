<?php
// panggil controller buat proses logout
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app/Controllers/AuthController.php';

$auth = new AuthController();
$auth->handleLogout();
?>