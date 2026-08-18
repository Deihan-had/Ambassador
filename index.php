<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Error Display (Bisa dimatikan di production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$isLoggedIn = false;
$username = 'Pengguna';
$user_id = null;

// Normalisasi Penanganan Session User
if (isset($_SESSION['id_users'])) {
    $user_id = $_SESSION['id_users'];
    $isLoggedIn = true;
}

if (isset($_SESSION['user'])) {
    $isLoggedIn = true;

    if (is_array($_SESSION['user'])) {
        // Jika $_SESSION['user'] berbentuk Array
        if (!empty($_SESSION['user']['username'])) {
            $username = $_SESSION['user']['username'];
        } elseif (!empty($_SESSION['user']['nama'])) {
            $username = $_SESSION['user']['nama'];
        } elseif (!empty($_SESSION['user']['name'])) {
            $username = $_SESSION['user']['name'];
        }

        if (!$user_id && !empty($_SESSION['user']['id_users'])) {
            $user_id = $_SESSION['user']['id_users'];
        } elseif (!$user_id && !empty($_SESSION['user']['id'])) {
            $user_id = $_SESSION['user']['id'];
        }
    } elseif (is_string($_SESSION['user'])) {
        // Jika $_SESSION['user'] disimpan berupa string biasa
        $username = $_SESSION['user'];
    }
}

if (isset($_SESSION['username']) && is_string($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $isLoggedIn = true;
}

// Memastikan $username adalah String & Tidak Kosong
if (!is_string($username) || empty(trim($username))) {
    $username = 'Pengguna';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ambassador - Toko Online</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
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
    </style>
</head>

<body class="bg-slate-50 text-slate-800 flex-col min-h-screen">

    <!-- Promo Topbar -->
    <div class="bg-slate-900 text-white text-xs py-2 px-6 text-center flex justify-between items-center">
        <span class="hidden sm:inline">
            <i class="fa-solid fa-truck-fast mr-2 text-emerald-500"></i>
            Gratis Ongkir Seluruh Indonesia min. belanja Rp 250.000
        </span>

        <div class="mx-auto sm:mx-0 flex items-center">
            <span>
                <i class="fa-solid fa-bolt text-yellow-400 mr-1"></i>
                Promo Spesial: Gunakan Kode
                <span class="bg-emerald-600 text-white px-1.5 py-0.5 rounded font-bold cursor-pointer"
                    onclick="applyPromoQuick('AMBASDISKON')">
                    AMBASDISKON
                </span>
                untuk diskon 10%
            </span>
        </div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Logo -->
                <div class="flex items-center gap-2.5 cursor-pointer shrink-0"
                    onclick="if(typeof resetFilters === 'function') resetFilters();">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                    </div>
                    <div>
                        <span
                            class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                            Ambas<span class="text-emerald-600">sador</span>
                        </span>
                        <span class="block text-[10px] text-slate-400 font-semibold -mt-1 tracking-widest uppercase">
                            Your Trusted Partner for Every Journey
                        </span>
                    </div>
                </div>

                <!-- Search -->
                <div class="flex-1 max-w-xl mx-4 hidden md:block">
                    <div class="relative">
                        <input type="text" id="searchInput" oninput="handleSearch()"
                            placeholder="Cari gadget, fashion, aksesori..."
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-100 hover:bg-slate-200/70 focus:bg-white text-sm rounded-full border border-transparent focus:border-emerald-500 focus:outline-none transition-all duration-200 shadow-inner">

                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <button id="clearSearch" onclick="clearSearch()"
                            class="hidden absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Kanan -->
                <div class="flex items-center gap-2 sm:gap-3">

                    <!-- Wishlist -->
                    <button onclick="toggleWishlistModal()"
                        class="relative p-2.5 text-slate-600 hover:text-red-500 hover:bg-slate-100 rounded-full transition-colors"
                        title="Wishlist">
                        <i class="fa-regular fa-heart text-xl"></i>
                        <span id="wishlistCount"
                            class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">
                            0
                        </span>
                    </button>

                    <!-- Keranjang -->
                    <button
                        onclick="<?php echo $isLoggedIn ? 'toggleCartModal()' : "window.location.href='/views/auth/login.php'"; ?>"
                        class="relative p-2.5 bg-slate-900 text-white hover:bg-emerald-600 rounded-full shadow-md transition-all duration-200 flex items-center justify-center"
                        title="Keranjang">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span id="cartCount"
                            class="absolute -top-1 -right-1 bg-emerald-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">
                            0
                        </span>
                    </button>

                    <div class="h-6 w-[1px] bg-slate-200 mx-1 hidden sm:block"></div>

                    <!-- Akun -->
                    <div class="flex items-center gap-2">
                        <?php if ($isLoggedIn) { ?>
                            <div class="relative" id="userMenuContainer">
                                <button onclick="toggleUserDropdown()"
                                    class="flex items-center gap-2.5 p-1.5 pl-3 rounded-full bg-slate-100 hover:bg-slate-200/80 border border-slate-200 transition-all">
                                    <div
                                        class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">
                                        <?php echo strtoupper(substr($username, 0, 1)); ?>
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold text-slate-700 max-w-[100px] truncate">
                                        <?php echo htmlspecialchars($username); ?>
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-slate-400 text-xs pr-1"></i>
                                </button>

                                <div id="userDropdown"
                                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-slate-100">
                                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Akun
                                            Saya</p>
                                        <p class="text-sm font-bold text-slate-800 truncate">
                                            <?php echo htmlspecialchars($username); ?>
                                        </p>
                                    </div>

                                    <a href="views/profile.php"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        <i class="fa-regular fa-user text-slate-400 w-4"></i><span>Profil Saya</span>
                                    </a>

                                    <a href="views/orders.php"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        <i class="fa-solid fa-box-archive text-slate-400 w-4"></i><span>Pesanan Saya</span>
                                    </a>

                                    <a href="views/rewards/index.php"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        <i class="fa-solid fa-gift text-slate-400 w-4"></i><span>Rewards Card</span>
                                    </a>

                                    <div class="border-t border-slate-100 my-1"></div>

                                    <a href="logout.php"
                                        class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                                        <i class="fa-solid fa-right-from-bracket text-red-500 w-4"></i><span>Keluar
                                            (Logout)</span>
                                    </a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <a href="views/auth/login.php"
                                class="text-xs sm:text-sm font-semibold text-slate-700 hover:text-emerald-600 px-3 py-2 rounded-lg hover:bg-slate-100 flex items-center gap-1.5">
                                <i class="fa-regular fa-user text-sm"></i>
                                <span class="hidden sm:inline">Sign In</span>
                            </a>

                            <a href="views/auth/register.php"
                                class="text-xs sm:text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-3.5 py-2 rounded-xl shadow-md flex items-center gap-1.5">
                                <i class="fa-solid fa-user-plus text-xs"></i>
                                <span>Sign Up</span>
                            </a>
                        <?php } ?>
                    </div>

                </div>
            </div>

            <!-- Search Mobile -->
            <div class="pb-3 md:hidden">
                <div class="relative">
                    <input type="text" id="mobileSearchInput" oninput="handleSearch(true)" placeholder="Cari produk..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-100 text-sm rounded-lg border border-slate-200 focus:outline-none focus:border-emerald-500">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section
        class="relative bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 text-white overflow-hidden py-12 md:py-16">
        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(#22c55e_1px,transparent_1px)] [background-size:16px_16px]">
        </div>
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4 max-w-xl text-center md:text-left">
                <span
                    class="inline-block bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs px-3 py-1 rounded-full font-semibold uppercase">
                    Mega Sale Edition
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-tight">
                    Koleksi Fashion & Lifestyle Masa Kini
                </h1>
                <p class="text-slate-300 text-sm md:text-base">
                    Dapatkan diskon hingga <span class="text-yellow-400 font-bold">50%</span> untuk produk pilihan
                    dengan garansi resmi dan bebas biaya kirim.
                </p>
                <div class="pt-2 flex flex-wrap justify-center md:justify-start gap-3">
                    <a href="#productGrid"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition-all text-sm flex items-center gap-2">
                        Belanja Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <button onclick="applyPromoQuick('AMBASDISKON')"
                        class="bg-white/10 hover:bg-white/20 backdrop-blur text-white font-medium px-5 py-3 rounded-xl border border-white/20 transition-all text-sm">
                        Klaim Kupon 10%
                    </button>
                </div>
            </div>

            <!-- Flash Sale -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl w-full md:w-80 shadow-2xl">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-bold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-yellow-400"></i>
                        Flash Sale Berakhir:
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center" id="countdownTimer">
                    <div class="bg-slate-900/80 rounded-lg p-2 border border-slate-700">
                        <span class="block text-2xl font-bold text-emerald-400" id="hours">08</span>
                        <span class="text-[10px] text-slate-400 uppercase">Jam</span>
                    </div>
                    <div class="bg-slate-900/80 rounded-lg p-2 border border-slate-700">
                        <span class="block text-2xl font-bold text-emerald-400" id="minutes">42</span>
                        <span class="text-[10px] text-slate-400 uppercase">Menit</span>
                    </div>
                    <div class="bg-slate-900/80 rounded-lg p-2 border border-slate-700">
                        <span class="block text-2xl font-bold text-emerald-400" id="seconds">19</span>
                        <span class="text-[10px] text-slate-400 uppercase">Detik</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Katalog Produk Utama -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1 w-full">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none" id="categoryContainer">
            </div>
            <div class="flex items-center justify-end gap-3 self-end md:self-auto min-w-max">
                <label for="sortSelect"
                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Urutkan:</label>
                <select id="sortSelect" onchange="handleSort()"
                    class="bg-white border border-slate-200 rounded-lg text-sm px-3 py-2 font-medium text-slate-700 focus:outline-none focus:border-emerald-500 shadow-sm">
                    <option value="featured">Paling Sesuai</option>
                    <option value="price-low">Harga: Terendah ke Tertinggi</option>
                    <option value="price-high">Harga: Tertinggi ke Terendah</option>
                    <option value="rating">Rating Tertinggi</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-fire text-orange-500"></i>
                <span id="catalogTitle">Semua Produk</span>
            </h2>
            <span class="text-xs text-slate-500 font-medium" id="productCount">Menampilkan 0 produk</span>
        </div>

        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>

        <div id="emptyState" class="hidden text-center py-16">
            <div
                class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                <i class="fa-solid fa-box-open text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Produk Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm max-w-sm mx-auto mb-4">Coba sesuaikan kata kunci pencarian atau pilih
                kategori lain.</p>
            <button onclick="resetFilters()"
                class="bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-emerald-700">
                Reset Filter
            </button>
        </div>
    </main>

    <!-- MODAL INTEGRASI LAYANAN -->
    <div id="layananModal"
        class="fixed inset-0 z-50 invisible transition-all duration-300 flex items-center justify-center p-3 sm:p-6">
        <div id="layananBackdrop" onclick="closeLayananModal()"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity"></div>
        <div id="layananPanel"
            class="relative bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl opacity-0 scale-95 transition-all duration-300 z-10 p-6 sm:p-8">
            <button onclick="closeLayananModal()"
                class="absolute top-5 right-5 bg-slate-100 hover:bg-slate-200 text-slate-600 w-9 h-9 rounded-full flex items-center justify-center z-20 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Tampilan Pusat Bantuan -->
            <div id="viewPusatBantuan" class="hidden space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold mb-2">
                        <i class="fa-solid fa-circle-question"></i> Help Center
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">Pusat Bantuan</h2>
                    <p class="text-xs text-slate-500 mt-1">Cari solusi atau panduan berbelanja kamu di sini.</p>
                </div>

                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="modalHelpSearch" type="text" oninput="filterModalHelp()"
                        placeholder="Cari bantuan, misal promo, keranjang, garansi..."
                        class="w-full pl-11 pr-4 py-3 bg-slate-100 rounded-xl text-sm border border-transparent focus:border-emerald-500 outline-none">
                </div>

                <div id="modalFaqList" class="space-y-3">
                    <div class="faq-item bg-slate-50 border border-slate-200 rounded-xl overflow-hidden"
                        data-search="wishlist simpan favorit">
                        <button type="button" onclick="toggleModalFaq(this)"
                            class="w-full px-4 py-3 flex items-center justify-between text-left font-semibold text-sm text-slate-800">
                            <span>Bagaimana cara memakai Wishlist?</span>
                            <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div class="faq-answer px-4 pb-4 text-xs text-slate-600">
                            Klik ikon hati pada produk untuk menyimpannya ke Wishlist sehingga kamu mudah mencarinya
                            kembali.
                        </div>
                    </div>

                    <div class="faq-item bg-slate-50 border border-slate-200 rounded-xl overflow-hidden"
                        data-search="promo diskon voucher ambasdiskon discount10">
                        <button type="button" onclick="toggleModalFaq(this)"
                            class="w-full px-4 py-3 flex items-center justify-between text-left font-semibold text-sm text-slate-800">
                            <span>Bagaimana cara menggunakan Kode Promo?</span>
                            <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div class="faq-answer px-4 pb-4 text-xs text-slate-600">
                            Masukkan kode promo <b>AMBASDISKON</b> pada kolom promo di keranjang belanja sebelum
                            melanjutkan ke checkout.
                        </div>
                    </div>

                    <div class="faq-item bg-slate-50 border border-slate-200 rounded-xl overflow-hidden"
                        data-search="garansi retur rusak klaim">
                        <button type="button" onclick="toggleModalFaq(this)"
                            class="w-full px-4 py-3 flex items-center justify-between text-left font-semibold text-sm text-slate-800">
                            <span>Bagaimana klaim Garansi produk?</span>
                            <i class="faq-icon fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div class="faq-answer px-4 pb-4 text-xs text-slate-600">
                            Siapkan nomor invoice pesanan dan foto/video bukti kerusakan, lalu ajukan melalui layanan
                            garansi resmi kami.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tampilan Cara Pembelian -->
            <div id="viewCaraPembelian" class="hidden space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold mb-2">
                        <i class="fa-solid fa-book-open"></i> Panduan
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">Cara Pembelian</h2>
                </div>

                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="font-black text-emerald-600 text-lg">01.</span>
                        <h4 class="font-bold text-slate-900">Pilih Produk</h4>
                        <p class="text-xs text-slate-500 mt-1">Cari dan pilih produk pilihanmu di katalog utama.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="font-black text-emerald-600 text-lg">02.</span>
                        <h4 class="font-bold text-slate-900">Tambah Keranjang / Beli</h4>
                        <p class="text-xs text-slate-500 mt-1">Gunakan Beli Sekarang atau masukkan keranjang terlebih
                            dahulu.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="font-black text-emerald-600 text-lg">03.</span>
                        <h4 class="font-bold text-slate-900">Gunakan Promo</h4>
                        <p class="text-xs text-slate-500 mt-1">Masukkan kode promo AMBASDISKON di keranjang belanja.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="font-black text-emerald-600 text-lg">04.</span>
                        <h4 class="font-bold text-slate-900">Checkout & Bayar</h4>
                        <p class="text-xs text-slate-500 mt-1">Isi alamat pengiriman dan tuntaskan pembayaran.</p>
                    </div>
                </div>
            </div>

            <!-- Tampilan Kebijakan Garansi -->
            <div id="viewKebijakanGaransi" class="hidden space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-50 text-purple-600 text-xs font-bold mb-2">
                        <i class="fa-solid fa-shield-halved"></i> Garansi Resmi
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">Kebijakan Garansi</h2>
                </div>

                <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-emerald-900">
                        <h4 class="font-bold mb-1"><i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i>
                            Cakupan Garansi</h4>
                        Garansi berlaku untuk cacat produksi dan malfungsi perangkat yang bukan disebabkan oleh
                        kelalaian pengguna.
                    </div>
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100 text-red-900">
                        <h4 class="font-bold mb-1"><i class="fa-solid fa-circle-xmark text-red-600 mr-1"></i> Tidak
                            Dicakup</h4>
                        Garansi tidak berlaku untuk kerusakan akibat jatuh, terkena cairan secara sengaja, atau
                        modifikasi ilegal.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modals Keranjang, Product, Wishlist, Checkout, Success, Toast -->
    <div id="cartModal" class="fixed inset-0 z-50 invisible transition-all duration-300">
        <div id="cartBackdrop"
            onclick="<?php echo $isLoggedIn ? 'toggleCartModal()' : "window.location.href='/views/auth/login.php'"; ?>"
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs opacity-0 transition-opacity duration-300"></div>
        <div id="cartPanel"
            class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bag-shopping text-emerald-600 text-lg"></i>
                    <h3 class="font-bold text-slate-800"><button onclick="addToCart(1)">Tambah ke Keranjang</button>
                    </h3>
                    <span id="cartHeaderBadge"
                        class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                </div>
                <button onclick="toggleCartModal()" class="text-slate-400 hover:text-slate-600 p-2 rounded-lg"><i
                        class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div id="cartItemsContainer" class="flex-1 overflow-y-auto p-4 space-y-4"></div>
            <div class="p-4 border-t border-slate-100 bg-slate-50 space-y-3">
                <div class="flex gap-2">
                    <input type="text" id="promoInput" placeholder="Kode Promo (mis. AMBASDISKON)"
                        class="flex-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm uppercase focus:outline-none focus:border-emerald-500">
                    <button onclick="applyPromoCode()"
                        class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 rounded-lg">Gunakan</button>
                </div>
                <div id="promoMessage" class="hidden text-xs font-medium"></div>
                <div class="space-y-1.5 text-sm pt-2 border-t border-slate-200">
                    <div class="flex justify-between text-slate-600"><span>Subtotal</span><span id="cartSubtotal">Rp
                            0</span></div>
                    <div class="flex justify-between text-slate-600"><span>Diskon</span><span id="cartDiscount"
                            class="text-emerald-600 font-medium">- Rp 0</span></div>
                    <div class="flex justify-between text-slate-600"><span>Estimasi Ongkir</span><span id="cartShipping"
                            class="text-slate-800 font-medium">Gratis</span></div>
                    <div class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                        <span>Total Bayar</span><span id="cartTotal" class="text-emerald-600">Rp 0</span>
                    </div>
                </div>
                <id="checkoutBtn"
                    onclick="<?php echo $isLoggedIn ? 'openCheckout()' : "window.location.href='/views/auth/login.php'"; ?>"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                    Proses Pembayaran <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
            </div>
        </div>
    </div>

    <!-- Detail Produk Modal -->
    <div id="productModal"
        class="fixed inset-0 z-50 invisible transition-all duration-300 flex items-center justify-center p-4">
        <div id="productModalBackdrop" onclick="closeProductModal()"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity"></div>
        <div id="productModalContent"
            class="relative bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl opacity-0 scale-95 transition-all duration-300 z-10">
            <button onclick="closeProductModal()"
                class="absolute top-4 right-4 bg-slate-100 hover:bg-slate-200 text-slate-600 w-9 h-9 rounded-full flex items-center justify-center z-20">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6" id="productModalBody"></div>
        </div>
    </div>

    <!-- Wishlist Modal -->
    <div id="wishlistModal"
        class="fixed inset-0 z-50 invisible transition-all duration-300 flex items-center justify-center p-4">
        <div id="wishlistBackdrop" onclick="toggleWishlistModal()"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity"></div>
        <div id="wishlistPanel"
            class="relative bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] flex flex-col shadow-2xl opacity-0 scale-95 transition-all duration-300 z-10 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2"><i
                        class="fa-solid fa-heart text-red-500"></i> Wishlist Saya</h3>
                <button onclick="toggleWishlistModal()" class="text-slate-400 hover:text-slate-600 p-2"><i
                        class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div id="wishlistItemsContainer" class="flex-1 overflow-y-auto p-4 space-y-4"></div>
        </div>
    </div>

    <!-- checkout modal -->
    <div id="checkoutModal"
        class="fixed inset-0 z-50 invisible transition-all duration-300 flex items-center justify-center p-4">
        <div id="checkoutBackdrop" onclick="closeCheckout()"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity"></div>
        <div id="checkoutPanel"
            class="relative bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl opacity-0 scale-95 transition-all duration-300 z-10 p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-emerald-600"></i> Pengiriman & Pembayaran
                </h3>
                <button onclick="closeCheckout()" class="text-slate-400 hover:text-slate-600"><i
                        class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="checkoutForm" onsubmit="handlePlaceOrder(event)" class="mt-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap *</label>
                        <input type="text" id="checkoutName" name="name" required placeholder="Ahmad Subagja"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor WhatsApp/HP *</label>
                        <input type="tel" id="checkoutPhone" name="phone" required placeholder="081234567890"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat Lengkap *</label>
                    <textarea id="checkoutAddress" name="address" required rows="2"
                        placeholder="Jl. Sudirman No. 123, Jakarta"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500"></textarea>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2 text-sm">
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Total Biaya Pesanan:</span><span id="checkoutTotal" class="text-emerald-600">Rp 0</span>
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all">
                    Buat Pesanan Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- pesanan berhasil modal -->
    <div id="successModal"
        class="fixed inset-0 z-50 invisible transition-all duration-300 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 text-center shadow-2xl z-10 space-y-4">
            <div
                class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-3xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-800">Pesanan Berhasil!</h3>
            <p class="text-sm text-slate-600">Terima kasih telah berbelanja di Ambassador. Nomor Invoice Anda adalah
                <span id="invoiceNumber" class="font-mono font-bold text-slate-900">#INV-89123</span>.
            </p>
            <button
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl">
                <a
                    href="/webdesign/midtrans/midtrans-php-native/vendor/veritrans/veritrans-php/examples/snap/checkout-process-simple-version.php">Checkout
                    Now</a>
            </button>
            <button onclick="closeSuccessModal()"
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl">
                Kembali ke Beranda
            </button>
        </div>
    </div>

    <!-- toast atau pesan pop up kecil -->
    <div id="toast"
        class="fixed bottom-5 right-5 z-50 hidden toast-slide-in bg-slate-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 border border-slate-700">
        <i id="toastIcon" class="fa-solid fa-circle-info text-emerald-400 text-lg"></i>
        <span id="toastMessage" class="text-sm font-medium">Notifikasi</span>
    </div>

    <!-- footer -->
    <footer class="bg-slate-900 text-slate-400 text-sm mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <span class="text-lg font-bold text-white">Ambassador</span>
                </div>
                <p class="text-xs text-slate-400">
                    Platform belanja online terpercaya dengan garansi kualitas terbaik dan layanan pengiriman super
                    cepat.
                </p>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-3">Layanan Pelanggan</h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="/views/layanan/pusatbantuan.php"
                            onclick="event.preventDefault(); openLayananModal('pusatbantuan');"
                            class="hover:text-white">
                            Pusat Bantuan
                        </a>
                    </li>
                    <li>
                        <a href="/views/layanan/carapembelian.php"
                            onclick="event.preventDefault(); openLayananModal('carapembelian');"
                            class="hover:text-white">
                            Cara Pembelian
                        </a>
                    </li>
                    <li>
                        <a href="/views/layanan/kebijakangaransi.php"
                            onclick="event.preventDefault(); openLayananModal('kebijakangaransi');"
                            class="hover:text-white">
                            Kebijakan Garansi
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-3">Kategori Populer</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#"
                            onclick="if(typeof filterByCategory === 'function') filterByCategory('Fashion'); return false;"
                            class="hover:text-white">Fashion Pria & Wanita</a></li>
                    <li><a href="#"
                            onclick="if(typeof filterByCategory === 'function') filterByCategory('Accessories'); return false;"
                            class="hover:text-white">Aksesoris Premium</a></li>
                    <li><a href="#"
                            onclick="if(typeof filterByCategory === 'function') filterByCategory('Home'); return false;"
                            class="hover:text-white">Perlengkapan Rumah</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-3">Metode Pembayaran</h4>
                <div class="flex flex-wrap gap-2 text-slate-300">
                    <span class="bg-slate-800 px-2 py-1 rounded text-xs border border-slate-700">QRIS</span>
                    <span class="bg-slate-800 px-2 py-1 rounded text-xs border border-slate-700">BCA Transfer</span>
                    <span class="bg-slate-800 px-2 py-1 rounded text-xs border border-slate-700">Mandiri</span>
                    <span class="bg-slate-800 px-2 py-1 rounded text-xs border border-slate-700">COD</span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 py-4 text-center text-xs text-slate-500">
            © 2026 Ambassador Inc. Seluruh hak cipta dilindungi undang-undang.

        </div>
    </footer>

    <!-- data login -->
    <script>
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        const currentUserId = <?php echo $user_id ? json_encode($user_id) : 'null'; ?>;

        document.addEventListener('click', function (e) {
            if (isLoggedIn) {
                return;
            }

            let tombol = e.target.closest('button');
            if (!tombol) {
                return;
            }

            let teks = tombol.innerText.trim().toLowerCase();

            if (teks.includes('keranjang') || teks.includes('beli sekarang')) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                window.location.href = '/views/auth/login.php';
                return;
            }
        }, true);

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function (e) {
            const container = document.getElementById('userMenuContainer');
            const dropdown = document.getElementById('userDropdown');

            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        /* manajemen modal layanan */
        function openLayananModal(type) {
            const modal = document.getElementById('layananModal');
            const backdrop = document.getElementById('layananBackdrop');
            const panel = document.getElementById('layananPanel');

            const viewPusatBantuan = document.getElementById('viewPusatBantuan');
            const viewCaraPembelian = document.getElementById('viewCaraPembelian');
            const viewKebijakanGaransi = document.getElementById('viewKebijakanGaransi');

            viewPusatBantuan.classList.add('hidden');
            viewCaraPembelian.classList.add('hidden');
            viewKebijakanGaransi.classList.add('hidden');

            if (type === 'pusatbantuan') {
                viewPusatBantuan.classList.remove('hidden');
            } else if (type === 'carapembelian') {
                viewCaraPembelian.classList.remove('hidden');
            } else if (type === 'kebijakangaransi') {
                viewKebijakanGaransi.classList.remove('hidden');
            }

            modal.classList.remove('invisible');

            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
            }, 10);
        }

        function closeLayananModal() {
            const modal = document.getElementById('layananModal');
            const backdrop = document.getElementById('layananBackdrop');
            const panel = document.getElementById('layananPanel');

            backdrop.classList.add('opacity-0');
            panel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('invisible');
            }, 300);
        }

        function filterModalHelp() {
            const input = document.getElementById('modalHelpSearch').value.toLowerCase();
            const items = document.querySelectorAll('#modalFaqList .faq-item');

            items.forEach(item => {
                const search = item.getAttribute('data-search').toLowerCase();

                if (search.includes(input) || input === '') {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        function toggleModalFaq(button) {
            const item = button.closest('.faq-item');

            document.querySelectorAll('#modalFaqList .faq-item.active').forEach(activeItem => {
                if (activeItem !== item) {
                    activeItem.classList.remove('active');
                }
            });

            item.classList.toggle('active');
        }

        function applyPromoQuick(code) {
            if (typeof toggleCartModal === 'function') {
                toggleCartModal();

                const promoInput = document.getElementById('promoInput');

                if (promoInput) {
                    promoInput.value = code;

                    if (typeof applyPromoCode === 'function') {
                        applyPromoCode();
                    }
                }
            }
        }

        // Path Midtrans checkout, relatif dari index.php (root).
        // Sesuaikan lagi kalau lokasi foldernya berubah.
        const MIDTRANS_CHECKOUT_PATH = "midtrans/midtrans-php-native/vendor/veritrans/veritrans-php/examples/snap/checkout-process-simple-version.php";

        // Alias, kalau ada bagian lain (mis. js/checkout.js) yang
        // masih memanggil nama fungsi lama ini.
        // Variabel sementara untuk menampung keranjang saat order dibuat
        let pendingCheckoutCart = [];

        // Simpan satu versi fungsi checkoutnow() ini saja di index.php
        // Simpan satu versi fungsi checkoutnow() ini saja di index.php
        function checkoutnow() {
            const modal = document.getElementById("successModal");

            // 1. Ambil data barang dari pendingCheckoutCart (setelah form modal diisi) 
            // ATAU dari cart/localStorage jika dipanggil langsung
            let cartToCheckout = (typeof pendingCheckoutCart !== 'undefined' && pendingCheckoutCart.length > 0)
                ? pendingCheckoutCart
                : (typeof cart !== 'undefined' && cart.length > 0 ? cart : JSON.parse(localStorage.getItem('cart')) || []);

            if (cartToCheckout.length === 0) {
                alert("Keranjang belanja Anda kosong!");
                return;
            }

            // 2. Kirim data keranjang aktual ke session PHP
            fetch('save_cart_session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart: cartToCheckout })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (modal) modal.classList.add("invisible");
                        // Redirect ke Midtrans
                        window.location.href = MIDTRANS_CHECKOUT_PATH;
                    } else {
                        alert("Gagal menyiapkan checkout. Silakan coba lagi.");
                    }
                })
                .catch(err => {
                    console.error('Checkout error:', err);
                    alert("Terjadi kesalahan koneksi. Silakan coba lagi.");
                });
        }

    </script>
    <!-- File JS -->
    <script src="js/data.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/state.js"></script>
    <script src="js/ui.js"></script>
    <script src="js/produk.js"></script>
    <script src="js/wishlist.js"></script>
    <script src="js/checkout.js"></script>
    <script src="js/app.js"></script>

</body>

</html>