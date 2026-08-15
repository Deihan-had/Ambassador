<?php
// 1. Pastikan Session Aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Kosongkan semua data di $_SESSION
$_SESSION = array();

// 3. Hapus Cookie Session dari Browser (jika ada)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. Hancurkan session lama dan buat session baru untuk menyimpan pesan pemberitahuan
session_destroy();
session_start();
$_SESSION['success'] = "Anda telah berhasil keluar.";

// 5. Hitung Relative Path ke Halaman Login secara otomatis & fleksibel
// Cara ini membuat redirect SELALU BERHASIL baik di localhost/ambassador maupun di hosting
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Hapus /logout.php dari path jika ada
$baseUrl = rtrim($scriptDir, '/');

// Redirect langsung ke tampilan login
header("Location: " . $baseUrl . "/views/auth/login.php");
exit();
?>