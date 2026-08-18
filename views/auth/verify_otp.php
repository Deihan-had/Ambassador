<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_SESSION['otp_email'] ?? '';

if (empty($email)) {
    header("Location: login.php");
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Verifikasi OTP - Ambassador</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .otp-input {
            letter-spacing: 8px;
            text-align: center;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-emerald-950 via-slate-900 to-emerald-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Background -->
    <div
        class="absolute inset-0 opacity-10 bg-[radial-gradient(#22c55e_1px,transparent_1px)] [background-size:20px_20px]"
    ></div>

    <!-- Card -->
    <div
        class="relative z-10 w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 p-8"
    >

        <!-- Logo -->
        <div class="text-center mb-6">
            <a
                href="../../index.php"
                class="inline-flex items-center gap-2 mb-3"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30"
                >
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                </div>

                <span
                    class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent"
                >
                    Ambas<span class="text-emerald-600">sador</span>
                </span>
            </a>

            <h2 class="text-xl font-bold text-slate-800">
                Verifikasi OTP
            </h2>

            <p class="text-slate-500 text-xs mt-1">
                Masukkan kode OTP yang dikirim ke email Anda
            </p>
        </div>

        <!-- Error -->
        <?php if (!empty($error)): ?>
            <div
                class="mb-5 bg-red-50 border border-red-200 text-red-600 text-xs px-4 py-3 rounded-xl flex items-center gap-2.5"
            >
                <i class="fa-solid fa-circle-exclamation text-base"></i>

                <span>
                    <?php echo htmlspecialchars($error); ?>
                </span>
            </div>
        <?php endif; ?>

        <!-- Email -->
        <div class="mb-5 text-center">
            <p class="text-xs text-slate-500">
                Kode dikirim ke
            </p>

            <p class="text-sm font-semibold text-slate-700 mt-1">
                <?php echo htmlspecialchars($email); ?>
            </p>
        </div>

        <!-- Form OTP -->
        <form
            action="../../app/controllers/AuthController.php?action=verify_otp"
            method="POST"
            class="space-y-5"
        >
            <div>
                <label
                    for="otp"
                    class="block text-xs font-semibold text-slate-700 mb-1.5"
                >
                    Kode OTP
                </label>

                <input
                    type="text"
                    id="otp"
                    name="otp"
                    maxlength="6"
                    minlength="6"
                    pattern="[0-9]{6}"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    required
                    autofocus
                    placeholder="000000"
                    class="otp-input w-full py-3 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold focus:bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 text-slate-800"
                >
            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 transition-all duration-200 flex items-center justify-center gap-2 text-sm"
            >
                <i class="fa-solid fa-check text-xs"></i>
                Verifikasi OTP
            </button>
        </form>

        <!-- Testing localhost -->
        <?php if (isset($_SESSION['otp_debug'])): ?>
            <div
                class="mt-4 bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-center"
            >
                <p class="text-[11px] text-yellow-700">
                    MODE TESTING LOCALHOST
                </p>

                <p class="text-lg font-bold tracking-widest text-yellow-800 mt-1">
                    <?php echo htmlspecialchars($_SESSION['otp_debug']); ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Kembali -->
        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <a
                href="login.php"
                class="text-xs font-semibold text-emerald-600 hover:text-emerald-700"
            >
                <i class="fa-solid fa-arrow-left mr-1"></i>
                Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>