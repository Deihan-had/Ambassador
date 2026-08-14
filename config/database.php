<?php
// class buat konek ke database, biar gampang dipanggil di file lain

class Database {
    var $host = "localhost";
    var $db_name = "ecommerce_db";
    var $user = "root";
    var $pass = "";
    var $conn;

    function getConnection() {

        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db_name);

        if (!$this->conn) {
            die("Koneksi Database Gagal: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conn, "utf8mb4");

        return $this->conn;
    }
}
?>