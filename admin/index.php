<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../app/models/produk.php';

$produkModel = new Produk($conn);
$semuaProduk = $produkModel->getAll() ?? [];

// Helper query aman
function getCount($conn, $query) {
    $res = mysqli_query($conn, $query);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        return $row['total'] ?? 0;
    }
    return 0;
}

$total_orders = getCount($conn, "SELECT COUNT(*) as total FROM orders");
$total_users  = getCount($conn, "SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
$pendapatan   = getCount($conn, "SELECT SUM(total_harga) as total FROM orders");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambassador - Admin Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; color: #212529; }
        .bg-navy { background-color: #0f172a; }
        .btn-green { background-color: #00b074; color: #fff; border: none; }
        .btn-green:hover { background-color: #009663; color: #fff; }
        .product-card { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff; }
        .badge-status { position: absolute; top: 12px; left: 12px; z-index: 2; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-success d-flex align-items-center gap-2" href="#">
            <i class="fa-solid fa-bag-shopping fs-3"></i>
            <div>
                <span class="fs-4 text-dark d-block">Ambassador</span>
                <small class="text-muted fs-6 font-monospace">ADMIN PANEL</small>
            </div>
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-danger px-3 py-2">Role: Administrator</span>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
        </div>
    </div>
</nav>

<section class="bg-navy text-white py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-success mb-2">ADMIN DASHBOARD</span>
                <h2 class="fw-bold">Pengelolaan Toko & Inventaris</h2>
                <p class="text-secondary">Pantau transaksi pesanan, kelola stok barang, dan atur akun pengguna dalam satu tampilan terpadu.</p>
            </div>
            <div class="col-lg-5">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-3 bg-dark rounded-3 border border-secondary">
                            <small class="text-secondary d-block">Pesanan</small>
                            <span class="fs-4 fw-bold text-warning"><?= $total_orders; ?></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-dark rounded-3 border border-secondary">
                            <small class="text-secondary d-block">User Regist</small>
                            <span class="fs-4 fw-bold text-info"><?= $total_users; ?></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-dark rounded-3 border border-secondary">
                            <small class="text-secondary d-block">Omset</small>
                            <span class="fs-6 fw-bold text-success">Rp <?= number_format($pendapatan, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-green rounded-pill px-4">Semua Produk</a>
            <a href="orders.php" class="btn btn-outline-secondary rounded-pill px-4">Kelola Pesanan</a>
            <a href="users.php" class="btn btn-outline-secondary rounded-pill px-4">Data User</a>
        </div>
        <a href="tambah_produk.php" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-1"></i> Tambah Produk Baru</a>
    </div>
</div>

<div class="container pb-5">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-boxes-stacked me-2"></i>Daftar Inventaris Produk</h5>
    <div class="row g-4">
        <?php if (!empty($semuaProduk)): ?>
            <?php foreach ($semuaProduk as $item): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card h-100 position-relative d-flex flex-column">
                        <span class="badge bg-dark badge-status">Stok: <?= $item['stok'] ?? 0; ?></span>
                        <img src="../assets/img/<?= !empty($item['foto']) ? $item['foto'] : 'default.jpg'; ?>" class="card-img-top p-3" style="height: 220px; object-fit: contain;">
                        <div class="card-body d-flex flex-column justify-content-between p-3 border-top">
                            <div>
                                <small class="text-muted d-block mb-1"><?= htmlspecialchars($item['nama_kategori'] ?? 'Umum'); ?></small>
                                <h6 class="fw-bold text-truncate mb-2"><?= htmlspecialchars($item['nama'] ?? 'Tanpa Nama'); ?></h6>
                                <p class="fw-bold text-success mb-3">Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.'); ?></p>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="edit_produk.php?id=<?= $item['id_produk']; ?>" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-pen-to-square me-1"></i> Edit Produk</a>
                                <a href="hapus_produk.php?id=<?= $item['id_produk']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')"><i class="fa-solid fa-trash me-1"></i> Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada produk yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer class="bg-navy text-white-50 py-4 text-center mt-auto">
    <small>&copy; 2026 Ambassador Inc. Admin Dashboard Management.</small>
</footer>

</body>
</html>