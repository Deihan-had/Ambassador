<?php
class Biodata {

    var $conn;

    function __construct($conn) {
        $this->conn = $conn;
    }

    function saveOrUpdate($idUsers, $namaLengkap, $alamat, $noHp) {

        // cek dulu udah ada biodata nya belum
        $cekQuery = "SELECT id_bio FROM biodata WHERE id_users = ?";
        $cekStmt = mysqli_prepare($this->conn, $cekQuery);
        mysqli_stmt_bind_param($cekStmt, "s", $idUsers);
        mysqli_stmt_execute($cekStmt);
        $cekResult = mysqli_stmt_get_result($cekStmt);

        if (mysqli_fetch_assoc($cekResult)) {

            // udah ada, tinggal update aja
            $query = "UPDATE biodata SET nama_lengkap = ?, alamat_lengkap = ?, no_telephone = ? WHERE id_users = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $namaLengkap, $alamat, $noHp, $idUsers);
            return mysqli_stmt_execute($stmt);

        } else {

            // belum ada, insert data baru
            $query = "INSERT INTO biodata (id_users, nama_lengkap, alamat_lengkap, no_telephone) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $idUsers, $namaLengkap, $alamat, $noHp);
            return mysqli_stmt_execute($stmt);
        }
    }
}
?>