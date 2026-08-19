<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan — Ambassador</title>

    <!-- External Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .help-card {
            transition: all .2s ease;
        }

        .help-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
        }

        .chat-message {
            animation: messageIn .2s ease-out;
        }

        @keyframes messageIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .online-dot {
            animation: pulseOnline 2s infinite;
        }

        @keyframes pulseOnline {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, .25);
            }
            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">
                <!-- BRAND -->
                <a href="/webdesign/index.php">
                    <div class="flex items-center gap-2 cursor-pointer" onclick="resetFilters()">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i class="fa-solid fa-bag-shopping text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                                Ambas<span class="text-emerald-600">sador</span>
                            </span>
                            <span class="block text-[10px] text-slate-400 font-semibold -mt-1 tracking-widest uppercase">
                                Help Center
                            </span>
                        </div>
                    </div>
                </a>

                <!-- BACK TO STORE -->
                <a href="/webdesign/index.php" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Toko
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="bg-slate-900 text-white">
        <div class="max-w-5xl mx-auto px-4 py-14 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-semibold text-slate-300 mb-5">
                <i class="fa-solid fa-headset text-emerald-400"></i>
                Ambassador Support
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight mb-4">
                Ada yang bisa kami bantu?
            </h1>

            <p class="max-w-2xl mx-auto text-sm sm:text-base leading-relaxed text-slate-400 mb-8">
                Temukan jawaban atau langsung hubungi tim Ambassador untuk mendapatkan bantuan.
            </p>

            <!-- SEARCH -->
            <div class="max-w-2xl mx-auto relative">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="helpSearch" placeholder="Cari bantuan, misalnya: cara pembelian..." class="w-full h-14 pl-12 pr-5 rounded-2xl bg-white text-slate-800 text-sm outline-none placeholder:text-slate-400 shadow-xl">
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- CHAT ADMIN HERO CARD -->
        <section class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-8">
            <div class="p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-7">
                <!-- LEFT -->
                <div class="flex items-start gap-5">
                    <!-- ADMIN AVATAR -->
                    <div class="relative shrink-0">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-headset text-2xl"></i>
                        </div>
                        <span class="online-dot absolute -right-1 -bottom-1 w-4 h-4 rounded-full bg-emerald-500 border-[3px] border-white"></span>
                    </div>

                    <!-- INFO -->
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h2 class="text-xl font-black text-slate-900">Chat dengan Admin</h2>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">ONLINE</span>
                        </div>
                        <p class="text-sm text-slate-500 max-w-xl leading-relaxed">
                            Butuh bantuan langsung? Hubungi tim Ambassador dan dapatkan bantuan mengenai pesanan, pembayaran, pengiriman, garansi, dan lainnya.
                        </p>
                        <div class="flex flex-wrap items-center gap-4 mt-4 text-xs text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-clock"></i> Senin–Minggu
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-bolt text-emerald-500"></i> Respon cepat
                            </span>
                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <button onclick="openChat()" class="w-full lg:w-auto shrink-0 px-7 h-12 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm flex items-center justify-center gap-2 transition shadow-lg shadow-emerald-500/20">
                    <i class="fa-regular fa-comments"></i>
                    Mulai Chat
                </button>
            </div>
        </section>

        <!-- QUICK MENU -->
        <section class="mb-10">
            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-900">Bantuan Cepat</h2>
                <p class="text-sm text-slate-500 mt-1">Pilih topik yang ingin kamu tanyakan.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- PESANAN -->
                <button onclick="openChatWithTopic('Pesanan')" class="help-card bg-white border border-slate-200 rounded-2xl p-5 text-left">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Pesanan</h3>
                    <p class="text-xs text-slate-400 mt-1">Status dan pesanan</p>
                </button>

                <!-- PEMBAYARAN -->
                <button onclick="openChatWithTopic('Pembayaran')" class="help-card bg-white border border-slate-200 rounded-2xl p-5 text-left">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Pembayaran</h3>
                    <p class="text-xs text-slate-400 mt-1">Metode dan pembayaran</p>
                </button>

                <!-- PENGIRIMAN -->
                <button onclick="openChatWithTopic('Pengiriman')" class="help-card bg-white border border-slate-200 rounded-2xl p-5 text-left">
                    <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Pengiriman</h3>
                    <p class="text-xs text-slate-400 mt-1">Pengiriman dan kurir</p>
                </button>

                <!-- GARANSI -->
                <button onclick="openChatWithTopic('Garansi')" class="help-card bg-white border border-slate-200 rounded-2xl p-5 text-left">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Garansi</h3>
                    <p class="text-xs text-slate-400 mt-1">Klaim dan garansi</p>
                </button>
            </div>
        </section>

        <!-- SELF SERVICE -->
        <section class="mb-10">
            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-900">Panduan Ambassador</h2>
                <p class="text-sm text-slate-500 mt-1">Temukan informasi yang kamu butuhkan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <a href="../carapembelian.php" class="help-card bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-slate-900">Cara Pembelian</h3>
                        <p class="text-xs text-slate-400 mt-1">Panduan berbelanja</p>
                    </div>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-300"></i>
                </a>

                <a href="../../layanan/melacakpesanan.php" class="help-card bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-slate-900">Melacak Pesanan</h3>
                        <p class="text-xs text-slate-400 mt-1">Cek status pesanan</p>
                    </div>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-300"></i>
                </a>

                <a href="../kebijakangaransi.php" class="help-card bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-slate-900">Kebijakan Garansi</h3>
                        <p class="text-xs text-slate-400 mt-1">Ketentuan garansi</p>
                    </div>
                    <i class="fa-solid fa-chevron-right ml-auto text-xs text-slate-300"></i>
                </a>
            </div>
        </section>

        <!-- CONTACT ADMIN -->
        <section class="bg-slate-900 rounded-3xl p-6 sm:p-8 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-headset text-emerald-400"></i>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Butuh bantuan?</span>
                </div>
                <h2 class="text-2xl font-black mb-2">Tim Ambassador siap membantu.</h2>
                <p class="text-sm text-slate-400">Hubungi admin jika kamu tidak menemukan jawaban yang dibutuhkan.</p>
            </div>

            <button onclick="openChat()" class="px-6 h-12 rounded-xl bg-white text-slate-900 hover:bg-emerald-500 hover:text-white font-bold text-sm flex items-center justify-center gap-2 transition shrink-0">
                <i class="fa-regular fa-comments"></i>
                Chat dengan Admin
            </button>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <div class="font-black text-slate-900">Ambassador</div>
                    <p class="text-[10px] uppercase tracking-[0.14em] text-slate-400 mt-1">Premium Marketplace</p>
                </div>
                <div class="text-xs text-slate-400">
                    © 2026 Ambassador. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- FLOATING CHAT BUTTON -->
    <button onclick="openChat()" class="fixed right-5 bottom-5 z-40 w-14 h-14 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white shadow-xl shadow-emerald-500/25 flex items-center justify-center transition hover:scale-105" aria-label="Chat dengan Admin">
        <i class="fa-regular fa-comments text-xl"></i>
    </button>

    <!-- CHAT MODAL -->
    <div id="chatModal" class="fixed inset-0 z-[100] hidden">
        <!-- BACKDROP -->
        <div onclick="closeChat()" class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm"></div>

        <!-- CHAT WINDOW -->
        <div class="absolute right-4 bottom-4 sm:right-6 sm:bottom-6 w-[calc(100%-2rem)] sm:w-[420px] h-[min(680px,calc(100vh-2rem))] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col">
            <!-- CHAT HEADER -->
            <div class="bg-slate-900 text-white px-5 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <i class="fa-solid fa-headset text-emerald-400"></i>
                        </div>
                        <span class="absolute -right-0.5 -bottom-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                    </div>
                    <div>
                        <div class="font-bold text-sm">Admin Ambassador</div>
                        <div class="text-[11px] text-emerald-400 mt-0.5">Online • Siap membantu</div>
                    </div>
                </div>

                <button onclick="closeChat()" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition" aria-label="Tutup chat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- CHAT INFO -->
            <div class="px-4 py-3 bg-emerald-50 border-b border-emerald-100">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-circle-info text-emerald-600 text-xs mt-0.5"></i>
                    <p class="text-[11px] leading-relaxed text-emerald-800">
                        Jangan bagikan password, PIN, atau kode OTP kepada siapa pun.
                    </p>
                </div>
            </div>

            <!-- CHAT BODY -->
            <div id="chatMessages" class="chat-scroll flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                <!-- ADMIN MESSAGE 1 -->
                <div class="chat-message flex items-end gap-2">
                    <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-[10px]"></i>
                    </div>
                    <div class="max-w-[80%]">
                        <div class="bg-white border border-slate-200 rounded-2xl rounded-bl-md px-4 py-3 shadow-sm">
                            <p class="text-sm text-slate-700 leading-relaxed">
                                Halo! 👋 Selamat datang di Ambassador Help Center.
                            </p>
                        </div>
                        <div class="text-[10px] text-slate-400 mt-1 ml-1">Admin Ambassador</div>
                    </div>
                </div>

                <!-- ADMIN MESSAGE 2 -->
                <div class="chat-message flex items-end gap-2">
                    <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-[10px]"></i>
                    </div>
                    <div class="max-w-[80%]">
                        <div class="bg-white border border-slate-200 rounded-2xl rounded-bl-md px-4 py-3 shadow-sm">
                            <p class="text-sm text-slate-700 leading-relaxed">
                                Ada yang bisa kami bantu? Silakan pilih topik atau tuliskan pertanyaanmu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QUICK QUESTIONS -->
            <div class="px-4 py-3 bg-white border-t border-slate-100">
                <div class="flex gap-2 overflow-x-auto pb-1">
                    <button onclick="sendQuickMessage('Saya ingin menanyakan pesanan saya')" class="whitespace-nowrap px-3 py-2 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-xs font-semibold text-slate-600 transition">
                        Pesanan
                    </button>
                    <button onclick="sendQuickMessage('Saya ingin menanyakan pembayaran')" class="whitespace-nowrap px-3 py-2 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-xs font-semibold text-slate-600 transition">
                        Pembayaran
                    </button>
                    <button onclick="sendQuickMessage('Saya ingin menanyakan pengiriman')" class="whitespace-nowrap px-3 py-2 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-xs font-semibold text-slate-600 transition">
                        Pengiriman
                    </button>
                    <button onclick="sendQuickMessage('Saya ingin menanyakan garansi')" class="whitespace-nowrap px-3 py-2 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-xs font-semibold text-slate-600 transition">
                        Garansi
                    </button>
                </div>
            </div>

            <!-- INPUT -->
            <form id="chatForm" onsubmit="sendMessage(event)" class="p-3 bg-white border-t border-slate-200">
                <div class="flex items-center gap-2">
                    <input type="text" id="chatInput" placeholder="Tulis pesan..." autocomplete="off" class="flex-1 h-11 px-4 rounded-xl bg-slate-100 border border-transparent focus:bg-white focus:border-emerald-500 outline-none text-sm transition">
                    <button type="submit" class="w-11 h-11 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center transition" aria-label="Kirim pesan">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        let unclearCount = 0;

        function openChat() {
            const modal = document.getElementById("chatModal");
            modal.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");

            setTimeout(() => {
                document.getElementById("chatInput").focus();
            }, 200);
        }

        function closeChat() {
            document.getElementById("chatModal").classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }

        function openChatWithTopic(topic) {
            openChat();
            setTimeout(() => {
                sendQuickMessage("Halo Admin, saya ingin bertanya tentang " + topic);
            }, 250);
        }

        function sendQuickMessage(message) {
            addUserMessage(message);
            simulateAdminReply(message);
        }

        function sendMessage(event) {
            event.preventDefault();
            const input = document.getElementById("chatInput");
            const message = input.value.trim();

            if (!message) return;

            addUserMessage(message);
            input.value = "";
            simulateAdminReply(message);
        }

        function addUserMessage(message) {
            const container = document.getElementById("chatMessages");
            const messageWrapper = document.createElement("div");
            messageWrapper.className = "chat-message flex justify-end";

            messageWrapper.innerHTML = `
                <div class="max-w-[80%]">
                    <div class="bg-emerald-500 text-white rounded-2xl rounded-br-md px-4 py-3 shadow-sm">
                        <p class="text-sm leading-relaxed">${escapeHTML(message)}</p>
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 text-right mr-1">Kamu</div>
                </div>
            `;

            container.appendChild(messageWrapper);
            scrollChat();
        }

        function simulateAdminReply(message) {
            const container = document.getElementById("chatMessages");
            const typing = document.createElement("div");
            typing.id = "typingIndicator";
            typing.className = "chat-message flex items-end gap-2";

            typing.innerHTML = `
                <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-[10px]"></i>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl rounded-bl-md px-4 py-3 shadow-sm">
                    <div class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay:.1s"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay:.2s"></span>
                    </div>
                </div>
            `;

            container.appendChild(typing);
            scrollChat();

            setTimeout(() => {
                typing.remove();
                addAdminMessage(getAdminReply(message));
            }, 900);
        }

        function addAdminMessage(message) {
            const container = document.getElementById("chatMessages");
            const wrapper = document.createElement("div");
            wrapper.className = "chat-message flex items-end gap-2";

            wrapper.innerHTML = `
                <div class="w-7 h-7 rounded-lg bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-headset text-[10px]"></i>
                </div>
                <div class="max-w-[80%]">
                    <div class="bg-white border border-slate-200 rounded-2xl rounded-bl-md px-4 py-3 shadow-sm">
                        <p class="text-sm text-slate-700 leading-relaxed">${escapeHTML(message)}</p>
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 ml-1">Admin Ambassador</div>
                </div>
            `;

            container.appendChild(wrapper);
            scrollChat();
        }

        function getAdminReply(message) {
            const text = message.toLowerCase();

            // Deteksi Frustrasi
            const kataFrustrasi = [
                "kecewa", "kesal", "kesel", "marah",
                "lama banget", "parah", "komplain",
                "ga jelas", "gak jelas", "gapapa deh"
            ];
            const adaFrustrasi = kataFrustrasi.some(k => text.includes(k));
            const empati = adaFrustrasi ? "Saya mengerti kondisinya, mari kita selesaikan bersama. " : "";

            // Stok
            if (["stok", "ready", "varian", "ukuran", "warna", "preorder", "pre-order"].some(k => text.includes(k))) {
                unclearCount = 0;
                return empati + "📦 Boleh sebutkan nama atau varian produk yang ingin dicek? Saya akan bantu lihat ketersediaan stoknya untuk Anda.";
            }

            // Refund
            if (["refund", "dana kembali", "pengembalian"].some(k => text.includes(k))) {
                unclearCount = 0;
                return empati + "💳 Baik, boleh informasikan nomor pesanan dan metode pembayaran yang digunakan? Proses refund biasanya memakan waktu 3-7 hari kerja tergantung bank/e-wallet.";
            }

            // Pembayaran
            if (["pembayaran", "bayar", "kadaluarsa", "kedaluwarsa"].some(k => text.includes(k))) {
                unclearCount = 0;
                return empati + "💳 Untuk masalah pembayaran, boleh sertakan nomor pesanan dan metode pembayaran yang digunakan? Nanti saya bantu cek status pembayarannya.";
            }

            // Pengiriman - Alamat
            if (text.includes("alamat")) {
                unclearCount = 0;
                return empati + "🚚 Untuk ubah alamat pengiriman, selama pesanan belum masuk status dikirim biasanya masih bisa diubah. Boleh kasih nomor pesanan Anda supaya saya cek dulu statusnya?";
            }

            // Pengiriman - Status
            if (["belum sampai", "pengiriman", "dikirim", "kirim", "kurir", "resi", "jne", "j&t", "sicepat"].some(k => text.includes(k))) {
                unclearCount = 0;
                return empati + "🚚 Bisa bantu sertakan nomor pesanan Anda? Nanti saya cek status pengirimannya ya. Estimasi biasanya tergantung lokasi dan kurir yang dipilih.";
            }

            // Garansi / Kerusakan
            if (["garansi", "rusak", "cacat", "hilang"].some(k => text.includes(k))) {
                unclearCount = 0;
                return empati + "🛠️ Untuk klaim garansi atau barang rusak/hilang saat pengiriman, mohon siapkan nomor pesanan serta foto atau video kondisi produknya. Tim kami akan bantu proses klaimnya.";
            }

            // Pesanan Umum
            if (["pesanan", "order", "invoice", "lacak"].some(k => text.includes(k))) {
                unclearCount = 0;
                if (text.includes("batal")) {
                    return empati + '✅ Untuk pembatalan pesanan, boleh kirim nomor pesanan Anda? Pembatalan hanya bisa dilakukan selama status masih "Menunggu Pembayaran" atau belum diproses oleh gudang.';
                }
                return empati + "📦 Bisa bantu sertakan nomor pesanan atau email terdaftar Anda? Nanti saya bantu cek statusnya.";
            }

            // Fallback Bot
            unclearCount++;
            if (unclearCount >= 2) {
                unclearCount = 0;
                return "Untuk masalah ini, saya perlu bantuan tim kami agar lebih akurat. Mohon tunggu sebentar ya, admin kami akan segera membantu 🙏";
            }

            return "Terima kasih sudah menghubungi Ambassador 😊 Boleh dijelaskan sedikit lebih detail, apakah ini terkait pesanan, pembayaran, pengiriman, atau ketersediaan stok?";
        }

        function scrollChat() {
            const container = document.getElementById("chatMessages");
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 50);
        }

        function escapeHTML(text) {
            const div = document.createElement("div");
            div.textContent = text;
            return div.innerHTML;
        }

        // Live Search Filter
        document.getElementById("helpSearch").addEventListener("input", function () {
            const query = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll(".help-card");

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = (!query || text.includes(query)) ? "" : "none";
            });
        });

        // Close on ESC
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && !document.getElementById("chatModal").classList.contains("hidden")) {
                closeChat();
            }
        });
    </script>
</body>

</html>