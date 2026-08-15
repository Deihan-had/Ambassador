<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../../config/database.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Melacak Pesanan — Ambassador</title>

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
    </style>
</head>

<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- HEADER -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                <!-- Logo -->
                <a href="/webdesign/index.php">
                    <div class="flex items-center gap-2 cursor-pointer">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i class="fa-solid fa-bag-shopping text-xl"></i>
                        </div>
                        <div>
                            <span
                                class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                                Ambas<span class="text-emerald-600">sador</span>
                            </span>
                            <span
                                class="block text-[10px] text-slate-400 font-semibold -mt-1 tracking-widest uppercase">
                                Melacak Pesanan
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Back -->
                <a href="/webdesign/index.php"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali Belanja
                </a>

            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="bg-slate-900 text-white py-14">
        <div class="max-w-3xl mx-auto px-4 text-center">

            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-semibold text-emerald-400 mb-4">
                <i class="fa-solid fa-truck-fast"></i>
                Real-Time Tracking System
            </div>

            <h1 class="text-3xl sm:text-4xl font-black tracking-tight mb-3">
                Lacak Lokasi & Status Pesanan
            </h1>

            <p class="text-slate-400 text-sm max-w-lg mx-auto mb-8">
                Masukkan Kode Invoice atau Nomor WhatsApp yang terdaftar untuk melihat progres pengiriman barang kamu.
            </p>

            <!-- Tracking Form -->
            <form onsubmit="handleTrack(event)"
                class="flex flex-col sm:flex-row gap-3 bg-white p-2 rounded-2xl shadow-xl max-w-xl mx-auto text-slate-800">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="trackInput" required placeholder="Masukkan No. Invoice (mis: #INV-89123)"
                        class="w-full pl-11 pr-4 py-3 rounded-xl text-sm outline-none focus:bg-slate-50">
                </div>

                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shrink-0 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-location-dot"></i>
                    Lacak Pesanan
                </button>
            </form>

        </div>
    </section>

    <!-- RESULT SECTION -->
    <main class="max-w-4xl mx-auto px-4 py-12 flex-1 w-full">

        <!-- Initial Placeholder -->
        <div id="initialState" class="text-center py-12 bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
            <div
                class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Siap Memantau Paketmu</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto">
                Silakan ketik nomor invoice pesananmu di kolom pencarian di atas untuk melihat detail status pengiriman.
            </p>
        </div>

        <!-- Tracking Details (Hidden by Default) -->
        <div id="trackingResult" class="hidden space-y-6">

            <!-- Order Info Header -->
            <div
                class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span id="resStatusBadge"
                        class="inline-block bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full mb-2">
                        Dalam Pengiriman
                    </span>
                    <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2" id="resInvoice">
                        #INV-89123
                    </h2>
                    <p class="text-xs text-slate-500 mt-1" id="resDate">
                        Dipesan pada: 14 Agustus 2026, 14:30 WIB
                    </p>
                </div>

                <div class="text-left sm:text-right border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                    <span class="text-xs text-slate-400 block font-semibold uppercase">Kurir Ekspedisi</span>
                    <span class="text-sm font-bold text-slate-800 flex items-center gap-1.5 sm:justify-end">
                        <i class="fa-solid fa-truck text-emerald-600"></i>
                        Ambassador Express (Reguler)
                    </span>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i>
                    Riwayat Perjalanan
                </h3>

                <div class="relative pl-6 border-l-2 border-emerald-500 space-y-8">

                    <!-- Step 1 -->
                    <div class="relative">
                        <div
                            class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-emerald-600 border-4 border-white shadow">
                        </div>
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Terbaru</p>
                        <h4 class="text-sm font-bold text-slate-800">Paket dalam perjalanan menuju lokasi tujuan</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Bandung Hub - Kurir sedang mengantar ke alamat
                            penerima.</p>
                        <span class="text-[11px] text-slate-400 font-mono mt-1 block">14 Agt 2026, 17:45 WIB</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div
                            class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-slate-300 border-4 border-white">
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">Tiba di Sorting Center Bandung</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Paket telah disortir dan diproses untuk kurir akhir.
                        </p>
                        <span class="text-[11px] text-slate-400 font-mono mt-1 block">14 Agt 2026, 12:10 WIB</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div
                            class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-slate-300 border-4 border-white">
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">Pesanan Diproses Penjual</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Gudang Ambassador telah mengemas barang dan menyerahkan
                            ke kurir.</p>
                        <span class="text-[11px] text-slate-400 font-mono mt-1 block">14 Agt 2026, 09:00 WIB</span>
                    </div>

                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-xs text-slate-400">
            © 2026 Ambassador Inc. All rights reserved.
        </div>
    </footer>

    <script>
        function handleTrack(e) {
            e.preventDefault();
            const inputVal = document.getElementById('trackInput').value.trim();

            if (!inputVal) return;

            document.getElementById('initialState').classList.add('hidden');
            const resultDiv = document.getElementById('trackingResult');
            resultDiv.classList.remove('hidden');

            document.getElementById('resInvoice').innerText = inputVal.toUpperCase();
        }
    </script>

</body>

</html>