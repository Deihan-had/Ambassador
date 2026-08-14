<?php
// models/Produk.php
class Produk {

    var $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    function create($kategoriId, $nama, $harga, $foto, $detail, $stok) {
        $query = "INSERT INTO produk (kategori_id, nama, harga, foto, detail, stok) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isdssi", $kategoriId, $nama, $harga, $foto, $detail, $stok);
        return mysqli_stmt_execute($stmt);
    }

    function getByKategori($kategoriId) {
        $query = "SELECT p.*, k.nama AS nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori WHERE p.kategori_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $kategoriId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }
}
?>