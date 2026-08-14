<?php
session_start();

require_once __DIR__ . '/../koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "login" => true,
        "message" => "Silakan login terlebih dahulu"
    ]);
    exit;
}

$id_users = $_SESSION['user_id'];
$id_produk = $_POST['id_produk'] ?? '';
$jumlah = (int) ($_POST['jumlah'] ?? 1);

if ($id_produk == '') {
    echo json_encode([
        "success" => false,
        "message" => "Produk tidak ditemukan"
    ]);
    exit;
}

if ($jumlah < 1) {
    $jumlah = 1;
}

/* cek apakah produk sudah ada di keranjang */
$query = mysqli_prepare($con, "
    SELECT id_keranjang, jumlah_beli
    FROM keranjang
    WHERE id_users = ? AND id_produk = ?
");

mysqli_stmt_bind_param($query, "si", $id_users, $id_produk);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if ($data) {

    $jumlah_baru = $data['jumlah_beli'] + $jumlah;

    $update = mysqli_prepare($con, "
        UPDATE keranjang
        SET jumlah_beli = ?
        WHERE id_keranjang = ?
    ");

    mysqli_stmt_bind_param(
        $update,
        "is",
        $jumlah_baru,
        $data['id_keranjang']
    );

    mysqli_stmt_execute($update);

} else {

    $id_keranjang = uniqid("cart_");

    $insert = mysqli_prepare($con, "
        INSERT INTO keranjang
        (id_keranjang, id_users, id_produk, jumlah_beli)
        VALUES (?, ?, ?, ?)
    ");

    mysqli_stmt_bind_param(
        $insert,
        "ssii",
        $id_keranjang,
        $id_users,
        $id_produk,
        $jumlah
    );

    mysqli_stmt_execute($insert);
}

echo json_encode([
    "success" => true,
    "message" => "Produk berhasil ditambahkan ke keranjang"
]);