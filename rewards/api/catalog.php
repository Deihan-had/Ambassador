<?php

header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ambas_sador';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');

    $sql = "
        SELECT
            p.id_produk,
            p.kategori_id,
            p.nama,
            p.harga,
            p.foto,
            p.detail,
            p.stok,
            k.nama AS kategori
        FROM produk p
        LEFT JOIN kategori k
            ON k.id_kategori = p.kategori_id
        ORDER BY p.id_produk DESC
    ";

    $result = $conn->query($sql);

    $products = [];

    while ($row = $result->fetch_assoc()) {

        $nama = (string)$row['nama'];

        $products[] = [
            'id' => (int)$row['id_produk'],
            'id_produk' => (int)$row['id_produk'],

            'name' => $nama,
            'nama' => $nama,

            'category' => $row['kategori'] ?? '',
            'kategori' => $row['kategori'] ?? '',
            'kategori_id' => (int)$row['kategori_id'],

            'price' => (float)$row['harga'],
            'harga' => (float)$row['harga'],

            'price_was' => (float)$row['harga'],

            'stock' => (int)$row['stok'],
            'stok' => (int)$row['stok'],

            'image' => $row['foto'] ?? '',
            'foto' => $row['foto'] ?? '',

            'description' => $row['detail'] ?? '',
            'detail' => $row['detail'] ?? '',

            'rating' => 0,
            'reviews' => 0,
            'sold' => 0,

            'badge' => ((int)$row['stok'] > 0)
                ? 'Tersedia'
                : 'Habis',

            'initials' => strtoupper(
                implode(
                    '',
                    array_slice(
                        preg_split('/\s+/', trim($nama)),
                        0,
                        2
                    )
                )
            ),

            'status' => ((int)$row['stok'] > 0)
                ? 'Aktif'
                : 'Habis'
        ];
    }

    $catResult = $conn->query("
        SELECT id_kategori, nama
        FROM kategori
        ORDER BY nama ASC
    ");

    $categories = [];

    while ($row = $catResult->fetch_assoc()) {
        $categories[] = [
            'id' => (int)$row['id_kategori'],
            'name' => $row['nama']
        ];
    }

    echo json_encode([
        'success' => true,
        'products' => $products,
        'categories' => $categories
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'products' => [],
        'categories' => []
    ], JSON_UNESCAPED_UNICODE);
}