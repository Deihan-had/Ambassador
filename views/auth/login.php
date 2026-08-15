<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// TODO: ganti ini pake google client id punya sendiri
$googleClientID = "YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com";
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
    <!-- Google Identity Services Library -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
            <a href="../../index.php" class="inline-flex items-center gap-2 mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">
                    Ambas<span class="text-emerald-600">sador</span>
                </span>
            </a>
            <h2 class="text-xl font-bold text-slate-800">Selamat Datang Kembali</h2>
            <p class="text-slate-500 text-xs mt-1">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Alert Pesan Error -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-xs px-4 py-3 rounded-xl flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                <span><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Login Manual -->
        <form action="../../app/controllers/AuthController.php?action=login" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">Username / Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-sm"></i>
                    </div>
                    <input type="text" id="username" name="username" required 
                        placeholder="Masukkan username Anda"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 text-slate-800">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input type="password" id="password" name="password" required 
                        placeholder="Masukkan password Anda"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 text-slate-800">
                </div>
            </div>

            <button type="submit" 
                class="w-full mt-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-right-to-bracket text-xs"></i>
                Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="relative my-5 flex items-center justify-center">
            <div class="border-t border-slate-200 w-full"></div>
            <span class="bg-white px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider absolute">atau</span>
        </div>

        <!-- Tombol Google Login Custom -->
        <button type="button" onclick="triggerGoogleLogin()" 
            class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold py-2.5 px-4 rounded-xl shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-3 text-sm">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Masuk dengan Google</span>
        </button>

        <!-- Hidden Form untuk mengirimkan Token Google ke Backend PHP -->
        <form id="googleAuthForm" action="../../app/controllers/AuthController.php?action=google_login" method="POST" class="hidden">
            <input type="hidden" name="credential" id="googleCredential">
        </form>

        <!-- Footer Card -->
        <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Belum punya akun? 
            <a href="register.php" class="font-bold text-emerald-600 hover:text-emerald-700 transition-colors ml-1">
                Daftar di sini
            </a>
        </div>
    </div>

    <script>
        window.onload = function () {
            if (typeof google !== 'undefined') {
                google.accounts.id.initialize({
                    client_id: "<?php echo $googleClientID; ?>",
                    callback: handleCredentialResponse,
                    auto_select: false
                });
            }
        };

        function triggerGoogleLogin() {
            if (typeof google !== 'undefined') {
                google.accounts.id.prompt((notification) => {
                    if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                        const oauth2Endpoint = 'https://accounts.google.com/o/oauth2/v2/auth';
                        const form = document.createElement('form');
                        form.setAttribute('method', 'GET');
                        form.setAttribute('action', oauth2Endpoint);

                        // PERBAIKAN: Mengambil URL path secara otomatis termasuk nama folder project di localhost
                        const currentPath = window.location.pathname; // contoh: /ambassador/views/auth/login.php
                        const projectFolder = currentPath.substring(0, currentPath.indexOf('/views/')); 
                        const redirectUri = window.location.origin + projectFolder + '/app/controllers/AuthController.php?action=google_login';

                        const params = {
                            'client_id': '<?php echo $googleClientID; ?>',
                            'redirect_uri': redirectUri,
                            'response_type': 'code',
                            'scope': 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
                            'include_granted_scopes': 'true',
                            'prompt': 'select_account'
                        };

                        for (var p in params) {
                            var input = document.createElement('input');
                            input.setAttribute('type', 'hidden');
                            input.setAttribute('name', p);
                            input.setAttribute('value', params[p]);
                            form.appendChild(input);
                        }
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            } else {
                alert('Gagal memuat library Google. Periksa koneksi internet Anda.');
            }
        }

        function handleCredentialResponse(response) {
            document.getElementById('googleCredential').value = response.credential;
            document.getElementById('googleAuthForm').submit();
        }
    </script>
</body>
</html>