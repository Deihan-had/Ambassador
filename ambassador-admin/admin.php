<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/config.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan Password wajib diisi.';
    } elseif (!isset($conn) || !($conn instanceof mysqli)) {
        $error = 'Koneksi database tidak tersedia.';
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id_users, username, password, role
             FROM users
             WHERE username = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $error = 'Gagal memproses login: ' . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                's',
                $username
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $user = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if (!$user) {

                $error = 'Username atau Password salah.';

            } else {

                $storedPassword = trim((string)($user['password'] ?? ''));

                /*
                 * CEK PASSWORD
                 *
                 * Bisa membaca:
                 * 1. password_hash() / bcrypt
                 * 2. password biasa dari database lama
                 */

                $passwordValid = false;

                if ($storedPassword !== '') {

                    // Password hash PHP
                    if (
                        password_get_info($storedPassword)['algo'] !== 0
                    ) {

                        $passwordValid = password_verify(
                            $password,
                            $storedPassword
                        );

                    } else {

                        // Password biasa
                        $passwordValid = hash_equals(
                            $storedPassword,
                            $password
                        );
                    }
                }

                /*
                 * CEK ROLE
                 */

                $role = strtolower(
                    trim((string)($user['role'] ?? ''))
                );

                $isAdmin = ($role === 'admin');

                if (!$passwordValid) {

                    $error = 'Username atau Password salah.';

                } elseif (!$isAdmin) {

                    $error = 'Akses ditolak. Akun ini bukan Administrator.';

                } else {

                    /*
                     * LOGIN BERHASIL
                     */

                    session_regenerate_id(true);

                    $_SESSION['is_admin'] = true;
                    $_SESSION['id_users'] = $user['id_users'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = 'admin';

                    header('Location: index.php');
                    exit;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login Admin - Ambassador</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #071b16;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 430px;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,.35);
        }

        .logo {
            width: 58px;
            height: 58px;
            margin: 0 auto 15px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #079455;
            color: white;
            font-size: 25px;
            font-weight: bold;
        }

        h1 {
            margin: 0;
            text-align: center;
            color: #14201c;
            font-size: 28px;
        }

        .subtitle {
            text-align: center;
            color: #718078;
            font-size: 14px;
            margin: 8px 0 25px;
        }

        .error {
            background: #fff0f0;
            border: 1px solid #ffd0d0;
            color: #c62828;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 14px;
            color: #26332e;
        }

        input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d8dfdc;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            margin-bottom: 17px;
        }

        input:focus {
            border-color: #079455;
        }

        button {
            width: 100%;
            border: 0;
            padding: 14px;
            border-radius: 10px;
            background: #079455;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #067c47;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #66736d;
            font-size: 13px;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="login-box">

    <div class="logo">A</div>

    <h1>Ambassador Admin</h1>

    <div class="subtitle">
        Masuk untuk mengelola toko.
    </div>

    <?php if ($error !== ''): ?>

        <div class="error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label for="username">
            Username
        </label>

        <input
            id="username"
            type="text"
            name="username"
            value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            autocomplete="username"
            required
        >

        <label for="password">
            Password
        </label>

        <input
            id="password"
            type="password"
            name="password"
            autocomplete="current-password"
            required
        >

        <button type="submit">
            Masuk Dashboard
        </button>

    </form>

    <a class="back" href="../index.php">
        ← Kembali ke toko
    </a>

</div>

</body>

</html>