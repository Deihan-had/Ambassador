<?php
class Keranjang {

    var $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    function addToCart($idKeranjang, $idUsers, $idProduk, $jumlah) {

        // cek dulu, produk ini udah ada di keranjang belum
        $query = "SELECT id_keranjang, jumlah_beli FROM keranjang WHERE id_users = ? AND id_produk = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $idUsers, $idProduk);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $existing = mysqli_fetch_assoc($result);

        if ($existing) {
            // udah ada, tambahin aja jumlahnya
            $jumlahBaru = $existing['jumlah_beli'] + $jumlah;

            $update = "UPDATE keranjang SET jumlah_beli = ? WHERE id_keranjang = ?";
            $stmtUpdate = mysqli_prepare($this->conn, $update);
            mysqli_stmt_bind_param($stmtUpdate, "is", $jumlahBaru, $existing['id_keranjang']);
            return mysqli_stmt_execute($stmtUpdate);
        }

        // belum ada, insert baru
        $insert = "INSERT INTO keranjang (id_keranjang, id_users, id_produk, jumlah_beli) VALUES (?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($this->conn, $insert);
        mysqli_stmt_bind_param($stmtInsert, "ssii", $idKeranjang, $idUsers, $idProduk, $jumlah);
        return mysqli_stmt_execute($stmtInsert);
    }
}
?>