<?php
// Tampilkan error jika ada kesalahan script saat debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan Session Aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Deteksi Base URL secara otomatis (Menangani subfolder seperti /ambassador)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = preg_replace('#/app/controllers.*$#i', '', $scriptDir);
if ($baseUrl === '/' || $baseUrl === '\\') {
    $baseUrl = '';
}

// Cek keberadaan file User.php (Sensitif huruf besar/kecil)
$userModelPath = __DIR__ . '/../models/User.php';
if (!file_exists($userModelPath)) {
    $userModelPath = __DIR__ . '/../Models/User.php';
}

if (file_exists($userModelPath)) {
    require_once $userModelPath;
} else {
    die("<b>Error:</b> Berkas <code>User.php</code> tidak ditemukan di folder <code>app/models/</code> atau <code>app/Models/</code>.");
}

class AuthController {

    private $userModel;
    private $baseUrl;

    public function __construct($baseUrl) {
        $this->userModel = new User();
        $this->baseUrl = $baseUrl;
    }

    /**
     * Memproses Registrasi Akun Baru
     */
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Semua kolom harus diisi!";
                header("Location: " . $this->baseUrl . "/views/auth/register.php");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Password minimal harus 6 karakter!";
                header("Location: " . $this->baseUrl . "/views/auth/register.php");
                exit;
            }

            // Generate ID User unik
            $idUsers = 'USR-' . time() . '-' . rand(100, 999);

            $sukses = $this->userModel->register($idUsers, $username, $password);

            if ($sukses) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan masuk.";
                header("Location: " . $this->baseUrl . "/views/auth/login.php");
                exit;
            } else {
                $_SESSION['error'] = "Registrasi gagal, username mungkin sudah dipakai orang lain.";
                header("Location: " . $this->baseUrl . "/views/auth/register.php");
                exit;
            }
        }
    }

    /**
     * Memproses Login User Form Standard
     */
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Semua kolom wajib diisi!";
                header("Location: " . $this->baseUrl . "/views/auth/login.php");
                exit;
            }

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {

                $role = $user['role'] ?? 'user';

                // Simpan variabel session
                $_SESSION['id_users'] = $user['id_users'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $role;

                $_SESSION['user'] = [
                    'id_users' => $user['id_users'],
                    'username' => $user['username'],
                    'role'     => $role
                ];

                // Redirect berdasarkan Role
                if ($role === 'admin') {
                    $_SESSION['success'] = "Selamat datang kembali, Admin!";
                    header("Location: " . $this->baseUrl . "/views/admin/dashboard.php");
                } else {
                    $_SESSION['success'] = "Login berhasil! Selamat berbelanja.";
                    header("Location: " . $this->baseUrl . "/index.php");
                }
                exit;

            } else {
                $_SESSION['error'] = "Username atau password salah!";
                header("Location: " . $this->baseUrl . "/views/auth/login.php");
                exit;
            }
        }
    }

    /**
     * Memproses Logout / Keluar Sesi
     */
    public function handleLogout() {
        // Hapus semua variabel session
        $_SESSION = array();

        // Hapus cookie session jika ada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        // Destroy session lalu mulai session baru untuk menampung pesan logout
        session_destroy();
        session_start();
        $_SESSION['success'] = "Anda telah berhasil keluar.";

        // DIPERBAIKI: Menambahkan slash '/' sebelum 'views'
        header("Location: " . $this->baseUrl . "/views/auth/login.php");
        exit;
    }

    /**
     * Memproses Login Via Google OAuth
     */
    public function handleGoogleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idToken = $_POST['credential'] ?? '';

            if (empty($idToken)) {
                $_SESSION['error'] = "Token autentikasi Google tidak ditemukan!";
                header("Location: " . $this->baseUrl . "/views/auth/login.php");
                exit;
            }

            $verifyUrl = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken);
            $response = @file_get_contents($verifyUrl);

            if ($response === false) {
                $_SESSION['error'] = "Gagal memverifikasi token dengan server Google.";
                header("Location: " . $this->baseUrl . "/views/auth/login.php");
                exit;
            }

            $googleUserData = json_decode($response, true);

            if (isset($googleUserData['sub']) && isset($googleUserData['email'])) {

                $googleId = $googleUserData['sub'];
                $email    = $googleUserData['email'];
                $name     = $googleUserData['name'] ?? explode('@', $email)[0];

                $user = $this->userModel->findOrCreateGoogleUser($googleId, $name, $email);

                if ($user) {
                    $role = $user['role'] ?? 'user';

                    $_SESSION['id_users'] = $user['id_users'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role']     = $role;

                    $_SESSION['user'] = [
                        'id_users' => $user['id_users'],
                        'username' => $user['username'],
                        'role'     => $role
                    ];

                    if ($role === 'admin') {
                        $_SESSION['success'] = "Berhasil masuk via Google! Selamat datang, Admin.";
                        header("Location: " . $this->baseUrl . "/views/admin/dashboard.php");
                    } else {
                        $_SESSION['success'] = "Berhasil masuk dengan akun Google!";
                        header("Location: " . $this->baseUrl . "/index.php");
                    }
                    exit;
                }
            }

            $_SESSION['error'] = "Autentikasi Google gagal, silakan coba lagi.";
            header("Location: " . $this->baseUrl . "/views/auth/login.php");
            exit;
        }
    }
}

// Router Sederhana Berdasarkan Query Parameter ?action=
$action = $_GET['action'] ?? '';

if (!empty($action)) {
    $controller = new AuthController($baseUrl);

    switch ($action) {
        case 'register':
            $controller->handleRegister();
            break;
        case 'login':
            $controller->handleLogin();
            break;
        case 'logout':
            $controller->handleLogout();
            break;
        case 'google_login':
            $controller->handleGoogleLogin();
            break;
        default:
            header("Location: " . $baseUrl . "/views/auth/login.php");
            exit;
    }
} else {
    header("Location: " . $baseUrl . "/views/auth/login.php");
    exit;
}

// Jika login berhasil:
header("Location: ../../views/profile/index.php");
exit();
?>