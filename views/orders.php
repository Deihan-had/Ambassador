<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek status login
$isLoggedIn = isset($_SESSION['id_users']) || isset($_SESSION['user']) || isset($_SESSION['username']);

if (!$isLoggedIn) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['user']['nama'] ?? $_SESSION['user']['username'] ?? $_SESSION['username'] ?? 'Pengguna';
$orders = $_SESSION['user_orders'] ?? [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesanan Saya — Ambassador</title>

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

                <div class="flex items-center gap-3">
                    <a href="profile.php" class="text-xs font-bold text-slate-700 hover:text-emerald-600 flex items-center gap-2 bg-slate-100 px-3 py-2 rounded-xl">
                        <i class="fa-regular fa-user"></i>
                        <span><?php echo htmlspecialchars($username); ?></span>
                    </a>

                    <a href="../index.php" class="text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-3.5 py-2 rounded-xl shadow transition">
                        Belanja Lagi
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="max-w-5xl mx-auto px-4 py-10 flex-1 w-full">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Pesanan Saya</h1>
                <p class="text-xs text-slate-500 mt-1">Pantau seluruh status dan daftar riwayat transaksi kamu.</p>
            </div>
        </div>

        <!-- Order Filter Tabs -->
        <div class="flex gap-2 overflow-x-auto pb-3 mb-6 scrollbar-none border-b border-slate-200">
            <button class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white shrink-0">
                Semua Pesanan
            </button>
            <button class="px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 shrink-0">
                Dalam Proses
            </button>
            <button class="px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 shrink-0">
                Dikirim
            </button>
            <button class="px-4 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 shrink-0">
                Selesai
            </button>
        </div>

        <!-- Orders List Container Dinamis -->
        <div class="space-y-4">

            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                        
                        <!-- Header Card Pesanan -->
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-xs font-bold text-slate-800">#<?php echo htmlspecialchars($order['order_id']); ?></span>
                                <span class="text-slate-300">|</span>
                                <span class="text-xs text-slate-400"><?php echo isset($order['date']) ? $order['date'] : date('d M Y'); ?></span>
                            </div>

                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[10px]"></i>
                                <?php echo htmlspecialchars($order['status'] ?? 'Dalam Proses'); ?>
                            </span>
                        </div>

                        <!-- Detail Barang (Looping Barang dari Session) -->
                        <div class="space-y-3">
                            <?php if (isset($order['items']) && is_array($order['items'])): ?>
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-emerald-600 text-lg shrink-0">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                            <div class="truncate">
                                                <h4 class="text-sm font-bold text-slate-800 truncate"><?php echo htmlspecialchars($item['name']); ?></h4>
                                                <p class="text-xs text-slate-400 mt-0.5"><?php echo $item['quantity']; ?> Barang x Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-sm font-extrabold text-slate-800">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Action & Total Pembayaran Footer -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <div>
                                <span class="text-xs text-slate-400 block">Total Belanja</span>
                                <span class="text-base font-extrabold text-emerald-600">Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></span>
                            </div>
                            <a href="layanan/melacakpesanan.php" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition">
                                Lacak Paket
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State (Jika belum ada pesanan) -->
                <div class="text-center py-12 bg-white rounded-2xl border border-slate-200">
                    <i class="fa-solid fa-receipt text-4xl text-slate-300 mb-3"></i>
                    <p class="text-sm font-semibold text-slate-500">Tidak ada riwayat pesanan.</p>
                </div>
            <?php endif; ?>

        </div>

    </main>

    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        © 2026 Ambassador Inc. All rights reserved.
    </footer>

</body>

</html>