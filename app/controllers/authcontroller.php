<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* BASE URL */
$baseUrl = '/webdesign';

/* USER MODEL */
$userModelPath = __DIR__ . '/../models/User.php';
if (!file_exists($userModelPath)) {
    $userModelPath = __DIR__ . '/../Models/User.php';
}
if (!file_exists($userModelPath)) {
    die('User.php tidak ditemukan.');
}
require_once $userModelPath;

/* DATABASE */
require_once __DIR__ . '/../../config/database.php';

class AuthController
{
    private $userModel;
    private $conn;
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->userModel = new User();
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */
    private function redirect($page)
    {
        $page = '/' . ltrim($page, '/');
        header('Location: ' . $this->baseUrl . $page);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/views/auth/register.php');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $email === '' || $password === '') {
            $_SESSION['error'] = 'Semua kolom harus diisi!';
            $this->redirect('/views/auth/register.php');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid!';
            $this->redirect('/views/auth/register.php');
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter!';
            $this->redirect('/views/auth/register.php');
        }

        $idUsers = 'USR-' . time() . '-' . rand(100, 999);

        $sukses = $this->userModel->register($idUsers, $username, $email, $password);

        if ($sukses) {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan masuk menggunakan email Anda.';
            $this->redirect('/views/auth/login.php');
        }

