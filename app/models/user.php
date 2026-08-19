<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    private $conn;
    private $table = "users";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Fungsi untuk Registrasi User Baru
    public function register($idUsers, $username, $email, $password)
    {
        // Cek apakah username atau email sudah ada
        if ($this->findByUsername($username) || $this->findByEmail($email)) {
            return false;
        }

        // Hash password agar aman
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = "user";

        $query = "INSERT INTO " . $this->table . " (id_users, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "sssss", $idUsers, $username, $email, $hashedPassword, $role);
        $hasil = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $hasil;
    }

    // Fungsi Cari User berdasarkan Username
    public function findByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $user;
    }

    // Fungsi Cari User berdasarkan Email
    public function findByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $user;
    }

    // Fungsi Login menggunakan Google
    public function findOrCreateGoogleUser($googleId, $name, $email)
    {
        // 1. Coba cari user berdasarkan google_id
        $query = "SELECT * FROM " . $this->table . " WHERE google_id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $googleId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($user) return $user;
        }

        // 2. Jika tidak ada, cari berdasarkan email
        $user = $this->findByEmail($email);
        if ($user) {
            // Update google_id ke akun yang sudah ada
            $query = "UPDATE " . $this->table . " SET google_id = ? WHERE id_users = ?";
            $stmt = mysqli_prepare($this->conn, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $googleId, $user['id_users']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            $user['google_id'] = $googleId;
            return $user;
        }

        // 3. Jika belum punya akun sama sekali, buat user baru
        $namaBersih = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));
        if ($namaBersih == "") {
            $namaBersih = explode('@', $email)[0];
        }

        $username = $namaBersih . '_' . substr($googleId, -4);
        
        // Pastikan username unik
        $usernameAwal = $username;
        $angka = 1;
        while ($this->findByUsername($username)) {
            $username = $usernameAwal . $angka;
            $angka++;
        }

        $idUsers = 'USR-GGL-' . time();
        $passwordAsal = password_hash(bin2hex(random_bytes(10)), PASSWORD_BCRYPT);
        $role = "user";

        $query = "INSERT INTO " . $this->table . " (id_users, username, email, google_id, password, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "ssssss", $idUsers, $username, $email, $googleId, $passwordAsal, $role);
        $hasil = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $hasil ? $this->findByEmail($email) : false;
    }
}