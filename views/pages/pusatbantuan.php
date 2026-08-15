<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/database.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan — Ambassador</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .recommend-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .recommend-card:hover {
            transform: translateY(-4px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <!-- NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                <!-- Logo -->
                <a href="/webdesign/index.php">
                    <div class="flex items-center gap-2 cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i class="fa-solid fa-bag-shopping text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                                Ambas<span class="text-emerald-600">sador</span>
                            </span>
                            <span class="block text-[10px] text-slate-400 font-semibold -mt-1 tracking-widest uppercase">
                                Pusat Bantuan
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Back -->
                <a href="/webdesign/index.php" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali Belanja
                </a>

            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="bg-slate-900 text-white">
        <div class="max-w-5xl mx-auto px-4 py-16 text-center">

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-semibold text-slate-200 mb-5">
                <i class="fa-solid fa-circle-question text-emerald-400"></i>
                Ambassador Help Center
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight mb-4">
                Butuh Bantuan?
            </h1>

            <p class="text-slate-400 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Temukan rekomendasi dan panduan singkat agar pengalaman belanja kamu di Ambassador lebih mudah.
            </p>

            <!-- Search -->
            <div class="max-w-2xl mx-auto mt-8">
                <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="helpSearch" type="text" placeholder="Cari bantuan, misalnya wishlist atau promo..."
                        class="w-full pl-12 pr-5 py-4 text-sm text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

        </div>
    </section>

    <!-- RECOMMENDATION SECTION -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">Rekomendasi</p>
            <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">Mulai dari sini</h2>
            <p class="mt-2 text-sm text-slate-500">Beberapa hal yang paling direkomendasikan sebelum kamu berbelanja.</p>
        </div>

        <!-- Cards -->
        <div id="recommendationGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Wishlist -->
            <div class="recommend-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm" data-search="wishlist simpan produk favorit hati">
                <div class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Gunakan Wishlist</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Simpan produk yang kamu suka agar lebih mudah ditemukan kembali sebelum membeli.
                </p>
                <a href="/webdesign/index.php#productGrid" class="inline-flex items-center gap-2 mt-5 text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Lihat produk <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Promo -->
            <div class="recommend-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm" data-search="promo diskon discount10 ambasdiskon kode promo voucher diskon 10 persen">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Manfaatkan Promo</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Sebelum checkout, cek kode promo yang tersedia untuk mendapatkan harga lebih hemat.
                </p>
                <div class="mt-5 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold">
                    <i class="fa-solid fa-tag"></i> AMBASDISKON
                </div>
            </div>

            <!-- Cart -->
            <div class="recommend-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm" data-search="keranjang cart tambah produk jumlah stok belanja">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Cek Keranjang</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Periksa jumlah produk, stok, promo, dan total harga sebelum melanjutkan ke checkout.
                </p>
                <a href="/webdesign/index.php#productGrid" class="inline-flex items-center gap-2 mt-5 text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Belanja sekarang <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Buy Now -->
            <div class="recommend-card bg-white border border-slate-200 rounded-2xl p-6 shadow-sm" data-search="beli sekarang buy now checkout langsung produk">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Gunakan Beli Sekarang</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Sudah menemukan produk yang cocok? Gunakan <b>Beli Sekarang</b> untuk langsung menuju checkout.
                </p>
                <a href="/webdesign/index.php#productGrid" class="inline-flex items-center gap-2 mt-5 text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Cari produk <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>

        <!-- FAQ -->
        <section class="mt-16">
            <div class="mb-7">
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2">Panduan singkat</p>
                <h2 class="text-2xl font-black text-slate-900">Rekomendasi sebelum checkout</h2>
            </div>

            <div id="faqList" class="space-y-3">

                <!-- FAQ 1 -->
                <div class="faq-item bg-white border border-slate-200 rounded-2xl overflow-hidden" data-search="wishlist rekomendasi simpan produk favorit">
                    <button type="button" class="faq-question w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50 transition">
                        <span class="font-semibold text-sm text-slate-800">Bagaimana rekomendasi menggunakan Wishlist?</span>
                        <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-500">
                            Kalau kamu belum ingin membeli produk sekarang, masukkan produk tersebut ke Wishlist. Dengan begitu, produk favorit bisa kamu akses kembali dengan lebih mudah.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item bg-white border border-slate-200 rounded-2xl overflow-hidden" data-search="promo discount10 ambasdiskon diskon kode promo rekomendasi">
                    <button type="button" class="faq-question w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50 transition">
                        <span class="font-semibold text-sm text-slate-800">Kapan sebaiknya menggunakan kode promo?</span>
                        <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-500">
                            Kami merekomendasikan memasukkan kode promo sebelum checkout agar kamu dapat melihat total harga setelah diskon. Kode promo yang tersedia saat ini adalah <b>AMBASDISKON</b> untuk diskon 10%.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item bg-white border border-slate-200 rounded-2xl overflow-hidden" data-search="keranjang cart stok jumlah produk rekomendasi checkout">
                    <button type="button" class="faq-question w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50 transition">
                        <span class="font-semibold text-sm text-slate-800">Apa yang sebaiknya dicek sebelum checkout?</span>
                        <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-500">
                            Pastikan produk dan jumlahnya sudah benar, stok masih tersedia, serta cek kembali total harga setelah promo diterapkan.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item bg-white border border-slate-200 rounded-2xl overflow-hidden" data-search="beli sekarang buy now langsung checkout rekomendasi">
                    <button type="button" class="faq-question w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50 transition">
                        <span class="font-semibold text-sm text-slate-800">Kapan sebaiknya memakai Beli Sekarang?</span>
                        <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-500">
                            Gunakan Beli Sekarang ketika kamu sudah yakin dengan satu produk dan ingin langsung menuju proses checkout tanpa perlu mengatur isi keranjang terlebih dahulu.
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-item bg-white border border-slate-200 rounded-2xl overflow-hidden" data-search="stok produk habis rekomendasi belanja">
                    <button type="button" class="faq-question w-full px-5 py-4 flex items-center justify-between text-left hover:bg-slate-50 transition">
                        <span class="font-semibold text-sm text-slate-800">Apa yang dilakukan jika produk habis?</span>
                        <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-500">
                            Kami merekomendasikan mencari produk alternatif yang tersedia. Sistem Ambassador juga melakukan pengecekan stok sebelum produk dimasukkan ke keranjang.
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- BOTTOM CTA -->
        <section class="mt-16">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl px-6 py-10 sm:px-10 text-center text-white overflow-hidden relative">
                <div class="absolute -top-20 -right-20 w-56 h-56 rounded-full bg-emerald-500/10"></div>
                <div class="absolute -bottom-24 -left-20 w-64 h-64 rounded-full bg-blue-500/10"></div>

                <div class="relative">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-white/10 flex items-center justify-center mb-5">
                        <i class="fa-solid fa-bag-shopping text-emerald-400"></i>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black mb-3">
                        Sudah menemukan produk yang kamu cari?
                    </h2>

                    <p class="text-sm text-slate-400 max-w-xl mx-auto mb-6">
                        Yuk kembali ke Ambassador dan mulai belanja.
                    </p>

                    <a href="/webdesign/index.php#productGrid" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-bold transition shadow-lg">
                        <i class="fa-solid fa-cart-shopping"></i> Mulai Belanja
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <div class="font-black tracking-tight text-slate-900">
                        Ambas<span class="text-emerald-500">sador</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Belanja lebih mudah, lebih nyaman.</p>
                </div>
                <div class="text-xs text-slate-400">
                    © 2026 Ambassador. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <script>
        // FAQ ACCORDION
        document.querySelectorAll(".faq-question").forEach(button => {
            button.addEventListener("click", () => {
                const item = button.closest(".faq-item");
                document.querySelectorAll(".faq-item.active").forEach(activeItem => {
                    if (activeItem !== item) {
                        activeItem.classList.remove("active");
                    }
                });
                item.classList.toggle("active");
            });
        });

        // SEARCH HELP CENTER
        const searchInput = document.getElementById("helpSearch");
        const searchableItems = document.querySelectorAll("[data-search]");

        if (searchInput) {
            searchInput.addEventListener("input", () => {
                const keyword = searchInput.value.trim().toLowerCase();
                searchableItems.forEach(item => {
                    const text = item.getAttribute("data-search").toLowerCase();
                    if (keyword === "" || text.includes(keyword)) {
                        item.classList.remove("hidden");
                    } else {
                        item.classList.add("hidden");
                    }
                });
            });
        }

        // ESC UNTUK MENUTUP FAQ
        document.addEventListener("keydown", event => {
            if (event.key === "Escape") {
                document.querySelectorAll(".faq-item.active").forEach(item => {
                    item.classList.remove("active");
                });
            }
        });
    </script>
</body>
</html>