        $_SESSION['error'] = 'Registrasi gagal. Username atau email mungkin sudah digunakan.';
        $this->redirect('/views/auth/register.php');
    }

    /*
    |--------------------------------------------------------------------------
    | KIRIM OTP
    |--------------------------------------------------------------------------
    */
    public function handleSendOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/views/auth/login.php');
        }

        $email = trim($_POST['email'] ?? '');

        if ($email === '') {
            $_SESSION['error'] = 'Email wajib diisi!';
            $this->redirect('/views/auth/login.php');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid!';
            $this->redirect('/views/auth/login.php');
        }

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Email belum terdaftar. Silakan daftar terlebih dahulu.';
            $this->redirect('/views/auth/login.php');
        }

        $idUsers = trim($user['id_users'] ?? '');

        if ($idUsers === '') {
            $_SESSION['error'] = 'ID pengguna tidak ditemukan.';
            $this->redirect('/views/auth/login.php');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK USER DATABASE
        |--------------------------------------------------------------------------
        */
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT id_users, email, username, role FROM users WHERE id_users = ? LIMIT 1"
        );

        if (!$stmt) {
            $_SESSION['error'] = 'Gagal memeriksa data pengguna: ' . mysqli_error($this->conn);
            $this->redirect('/views/auth/login.php');
        }

        mysqli_stmt_bind_param($stmt, 's', $idUsers);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userDatabase = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$userDatabase) {
            $_SESSION['error'] = 'User tidak ditemukan di database.';
            $this->redirect('/views/auth/login.php');
        }

        $idUsers = $userDatabase['id_users'];
        $email = $userDatabase['email'];

        /*
        |--------------------------------------------------------------------------
        | BUAT OTP
        |--------------------------------------------------------------------------
        */
        $otp = (string) random_int(100000, 999999);
        $expiredAt = date('Y-m-d H:i:s', time() + 300);

        /*
        |--------------------------------------------------------------------------
        | HAPUS OTP LAMA
        |--------------------------------------------------------------------------
        */
        $delete = mysqli_prepare($this->conn, "DELETE FROM login_otp WHERE id_users = ?");

        if ($delete) {
            mysqli_stmt_bind_param($delete, 's', $idUsers);
            mysqli_stmt_execute($delete);
            mysqli_stmt_close($delete);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN OTP
        |--------------------------------------------------------------------------
        */
        $insert = mysqli_prepare(
            $this->conn,
            "INSERT INTO login_otp (id_users, email, otp, expired_at) VALUES (?, ?, ?, ?)"
        );

        if (!$insert) {
            $_SESSION['error'] = 'Gagal menyiapkan OTP: ' . mysqli_error($this->conn);
            $this->redirect('/views/auth/login.php');
        }

        mysqli_stmt_bind_param($insert, 'ssss', $idUsers, $email, $otp, $expiredAt);

        if (!mysqli_stmt_execute($insert)) {
            $error = mysqli_stmt_error($insert);
            mysqli_stmt_close($insert);
            $_SESSION['error'] = 'Gagal menyimpan OTP: ' . $error;
            $this->redirect('/views/auth/login.php');
        }

        mysqli_stmt_close($insert);

        /*
        |--------------------------------------------------------------------------
        | SESSION OTP
        |--------------------------------------------------------------------------
        */
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_user_id'] = $idUsers;
        
        /*
        |--------------------------------------------------------------------------
        | DEBUG OTP
        |--------------------------------------------------------------------------
        */
        $_SESSION['otp_debug'] = $otp;

        /*
        |--------------------------------------------------------------------------
        | KE HALAMAN VERIFY OTP
        |--------------------------------------------------------------------------
        */
        $this->redirect('/views/auth/verify_otp.php');
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI OTP
    |--------------------------------------------------------------------------
    */
    public function handleVerifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/views/auth/login.php');
        }

        $otpInput = trim($_POST['otp'] ?? '');
        $email = $_SESSION['otp_email'] ?? '';
        $idUsers = $_SESSION['otp_user_id'] ?? '';

        if ($email === '' || $idUsers === '') {
            $_SESSION['error'] = 'Sesi OTP sudah tidak tersedia. Silakan minta OTP baru.';
            $this->redirect('/views/auth/login.php');
        }

        if ($otpInput === '') {
            $_SESSION['error'] = 'Kode OTP wajib diisi!';
            $this->redirect('/views/auth/verify_otp.php');
        }

        if (!ctype_digit($otpInput) || strlen($otpInput) !== 6) {
            $_SESSION['error'] = 'Kode OTP harus terdiri dari 6 angka.';
            $this->redirect('/views/auth/verify_otp.php');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL OTP
        |--------------------------------------------------------------------------
        */
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT * FROM login_otp WHERE id_users = ? AND email = ? ORDER BY id DESC LIMIT 1"
        );

        if (!$stmt) {
            $_SESSION['error'] = 'Terjadi kesalahan database.';
            $this->redirect('/views/auth/login.php');
        }

        mysqli_stmt_bind_param($stmt, 'ss', $idUsers, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $otpData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$otpData) {
            $_SESSION['error'] = 'OTP tidak ditemukan. Silakan minta OTP baru.';
            $this->redirect('/views/auth/login.php');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK EXPIRED
        |--------------------------------------------------------------------------
        */
        if (strtotime($otpData['expired_at']) < time()) {
            $this->deleteOtp($idUsers);
            unset($_SESSION['otp_email'], $_SESSION['otp_user_id'], $_SESSION['otp_debug']);
            $_SESSION['error'] = 'Kode OTP sudah expired. Silakan minta OTP baru.';
            $this->redirect('/views/auth/login.php');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK OTP
        |--------------------------------------------------------------------------
        */
        if (!hash_equals((string) $otpData['otp'], (string) $otpInput)) {
            $_SESSION['error'] = 'Kode OTP salah. Silakan coba lagi.';
            $this->redirect('/views/auth/verify_otp.php');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL USER
        |--------------------------------------------------------------------------
        */
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Data pengguna tidak ditemukan.';
            $this->redirect('/views/auth/login.php');
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN SESSION
        |--------------------------------------------------------------------------
        */
        session_regenerate_id(true);

        $_SESSION['id_users'] = $user['id_users'];
        $_SESSION['username'] = $user['username'] ?? '';
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'] ?? 'user';

        $_SESSION['user'] = [
            'id_users' => $user['id_users'],
            'username' => $user['username'] ?? '',
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user'
        ];

        /*
        |--------------------------------------------------------------------------
        | HAPUS OTP
        |--------------------------------------------------------------------------
        */
        $this->deleteOtp($user['id_users']);
        unset($_SESSION['otp_email'], $_SESSION['otp_user_id'], $_SESSION['otp_debug']);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT ROLE
        |--------------------------------------------------------------------------
        */
        if (($_SESSION['role'] ?? 'user') === 'admin') {
            $_SESSION['success'] = 'Selamat datang kembali, Admin!';
            $this->redirect('/views/admin/dashboard.php');
        }

        $_SESSION['success'] = 'Login berhasil! Selamat berbelanja.';
        $this->redirect('/index.php');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS OTP
    |--------------------------------------------------------------------------
    */
    private function deleteOtp($idUsers)
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM login_otp WHERE id_users = ?");

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, 's', $idUsers);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function handleLogout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        session_start();

        $_SESSION['success'] = 'Anda telah berhasil keluar.';
        $this->redirect('/views/auth/login.php');
    }

    /*
    |--------------------------------------------------------------------------
    | GOOGLE LOGIN
    |--------------------------------------------------------------------------
    */
    public function handleGoogleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/views/auth/login.php');
        }

        $idToken = $_POST['credential'] ?? '';

        if ($idToken === '') {
            $_SESSION['error'] = 'Token autentikasi Google tidak ditemukan!';
            $this->redirect('/views/auth/login.php');
        }

        $verifyUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
        $response = @file_get_contents($verifyUrl);

        if ($response === false) {
            $_SESSION['error'] = 'Gagal memverifikasi akun Google.';
            $this->redirect('/views/auth/login.php');
        }

        $googleUserData = json_decode($response, true);

        if (!isset($googleUserData['sub']) || !isset($googleUserData['email'])) {
            $_SESSION['error'] = 'Data akun Google tidak valid.';
            $this->redirect('/views/auth/login.php');
        }

        $googleId = $googleUserData['sub'];
        $email = $googleUserData['email'];
        $name = $googleUserData['name'] ?? explode('@', $email)[0];

        $user = $this->userModel->findOrCreateGoogleUser($googleId, $name, $email);

        if (!$user) {
            $_SESSION['error'] = 'Autentikasi Google gagal.';
            $this->redirect('/views/auth/login.php');
        }

        $role = $user['role'] ?? 'user';

        session_regenerate_id(true);

        $_SESSION['id_users'] = $user['id_users'];
        $_SESSION['username'] = $user['username'] ?? '';
        $_SESSION['email'] = $user['email'] ?? $email;
        $_SESSION['role'] = $role;

        $_SESSION['user'] = [
            'id_users' => $user['id_users'],
            'username' => $user['username'] ?? '',
            'email' => $user['email'] ?? $email,
            'role' => $role
        ];

        if ($role === 'admin') {
            $_SESSION['success'] = 'Berhasil masuk via Google! Selamat datang, Admin.';
            $this->redirect('/views/admin/dashboard.php');
        }

        $_SESSION['success'] = 'Berhasil masuk dengan akun Google!';
        $this->redirect('/index.php');
    }
}

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/
$action = $_GET['action'] ?? '';
$controller = new AuthController($baseUrl);

switch ($action) {
    case 'register':
        $controller->handleRegister();
        break;

    case 'send_otp':
    case 'login':
        $controller->handleSendOtp();
        break;

    case 'verify_otp':
        $controller->handleVerifyOtp();
        break;

    case 'logout':
        $controller->handleLogout();
        break;

    case 'google_login':
        $controller->handleGoogleLogin();
        break;

    default:
        header('Location: ' . $baseUrl . '/views/auth/login.php');
        exit;
}