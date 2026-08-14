<?php
// buka session dulu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Ambassador E-Commerce</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-950 via-slate-900 to-emerald-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Ornamen Background Grid -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#22c55e_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <!-- Container Card Login -->
    <div class="relative z-10 w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 p-8">
        
        <!-- Header / Logo Brand -->
        <div class="text-center mb-6">
            <a href="index.php" class="inline-flex items-center gap-2 mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                    Ambas<span class="text-emerald-600">sador</span>
                </span>
            </a>
            <h2 class="text-xl font-bold text-slate-800">Selamat Datang Kembali!</h2>
            <p class="text-slate-500 text-xs mt-1">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Alert Notifikasi Error -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-xs px-4 py-3 rounded-xl flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                <span><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- Alert Notifikasi Sukses (misal setelah berhasil register) -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs px-4 py-3 rounded-xl flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-base shrink-0"></i>
                <span><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form action="../../index.php?action=login" method="POST" class="space-y-4">
            
            <!-- Input Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Username
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-sm"></i>
                    </div>
                    <input type="text" id="username" name="username" required autocomplete="off"
                        placeholder="Masukkan username Anda"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 text-slate-800">
                </div>
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input type="password" id="password" name="password" required 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 text-slate-800">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" 
                class="w-full mt-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-right-to-bracket text-xs"></i>
                Masuk / Sign In
            </button>
            <button type="submit" 
                class="w-full mt-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                <a href="/index.php">Kembali Belanja</a>
            </button>
        </form>

        <!-- Footer Card / Link ke Register -->
        <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Belum punya akun? 
            <a href="register.php" class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors ml-1">
                Sign up di sini
            </a>
        </div>
    </div>

    <!-- Script Intip Password -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>