<?php
// app/models/produk.php

class Produk {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // 1. Ambil Semua Produk (untuk Katalog User & Tabel Admin)
    public function getAll() {
        $query = "SELECT p.*, k.nama AS nama_kategori 
                  FROM produk p 
                  LEFT JOIN kategori k ON p.kategori_id = k.id_kategori 
                  ORDER BY p.id_produk DESC";
        $result = mysqli_query($this->conn, $query);
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // 2. Ambil Single Produk berdasarkan ID (untuk Detail Produk & Edit Form)
    public function getById($idProduk) {
        $query = "SELECT p.*, k.nama AS nama_kategori 
                  FROM produk p 
                  LEFT JOIN kategori k ON p.kategori_id = k.id_kategori 
                  WHERE p.id_produk = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idProduk);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // 3. Ambil Produk Berdasarkan Kategori
    public function getByKategori($kategoriId) {
        $query = "SELECT p.*, k.nama AS nama_kategori 
                  FROM produk p 
                  JOIN kategori k ON p.kategori_id = k.id_kategori 
                  WHERE p.kategori_id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $kategoriId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // 4. Tambah Produk Baru (Create)
    public function create($kategoriId, $nama, $harga, $foto, $detail, $stok) {
        $query = "INSERT INTO produk (kategori_id, nama, harga, foto, detail, stok) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        // Tipe parameter: i (int), s (string), d (double/decimal), s (string), s (string), i (int)
        mysqli_stmt_bind_param($stmt, "isdssi", $kategoriId, $nama, $harga, $foto, $detail, $stok);
        return mysqli_stmt_execute($stmt);
    }

    // 5. Update Data Produk (Edit)
    public function update($idProduk, $kategoriId, $nama, $harga, $foto, $detail, $stok) {
        $query = "UPDATE produk SET kategori_id = ?, nama = ?, harga = ?, foto = ?, detail = ?, stok = ? WHERE id_produk = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "isdssii", $kategoriId, $nama, $harga, $foto, $detail, $stok, $idProduk);
        return mysqli_stmt_execute($stmt);
    }

    // 6. Hapus Produk (Delete)
    public function delete($idProduk) {
        $query = "DELETE FROM produk WHERE id_produk = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idProduk);
        return mysqli_stmt_execute($stmt);
    }
}
?>