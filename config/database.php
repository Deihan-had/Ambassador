<?php
class Database
{
    private string $host = "localhost";
    private string $user = "root";
    private string $pass = "";
    private string $db_name = "ambas_sador";

    public $conn;

    /**
     * Membuat koneksi ke database menggunakan MySQLi
     * @return mysqli
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db_name);

            if (!$this->conn) {
                throw new Exception("Koneksi Database Gagal: " . mysqli_connect_error());
            }

            // Set charset ke utf8mb4 agar mendukung semua karakter
            mysqli_set_charset($this->conn, "utf8mb4");

        } catch (Exception $e) {
            die("<strong>Database Error:</strong> " . $e->getMessage());
        }

        return $this->conn;
    }
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/webdesign/');
}
?>