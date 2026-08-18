<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        $username = $_SESSION['user'];
    }
}

if (isset($_SESSION['username']) && is_string($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $isLoggedIn = true;
}

if (!is_string($username) || empty(trim($username))) {
    $username = 'Pengguna';
}

// Halaman ini butuh login
if (!$isLoggedIn) {
    header('Location: /views/auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards Card - Ambassador</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../style.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .slot {
            position: relative;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            flex-shrink: 0;
        }

        .slot-filled {
            background: linear-gradient(135deg, #fde68a, #f59e0b 45%, #d97706);
            color: #451a03;
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.4);
            overflow: hidden;
        }

        .slot-filled::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(115deg, transparent 20%, rgba(255, 255, 255, 0.55) 40%, transparent 60%);
            background-size: 250% 100%;
            animation: shine 2.6s ease-in-out infinite;
        }

        @keyframes shine {
            0% {
                background-position: 150% 0;
            }

            100% {
                background-position: -50% 0;
            }
        }

        .slot-empty {
            background: #fff;
            border: 2px dashed #cbd5e1;
            color: #94a3b8;
        }

        .confetti-piece {
            position: absolute;
            top: -10%;
            width: 8px;
            height: 14px;
            opacity: 0.9;
            animation: fall linear forwards;
        }

        @keyframes fall {
            to {
                transform: translateY(110vh) rotate(540deg);
                opacity: 0.2;
            }
        }

        .tab-btn.active {
            background: #059669;
            color: #fff;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .card-shell {
            background: radial-gradient(circle at top left, #0f172a, #064e3b 65%, #022c22);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen">

    <!-- Header -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <a href="../../index.php" class="flex items-center gap-2.5">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                    </div>
                    <span
                        class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                        Ambas<span class="text-emerald-600">sador</span>
                    </span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="../profile.php"
                        class="text-xs sm:text-sm font-semibold text-slate-500 hover:text-emerald-600 flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Profil
                    </a>
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">
                        <?php echo strtoupper(substr($username, 0, 1)); ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-gift text-emerald-600"></i> Rewards Card
            </h1>
            <p class="text-sm text-slate-500 mt-1">Kumpulkan poin dari setiap belanja baju dan tukarkan dengan hadiah
                menarik.</p>
        </div>

        <!-- Kartu Loyalty -->
        <section class="card-shell rounded-3xl p-6 sm:p-8 text-white shadow-2xl relative overflow-hidden">
            <div
                class="absolute inset-0 opacity-10 bg-[radial-gradient(#22c55e_1px,transparent_1px)] [background-size:16px_16px]">
            </div>

            <div class="relative z-10 flex items-start justify-between mb-6">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-gift text-yellow-400"></i>
                    <span class="font-bold">Rewards Card</span>
                </div>
                <button onclick="toggleInfoTooltip()"
                    class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-info"></i>
                </button>
            </div>

            <div id="infoTooltip"
                class="hidden relative z-10 -mt-3 mb-4 bg-white/10 border border-white/20 rounded-xl p-3 text-xs text-slate-100">
                Dapatkan 1 poin setiap belanja Rp500.000. Kumpulkan 10 poin untuk klaim hadiah menarik! Sisa nominal di
                bawah Rp500.000 tidak diakumulasi ke transaksi berikutnya.
            </div>

            <!-- Slots -->
            <div id="slotsRow" class="relative z-10 flex items-center gap-2 sm:gap-3 overflow-x-auto pb-2"></div>

            <div class="relative z-10 mt-4 space-y-2">
                <p class="text-sm font-bold" id="pointsLabel">0 / 10 poin terkumpul</p>
                <p class="text-xs text-slate-300" id="amountLabel">Rp0 dari Rp5.000.000</p>

                <div class="w-full h-2.5 bg-white/10 rounded-full overflow-hidden">
                    <div id="progressBar"
                        class="h-full bg-gradient-to-r from-emerald-400 to-yellow-400 transition-all duration-500"
                        style="width:0%"></div>
                </div>
            </div>

            <div class="relative z-10 mt-5 bg-white/10 border border-white/15 rounded-2xl p-4 flex items-center gap-3">
                <div
                    class="w-11 h-11 rounded-xl bg-yellow-400/20 flex items-center justify-center text-yellow-300 text-lg shrink-0">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-300">Reward saat ini</p>
                    <p class="font-bold text-sm truncate" id="rewardLabel">Memuat...</p>
                </div>
            </div>

            <button id="claimBtn" onclick="openClaimFlow()" disabled
                class="relative z-10 w-full mt-5 bg-slate-500/30 text-slate-300 font-bold py-3 rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2">
                <span id="claimBtnText">Belanja Rp5.000.000 lagi untuk reward!</span>
            </button>
        </section>

        <!-- Mode Demo -->
        <section
            class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center gap-3 justify-between">
            <div class="text-xs text-amber-800">
                <i class="fa-solid fa-flask mr-1"></i>
                <b>Mode Demo:</b> simulasi transaksi fashion yang berhasil dibayar.
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <select id="simAmount" class="text-xs border border-amber-300 rounded-lg px-2 py-2 bg-white">
                    <option value="500000">Rp500.000 (+1 poin)</option>
                    <option value="1000000">Rp1.000.000 (+2 poin)</option>
                    <option value="2000000">Rp2.000.000 (+4 poin)</option>
                    <option value="300000">Rp300.000 (di bawah minimal)</option>
                </select>
                <button onclick="simulatePurchase()"
                    class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-3 py-2 rounded-lg whitespace-nowrap">
                    Simulasikan Pembelian
                </button>
            </div>
        </section>

        <!-- Tabs -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex gap-2 p-3 border-b border-slate-100 overflow-x-auto">
                <button class="tab-btn active px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all"
                    data-tab="active" onclick="switchTab('active')">Kartu Aktif</button>
                <button
                    class="tab-btn px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100"
                    data-tab="points" onclick="switchTab('points')">Riwayat Poin</button>
                <button
                    class="tab-btn px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all text-slate-600 hover:bg-slate-100"
                    data-tab="rewards" onclick="switchTab('rewards')">Riwayat Rewards</button>
            </div>

            <div class="p-5">
                <div id="tabActive">
                    <p class="text-sm text-slate-500" id="activeSummary">Memuat status kartu...</p>
                </div>

                <div id="tabPoints" class="hidden overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr
                                class="text-left text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="py-2 pr-3">Tanggal</th>
                                <th class="py-2 pr-3">No. Order</th>
                                <th class="py-2 pr-3">Nominal</th>
                                <th class="py-2 pr-3">Poin</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody id="pointsHistoryBody">
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">Memuat riwayat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tabRewards" class="hidden overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr
                                class="text-left text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="py-2 pr-3">Tanggal Penuh</th>
                                <th class="py-2 pr-3">Reward</th>
                                <th class="py-2 pr-3">Tanggal Klaim</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody id="rewardsHistoryBody">
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Memuat riwayat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Klaim -->
    <div id="claimModal"
        class="fixed inset-0 z-50 invisible transition-all duration-300 flex items-center justify-center p-4">
        <div id="claimBackdrop" onclick="closeClaimModal()"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs opacity-0 transition-opacity"></div>
        <div id="claimPanel"
            class="relative bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl opacity-0 scale-95 transition-all duration-300 z-10 overflow-hidden">
            <div id="confettiContainer" class="absolute inset-0 pointer-events-none overflow-hidden"></div>

            <div class="relative z-10 space-y-4">
                <div
                    class="w-16 h-16 bg-yellow-100 text-yellow-500 rounded-full flex items-center justify-center mx-auto text-3xl">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900">Selamat! Card Anda Penuh 🎉</h3>
                <p class="text-sm text-slate-600" id="claimRewardDesc">Pilih reward favorit Anda sekarang.</p>

                <div id="claimAddressField" class="hidden text-left">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Alamat Pengiriman *</label>
                    <textarea id="claimAddress" rows="2" placeholder="Jl. Sudirman No. 123, Jakarta"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <div id="claimResultBox"
                    class="hidden bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-sm text-emerald-800 font-mono font-bold">
                </div>

                <button id="claimActionBtn" onclick="submitClaim()"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all">
                    Klaim Reward Sekarang 🎉
                </button>
                <button onclick="closeClaimModal()"
                    class="w-full text-slate-400 text-xs font-semibold py-1">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast"
        class="fixed bottom-5 right-5 z-50 hidden bg-slate-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 border border-slate-700">
        <i id="toastIcon" class="fa-solid fa-circle-info text-emerald-400 text-lg"></i>
        <span id="toastMessage" class="text-sm font-medium">Notifikasi</span>
    </div>

    <script>
        // Endpoint diarahkan ke folder api/
        const API_URL = '../../api/rewards.php';
        let cardData = null;

        function formatRupiah(n) {
            return 'Rp' + Number(n || 0).toLocaleString('id-ID');
        }

        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            document.getElementById('toastMessage').textContent = message;
            icon.className = type === 'error'
                ? 'fa-solid fa-circle-exclamation text-red-400 text-lg'
                : 'fa-solid fa-circle-check text-emerald-400 text-lg';
            toast.classList.remove('hidden');
            clearTimeout(window.__toastTimer);
            window.__toastTimer = setTimeout(() => toast.classList.add('hidden'), 3500);
        }

        function toggleInfoTooltip() {
            document.getElementById('infoTooltip').classList.toggle('hidden');
        }

        async function fetchStatus() {
            try {
                const res = await fetch(`${API_URL}?action=status`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'Gagal memuat status.');
                cardData = json.data;
                renderCard();
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function renderCard() {
            if (!cardData) return;

            const row = document.getElementById('slotsRow');
            row.innerHTML = '';
            for (let i = 1; i <= cardData.points_to_full; i++) {
                const filled = i <= cardData.current_points;
                const el = document.createElement('div');
                el.className = 'slot ' + (filled ? 'slot-filled' : 'slot-empty');
                el.innerHTML = filled ? '<i class="fa-solid fa-star"></i>' : i;
                row.appendChild(el);
            }

            document.getElementById('pointsLabel').textContent = `${cardData.current_points} / ${cardData.points_to_full} poin terkumpul`;
            document.getElementById('amountLabel').textContent = `${formatRupiah(cardData.amount_collected)} dari ${formatRupiah(cardData.amount_target)}`;
            document.getElementById('progressBar').style.width = cardData.progress_percent + '%';
            document.getElementById('rewardLabel').textContent = cardData.reward_label;

            const claimBtn = document.getElementById('claimBtn');
            const claimBtnText = document.getElementById('claimBtnText');

            if (cardData.card_status === 'full') {
                claimBtn.disabled = false;
                claimBtn.className = 'relative z-10 w-full mt-5 bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-bold py-3 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2';
                claimBtnText.textContent = 'Klaim Reward Sekarang 🎉';
            } else {
                const remaining = cardData.amount_target - cardData.amount_collected;
                claimBtn.disabled = true;
                claimBtn.className = 'relative z-10 w-full mt-5 bg-slate-500/30 text-slate-300 font-bold py-3 rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2';
                claimBtnText.textContent = `Belanja ${formatRupiah(remaining)} lagi untuk reward!`;
            }

            document.getElementById('activeSummary').innerHTML = cardData.card_status === 'full'
                ? `Card Anda sudah <b>penuh (10/10)</b> – klaim reward "${cardData.reward_label}" sekarang.`
                : `Card Anda saat ini <b>${cardData.current_points}/10</b>. Total poin sepanjang waktu: <b>${cardData.total_lifetime_points}</b>. Reward menanti: <b>${cardData.reward_label}</b>.`;
        }

        async function simulatePurchase() {
            const amount = document.getElementById('simAmount').value;
            try {
                const res = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=simulate_purchase&amount=${encodeURIComponent(amount)}`
                });
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'Gagal memproses simulasi.');
                cardData = json.data;
                renderCard();

                if (json.points_earned > 0) {
                    showToast(`+${json.points_earned} poin didapat!`);
                } else {
                    showToast(json.message || 'Tidak ada poin didapat dari transaksi ini.', 'error');
                }

                if (cardData.card_status === 'full') {
                    setTimeout(openClaimFlow, 500);
                }

                if (document.getElementById('tabPoints').classList.contains('hidden') === false) {
                    loadHistory();
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        function fireConfetti() {
            const container = document.getElementById('confettiContainer');
            container.innerHTML = '';
            const colors = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#eab308'];
            for (let i = 0; i < 40; i++) {
                const piece = document.createElement('div');
                piece.className = 'confetti-piece';
                piece.style.left = Math.random() * 100 + '%';
                piece.style.background = colors[Math.floor(Math.random() * colors.length)];
                piece.style.animationDuration = (1.8 + Math.random() * 1.4) + 's';
                piece.style.animationDelay = (Math.random() * 0.4) + 's';
                container.appendChild(piece);
            }
        }

        function openClaimFlow() {
            if (!cardData || cardData.card_status !== 'full') return;

            document.getElementById('claimRewardDesc').textContent = cardData.reward_description;
            document.getElementById('claimResultBox').classList.add('hidden');
            document.getElementById('claimActionBtn').classList.remove('hidden');
            document.getElementById('claimActionBtn').disabled = false;
            document.getElementById('claimActionBtn').textContent = 'Klaim Reward Sekarang 🎉';

            const addressField = document.getElementById('claimAddressField');
            addressField.classList.toggle('hidden', cardData.reward_type !== 'physical');

            const modal = document.getElementById('claimModal');
            const backdrop = document.getElementById('claimBackdrop');
            const panel = document.getElementById('claimPanel');
            modal.classList.remove('invisible');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
                fireConfetti();
            }, 10);
        }

        function closeClaimModal() {
            const modal = document.getElementById('claimModal');
            const backdrop = document.getElementById('claimBackdrop');
            const panel = document.getElementById('claimPanel');
            backdrop.classList.add('opacity-0');
            panel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => modal.classList.add('invisible'), 300);
        }

        async function submitClaim() {
            const btn = document.getElementById('claimActionBtn');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            const body = new URLSearchParams();
            body.set('action', 'claim');
            if (cardData.reward_type === 'physical') {
                const address = document.getElementById('claimAddress').value.trim();
                if (!address) {
                    showToast('Alamat pengiriman wajib diisi.', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Klaim Reward Sekarang 🎉';
                    return;
                }
                body.set('shipping_address', address);
            }

            try {
                const res = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: body.toString()
                });
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'Gagal klaim reward.');

                cardData = json.data;
                renderCard();

                const resultBox = document.getElementById('claimResultBox');
                if (json.reward_code) {
                    resultBox.textContent = `Kode Reward: ${json.reward_code}`;
                    resultBox.classList.remove('hidden');
                    navigator.clipboard?.writeText(json.reward_code).catch(() => { });
                    showToast('Reward diklaim! Kode disalin ke clipboard.');
                } else {
                    showToast('Reward berhasil diklaim!');
                }

                btn.classList.add('hidden');
                loadHistory();
            } catch (err) {
                showToast(err.message, 'error');
                btn.disabled = false;
                btn.textContent = 'Klaim Reward Sekarang 🎉';
            }
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.tab === tab);
                if (b.dataset.tab !== tab) {
                    b.classList.add('text-slate-600');
                } else {
                    b.classList.remove('text-slate-600');
                }
            });
            document.getElementById('tabActive').classList.toggle('hidden', tab !== 'active');
            document.getElementById('tabPoints').classList.toggle('hidden', tab !== 'points');
            document.getElementById('tabRewards').classList.toggle('hidden', tab !== 'rewards');

            if (tab === 'points' || tab === 'rewards') {
                loadHistory();
            }
        }

        async function loadHistory() {
            try {
                const res = await fetch(`${API_URL}?action=history`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message || 'Gagal memuat riwayat.');

                const pointsBody = document.getElementById('pointsHistoryBody');
                if (json.data.points_history.length === 0) {
                    pointsBody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-400">Belum ada transaksi.</td></tr>';
                } else {
                    pointsBody.innerHTML = json.data.points_history.map(row => `
                        <tr class="border-b border-slate-50">
                            <td class="py-2 pr-3 whitespace-nowrap">${row.date}</td>
                            <td class="py-2 pr-3">${row.order_no}</td>
                            <td class="py-2 pr-3 whitespace-nowrap">${formatRupiah(row.amount)}</td>
                            <td class="py-2 pr-3 font-bold text-emerald-600">+${row.points}</td>
                            <td class="py-2"><span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded-full">${row.status}</span></td>
                        </tr>
                    `).join('');
                }

                const rewardsBody = document.getElementById('rewardsHistoryBody');
                if (json.data.rewards_history.length === 0) {
                    rewardsBody.innerHTML = '<tr><td colspan="4" class="py-6 text-center text-slate-400">Belum ada reward yang diklaim.</td></tr>';
                } else {
                    rewardsBody.innerHTML = json.data.rewards_history.map(row => `
                        <tr class="border-b border-slate-50">
                            <td class="py-2 pr-3 whitespace-nowrap">${row.full_date}</td>
                            <td class="py-2 pr-3">${row.reward}</td>
                            <td class="py-2 pr-3 whitespace-nowrap">${row.claimed_at}</td>
                            <td class="py-2"><span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-1 rounded-full">${row.status}</span></td>
                        </tr>
                    `).join('');
                }
            } catch (err) {
                showToast(err.message, 'error');
            }
        }

        // Inisialisasi awal
        fetchStatus();
    </script>
</body>

</html>