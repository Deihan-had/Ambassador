<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Path ke folder admin
$admin_dashboard_url = "/webdesign/ambassador-admin/index.php";

// Jika sudah login sebagai admin, langsung redirect ke dashboard admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: " . $admin_dashboard_url);
    exit();
}

// Memanggil bootstrap menggunakan path relatif dari posisi file admin.php saat ini
require_once __DIR__ . '/../../ambassador-admin/includes/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan Password wajib diisi!';
    } else {
        if (isset($conn)) {
            $stmt = mysqli_prepare($conn, "SELECT id_users, username, password, role FROM users WHERE username = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password'])) {
                if (isset($user['role']) && strtolower($user['role']) === 'admin') {
                    $_SESSION['is_admin'] = true;
                    $_SESSION['id_users'] = $user['id_users'];
                    $_SESSION['username'] = $user['username'];

                    header("Location: " . $admin_dashboard_url);
                    exit();
                } else {
                    $error = 'Akses ditolak! Akun Anda bukan Administrator.';
                }
            } else {
                $error = 'Username atau Password salah!';
            }
        } else {
            $error = 'Koneksi database ($conn) tidak ditemukan di bootstrap.php';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Ambassador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white w-full max-w-md rounded-2xl p-8 shadow-2xl border border-slate-700">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Admin Portal</h1>
            <p class="text-xs text-slate-500 mt-1">Masuk untuk mengelola toko online Anda</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-xs font-semibold mb-4 border border-red-200">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Username / Email Admin</label>
                <input type="text" name="username" required placeholder="Masukkan username"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <button type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all text-sm mt-2">
                Masuk Dashboard
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="../../index.php" class="text-xs text-slate-400 hover:text-slate-600">← Kembali ke Toko</a>
        </div>
    </div>
</body>
</html>