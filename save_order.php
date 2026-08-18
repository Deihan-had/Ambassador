<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data && isset($data['order_id'])) {
    $new_order = array(
        'order_id' => $data['order_id'],
        'date' => date('d M Y, H:i'),
        'total' => (int) $data['total'],
        'status' => 'Dalam Proses',
        'items' => $data['items'],
        'payment_type' => isset($data['payment_type']) ? $data['payment_type'] : 'Midtrans'
    );

    if (!isset($_SESSION['user_orders'])) {
        $_SESSION['user_orders'] = array();
    }

    // Masukkan pesanan paling baru di urutan teratas
    array_unshift($_SESSION['user_orders'], $new_order);

    // Hapus session keranjang temporer
    unset($_SESSION['checkout_cart']);

    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Data tidak valid'));
}