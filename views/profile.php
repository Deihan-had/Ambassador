<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['id_users']) || isset($_SESSION['user']);

if (!$isLoggedIn) {
    // Relative Path ke Halaman Login
    header("Location: ../auth/login.php");
    exit();
}

// PERBAIKAN: Memastikan variabel dipanggil dengan aman tanpa menyebabkan Fatal Error / Notice jika session berstruktur array atau string
$username = 'Pengguna';
if (isset($_SESSION['user']) && is_array($_SESSION['user']) && !empty($_SESSION['user']['username'])) {
    $username = $_SESSION['user']['username'];
} elseif (isset($_SESSION['username']) && is_string($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

$email = 'pengguna@gmail.com';
if (isset($_SESSION['user']) && is_array($_SESSION['user']) && !empty($_SESSION['user']['email'])) {
    $email = $_SESSION['user']['email'];
} elseif (isset($_SESSION['email']) && is_string($_SESSION['email'])) {
    $email = $_SESSION['email'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Saya — Ambassador</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                <!-- Relative Path ke Index Utama -->
                <a href="../index.php" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                            Ambas<span class="text-emerald-600">sador</span>
                        </span>
                    </div>
                </a>

                <!-- Relative Path ke Index Utama -->
                <a href="../index.php" class="text-xs font-bold text-slate-600 hover:text-emerald-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i>
                    Ke Toko
                </a>

            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="max-w-4xl mx-auto px-4 py-10 flex-1 w-full">

        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">

            <!-- Profile Header -->
            <div class="flex flex-col sm:flex-row items-center gap-5 pb-6 border-b border-slate-100 text-center sm:text-left">
                <div class="w-20 h-20 rounded-full bg-emerald-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <?php echo strtoupper(mb_substr($username, 0, 1)); ?>
                </div>

                <div class="space-y-1">
                    <h1 class="text-xl font-extrabold text-slate-900"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p class="text-xs text-slate-400 font-medium"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
                    <span class="inline-block bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-100">
                        Member Terverifikasi
                    </span>
                </div>
            </div>

            <!-- Navigation Links Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-6">

                <!-- Relative Path ke Pesanan Saya -->
                <a href="orders.php" class="p-4 rounded-2xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="fa-solid fa-box-archive"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Pesanan Saya</h3>
                            <p class="text-xs text-slate-400">Cek status & riwayat belanja</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600"></i>
                </a>

                <!-- Relative Path ke Pusat Bantuan -->
                <a href="/webdesign/views/pages/pusatbantuan.php" class="p-4 rounded-2xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="fa-solid fa-circle-question"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Pusat Bantuan</h3>
                            <p class="text-xs text-slate-400">Panduan dan FAQ</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600"></i>
                </a>

                <!-- Relative Path ke Cara Pembelian -->
                <a href="/webdesign/views/pages/carapembelian.php" class="p-4 rounded-2xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Cara Pembelian</h3>
                            <p class="text-xs text-slate-400">Langkah-langkah pemesanan</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600"></i>
                </a>

                <!-- Relative Path ke Kebijakan Garansi -->
                <a href="/webdesign/views/pages/kebijakangaransi.php" class="p-4 rounded-2xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Kebijakan Garansi</h3>
                            <p class="text-xs text-slate-400">Syarat & ketentuan klaim</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600"></i>
                </a>

            </div>

            <!-- Profile Form -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-sm font-bold text-slate-800">Detail Kontak Default</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Pengguna</label>
                        <input type="text" readonly value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Email</label>
                        <input type="text" readonly value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700">
                    </div>
                </div>
            </div>

            <!-- Logout Button -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <a href="../../logout.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Keluar dari Akun (Logout)
                </a>
            </div>

        </div>

    </main>

    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        © 2026 Ambassador Inc. All rights reserved.
    </footer>

</body>

</html>