<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Ambil input JSON dari fetch JavaScript
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (isset($data['cart']) && is_array($data['cart']) && count($data['cart']) > 0) {
    $formatted_cart = array();

    foreach ($data['cart'] as $item) {
        // Ambil jumlah barang (baik dari properti 'qty' maupun 'quantity')
        $qty = isset($item['qty']) ? $item['qty'] : (isset($item['quantity']) ? $item['quantity'] : 1);
        
        $formatted_cart[] = array(
            'id'       => isset($item['id']) ? $item['id'] : rand(100, 999),
            'name'     => isset($item['name']) ? $item['name'] : 'Produk',
            'price'    => isset($item['price']) ? (int)$item['price'] : 0,
            'quantity' => (int)$qty
        );
    }

    // Simpan ke Session
    $_SESSION['checkout_cart'] = $formatted_cart;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Data keranjang kosong atau format salah']);
}