<?php
// Menjalankan session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi database
require_once __DIR__ . '/../../config/database.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Pembelian — Ambassador</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .step-card {
            transition: .2s ease;
        }

        .step-card:hover {
            transform: translateY(-4px);
        }

        .step-number {
            box-shadow: 0 8px 25px rgba(16, 185, 129, .18);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                <a href="/index.php">
                    <div class="flex items-center gap-2 cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i class="fa-solid fa-bag-shopping text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                                Ambas<span class="text-emerald-600">sador</span>
                            </span>
                            <span class="block text-[10px] text-slate-400 font-semibold -mt-1 tracking-widest uppercase">
                                Cara Pembelian
                            </span>
                        </div>
                    </div>
                </a>

                <a href="/index.php" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali Belanja
                </a>

            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-slate-900 text-white">
        <div class="max-w-5xl mx-auto px-4 py-16 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-semibold text-slate-300 mb-5">
                <i class="fa-solid fa-book-open text-emerald-400"></i>
                Panduan Belanja Ambassador
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight mb-4">
                Cara Pembelian
            </h1>

            <p class="max-w-2xl mx-auto text-sm sm:text-base leading-relaxed text-slate-400">
                Ikuti beberapa langkah sederhana berikut untuk membeli produk favoritmu di Ambassador.
            </p>
        </div>
    </section>

    <!-- Konten utama -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        <!-- Langkah 1 -->
        <section class="step-card bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-5">
            <div class="flex gap-5">
                <div class="step-number shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    01
                </div>

                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Pilih Produk
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-2">
                        Temukan produk yang kamu inginkan
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Jelajahi produk di Ambassador dan pilih produk yang ingin kamu beli. Pastikan kamu memperhatikan informasi produk dan ketersediaan stok.
                    </p>
                </div>
            </div>
        </section>

        <!-- Langkah 2 -->
        <section class="step-card bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-5">
            <div class="flex gap-5">
                <div class="step-number shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    02
                </div>

                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                        <i class="fa-solid fa-cart-plus"></i>
                        Pilih Cara Membeli
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-3">
                        Tambahkan ke keranjang atau langsung beli
                    </h2>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-1">
                                Tambah ke Keranjang
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Cocok jika kamu ingin membeli beberapa produk sekaligus sebelum checkout.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-1">
                                Beli Sekarang
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Cocok jika kamu sudah yakin dengan satu produk dan ingin langsung menuju checkout.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Langkah 3 -->
        <section class="step-card bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-5">
            <div class="flex gap-5">
                <div class="step-number shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    03
                </div>

                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                        <i class="fa-solid fa-basket-shopping"></i>
                        Periksa Keranjang
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-2">
                        Pastikan produk dan jumlahnya benar
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Jika menggunakan keranjang, periksa kembali produk, jumlah barang, dan total harga. Jumlah pembelian juga akan menyesuaikan stok produk yang tersedia.
                    </p>
                </div>
            </div>
        </section>

        <!-- Langkah 4 -->
        <section class="step-card bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-5">
            <div class="flex gap-5">
                <div class="step-number shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    04
                </div>
                <div class="w-full">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                        <i class="fa-solid fa-ticket"></i>
                        Gunakan Promo
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-2">
                        Masukkan kode promo jika tersedia
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed mb-5">
                        Sebelum menyelesaikan pembelian, kamu dapat memasukkan kode promo pada bagian checkout untuk mendapatkan potongan harga.
                    </p>

                    <div class="inline-flex items-center gap-3 px-4 py-3 rounded-xl bg-amber-50 border border-amber-100">
                        <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-amber-600">
                                Kode Promo Diskon
                            </div>
                            <div class="font-black text-amber-800">
                                AMBASDISKON / DISCOUNT10
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Langkah 5 -->
        <section class="step-card bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-5">
            <div class="flex gap-5">
                <div class="step-number shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    05
                </div>

                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                        <i class="fa-solid fa-credit-card"></i>
                        Checkout
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-2">
                        Lanjutkan ke proses checkout
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Setelah produk dan promo sudah sesuai, lanjutkan ke checkout. Periksa kembali total pembayaran sebelum menyelesaikan pesanan.
                    </p>
                </div>
            </div>
        </section>

        <!-- Langkah 6 -->
        <section class="step-card bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-5">
            <div class="flex gap-5">
                <div class="step-number shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    06
                </div>

                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">
                        <i class="fa-solid fa-circle-check"></i>
                        Pesanan Berhasil
                    </div>
                    <h2 class="text-xl font-black text-slate-900 mb-2">
                        Selesaikan pesanan
                    </h2>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Setelah pesanan berhasil diproses, sistem akan menampilkan konfirmasi pembelian beserta nomor invoice pesanan.
                    </p>

                    <div class="mt-5 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-emerald-600 mt-0.5"></i>
                        <p class="text-xs text-emerald-800 leading-relaxed">
                            Simpan nomor invoice yang ditampilkan setelah pembelian berhasil untuk referensi pesanan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alur singkat -->
        <section class="mt-14">
            <div class="bg-slate-900 rounded-3xl p-7 sm:p-10 text-white">
                <div class="text-center mb-8">
                    <p class="text-xs uppercase tracking-widest font-bold text-emerald-400 mb-2">
                        Ringkasan
                    </p>
                    <h2 class="text-2xl font-black">
                        Alur pembelian Ambassador
                    </h2>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-center gap-3">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-xl bg-white/10 mx-auto flex items-center justify-center">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div class="text-xs font-bold mt-2">Pilih Produk</div>
                    </div>

                    <i class="fa-solid fa-arrow-down md:fa-arrow-right text-slate-600"></i>

                    <div class="text-center">
                        <div class="w-12 h-12 rounded-xl bg-white/10 mx-auto flex items-center justify-center">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div class="text-xs font-bold mt-2">Keranjang</div>
                    </div>

                    <i class="fa-solid fa-arrow-down md:fa-arrow-right text-slate-600"></i>

                    <div class="text-center">
                        <div class="w-12 h-12 rounded-xl bg-white/10 mx-auto flex items-center justify-center">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <div class="text-xs font-bold mt-2">Promo</div>
                    </div>

                    <i class="fa-solid fa-arrow-down md:fa-arrow-right text-slate-600"></i>

                    <div class="text-center">
                        <div class="w-12 h-12 rounded-xl bg-white/10 mx-auto flex items-center justify-center">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div class="text-xs font-bold mt-2">Checkout</div>
                    </div>

                    <i class="fa-solid fa-arrow-down md:fa-arrow-right text-slate-600"></i>

                    <div class="text-center">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500 mx-auto flex items-center justify-center">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="text-xs font-bold mt-2">Berhasil</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tombol ajakan belanja -->
        <section class="text-center mt-14">
            <h2 class="text-2xl font-black text-slate-900 mb-2">
                Siap mulai belanja?
            </h2>
            <p class="text-sm text-slate-500 mb-6">
                Temukan produk favoritmu di Ambassador.
            </p>

            <a href="/index.php#productGrid" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-sm font-bold transition shadow-md">
                <i class="fa-solid fa-bag-shopping"></i>
                Mulai Belanja Sekarang
            </a>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <div class="font-black text-slate-900">
                        Ambas<span class="text-emerald-500">sador</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">
                        Belanja lebih mudah, lebih nyaman.
                    </p>
                </div>

                <div class="text-xs text-slate-400">
                    © 2026 Ambassador. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</body>
</html>