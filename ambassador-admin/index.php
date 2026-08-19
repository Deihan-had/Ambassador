<?php require_once __DIR__ . '/includes/config.php';
$title = 'Dashboard';
$page = 'dashboard';
$total_products = (int) scalar($conn, 'SELECT COUNT(*) FROM produk');
$total_customers = (int) scalar($conn, "SELECT COUNT(*) FROM users WHERE role <> 'admin'");
$total_orders = (int) scalar($conn, 'SELECT COUNT(*) FROM orders');
$revenue = (float) scalar($conn, "SELECT COALESCE(SUM(total_harga),0) FROM orders WHERE status_pembayaran IN ('paid','completed')");
$low = rows($conn, 'SELECT p.nama,p.stok,k.nama kategori FROM produk p LEFT JOIN kategori k ON k.id_kategori=p.kategori_id WHERE p.stok<=10 ORDER BY p.stok,p.nama LIMIT 8');
$recent = rows($conn, 'SELECT o.id_order,COALESCE(b.nama_lengkap,u.username) customer,o.total_harga,o.status_pembayaran,o.status_pesanan,o.created_at FROM orders o JOIN users u ON u.id_users=o.id_users LEFT JOIN biodata b ON b.id_users=u.id_users ORDER BY o.created_at DESC LIMIT 8');
require __DIR__ . '/includes/header.php'; ?>
<div class="cards">
    <div class="card metric"><small>Total Produk</small><strong><?= $total_products ?></strong></div>
    <div class="card metric"><small>Pelanggan</small><strong><?= $total_customers ?></strong></div>
    <div class="card metric"><small>Pesanan</small><strong><?= $total_orders ?></strong></div>
    <div class="card metric"><small>Pendapatan</small><strong><?= rupiah($revenue) ?></strong></div>
</div>
<div class="grid">
    <section class="card section">
        <h2>Pesanan terbaru</h2>
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Order</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr><?php foreach ($recent as $o): ?>
                    <tr>
                        <td><?= e($o['id_order']) ?></td>
                        <td><?= e($o['customer']) ?></td>
                        <td><?= rupiah($o['total_harga']) ?></td>
                        <td><span
                                class="pill <?= status_class($o['status_pesanan']) ?>"><?= e(order_status_label($o['status_pesanan'])) ?></span>
                        </td>
                    </tr><?php endforeach;
                if (!$recent): ?>
                    <tr>
                        <td colspan="4" class="empty">Belum ada pesanan.</td>
                    </tr><?php endif; ?>
            </table>
        </div>
    </section>
    <section class="card section">
        <h2>Stok menipis</h2><?php foreach ($low as $p): ?>
            <p><b><?= e($p['nama']) ?></b><br><span class="pill <?= ((int) $p['stok'] === 0 ? 'bad' : 'warn') ?>"><?= $p['stok'] ?>
                    stok</span></p><?php endforeach;
        if (!$low): ?>
            <div class="empty">Semua stok aman.</div><?php endif; ?>
    </section>
</div><?php require __DIR__ . '/includes/footer.php'; ?>