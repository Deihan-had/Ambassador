<?php

require_once __DIR__ . '/../../config/database.php';

class User {

    var $conn;
    var $table = "users";

    function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    function register($idUsers, $username, $password) {

        // cek dulu username nya udah kepake apa belum
        $cek = $this->findByUsername($username);
        if ($cek) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = "user";

        $query = "INSERT INTO " . $this->table . " (id_users, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "ssss", $idUsers, $username, $hashedPassword, $role);

        return mysqli_stmt_execute($stmt);
    }

    function findByUsername($username) {

        $query = "SELECT * FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }

    function findOrCreateGoogleUser($googleId, $name, $email) {

        // bikin username dari nama google nya
        $namaBersih = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));

        if ($namaBersih == "") {
            $pecahEmail = explode('@', $email);
            $namaBersih = $pecahEmail[0];
        }

        $username = $namaBersih . '_' . substr($googleId, -4);

        $userLama = $this->findByUsername($username);
        if ($userLama) {
            return $userLama;
        }

        $idUsers = 'USR-GGL-' . time();
        $passwordAsal = password_hash(bin2hex(random_bytes(10)), PASSWORD_BCRYPT);
        $role = "user";

        $query = "INSERT INTO " . $this->table . " (id_users, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "ssss", $idUsers, $username, $passwordAsal, $role);

        if (mysqli_stmt_execute($stmt)) {
            return array(
                'id_users' => $idUsers,
                'username' => $username,
                'role'     => $role
            );
        }

        return false;
    }
}
?>