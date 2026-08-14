<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/User.php';

class AuthController {

    var $userModel;

    function __construct() {
        $this->userModel = new User();
    }

    function handleRegister() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if ($username == "" || $password == "") {
                $_SESSION['error'] = "Semua kolom harus diisi!";
                header("Location: ../../views/auth/register.php");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Password minimal harus 6 karakter!";
                header("Location: ../../views/auth/register.php");
                exit;
            }

            // bikin id user asal-asalan pake waktu + random
            $idUsers = 'USR-' . time() . '-' . rand(100, 999);

            $sukses = $this->userModel->register($idUsers, $username, $password);

            if ($sukses) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: ../../views/auth/login.php");
                exit;
            } else {
                $_SESSION['error'] = "Registrasi gagal, username mungkin sudah dipakai orang lain.";
                header("Location: ../../views/auth/register.php");
                exit;
            }
        }
    }

    function handleLogin() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if ($username == "" || $password == "") {
                $_SESSION['error'] = "Semua kolom wajib diisi!";
                header("Location: ../../views/auth/login.php");
                exit;
            }

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user'] = array(
                    'id_users' => $user['id_users'],
                    'username' => $user['username'],
                    'role'     => $user['role']
                );

                if ($user['role'] == 'admin') {
                    header("Location: ../../views/admin/dashboard.php");
                } else {
                    header("Location: ../../index.php");
                }
                exit;

            } else {
                $_SESSION['error'] = "Username atau password salah!";
                header("Location: ../../views/auth/login.php");
                exit;
            }
        }
    }

    function handleLogout() {
        session_unset();
        session_destroy();
        header("Location: ../../views/auth/login.php");
        exit;
    }

    function handleGoogleLogin() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $idToken = isset($_POST['credential']) ? $_POST['credential'] : '';

            if ($idToken == "") {
                $_SESSION['error'] = "Token autentikasi Google tidak ditemukan!";
                header("Location: ../../views/auth/register.php");
                exit;
            }

            // cek token nya langsung ke google
            $verifyUrl = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken);
            $response = @file_get_contents($verifyUrl);

            if ($response == false) {
                $_SESSION['error'] = "Gagal memverifikasi token dengan server Google.";
                header("Location: ../../views/auth/register.php");
                exit;
            }

            $googleUserData = json_decode($response, true);

            if (isset($googleUserData['sub']) && isset($googleUserData['email'])) {

                $googleId = $googleUserData['sub'];
                $email = $googleUserData['email'];
                $name = isset($googleUserData['name']) ? $googleUserData['name'] : explode('@', $email)[0];

                $user = $this->userModel->findOrCreateGoogleUser($googleId, $name, $email);

                if ($user) {
                    $_SESSION['user'] = array(
                        'id_users' => $user['id_users'],
                        'username' => $user['username'],
                        'role'     => isset($user['role']) ? $user['role'] : 'user'
                    );

                    $_SESSION['success'] = "Berhasil masuk dengan akun Google!";
                    header("Location: ../../index.php");
                    exit;
                }
            }

            $_SESSION['error'] = "Autentikasi Google gagal, silakan coba lagi.";
            header("Location: ../../views/auth/register.php");
            exit;
        }
    }
}

if (isset($_GET['action'])) {

    $controller = new AuthController();

    if ($_GET['action'] == 'register') {
        $controller->handleRegister();
    } elseif ($_GET['action'] == 'login') {
        $controller->handleLogin();
    } elseif ($_GET['action'] == 'logout') {
        $controller->handleLogout();
    } elseif ($_GET['action'] == 'google_login') {
        $controller->handleGoogleLogin();
    } else {
        header("Location: ../../index.php");
        exit;
    }
}
?>