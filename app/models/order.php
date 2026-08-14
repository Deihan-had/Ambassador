<?php
// models/Order.php
class Order {

    var $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    // proses checkout, pake transaction biar kalo ada yang gagal ditengah bisa dibatalin semua
    function checkout($idOrder, $idUsers, $totalHarga, $metode, $items) {

        mysqli_begin_transaction($this->conn);

        try {

            // 1. simpan data order utama
            $query = "INSERT INTO orders (id_order, id_users, total_harga, metode_pembayaran) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ssds", $idOrder, $idUsers, $totalHarga, $metode);
            mysqli_stmt_execute($stmt);

            // 2. simpan detail produk yang dibeli + kurangin stok nya
            $queryDetail = "INSERT INTO order_details (id_order, id_produk, jumlah, harga_satuan) VALUES (?, ?, ?, ?)";
            $stmtDetail = mysqli_prepare($this->conn, $queryDetail);

            $queryStok = "UPDATE produk SET stok = stok - ? WHERE id_produk = ?";
            $stmtStok = mysqli_prepare($this->conn, $queryStok);

            foreach ($items as $item) {
                mysqli_stmt_bind_param($stmtDetail, "siid", $idOrder, $item['id_produk'], $item['jumlah'], $item['harga_satuan']);
                mysqli_stmt_execute($stmtDetail);

                mysqli_stmt_bind_param($stmtStok, "ii", $item['jumlah'], $item['id_produk']);
                mysqli_stmt_execute($stmtStok);
            }

            // 3. kosongin keranjang belanja user nya
            $queryClear = "DELETE FROM keranjang WHERE id_users = ?";
            $stmtClear = mysqli_prepare($this->conn, $queryClear);
            mysqli_stmt_bind_param($stmtClear, "s", $idUsers);
            mysqli_stmt_execute($stmtClear);

            mysqli_commit($this->conn);
            return true;

        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return false;
        }
    }

    // buat fitur laporan penjualan berdasarkan rentang tanggal
    function getSalesReport($startDate, $endDate) {

        $query = "SELECT o.id_order, u.username, o.total_harga, o.status_pembayaran, o.created_at
                  FROM orders o
                  JOIN users u ON o.id_users = u.id_users
                  WHERE DATE(o.created_at) BETWEEN ? AND ?
                  ORDER BY o.created_at DESC";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $startDate, $endDate);
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