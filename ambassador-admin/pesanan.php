<?php require_once __DIR__ . '/includes/config.php';
$title = 'Pesanan';
$page = 'pesanan';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];
    execute_stmt($conn, 'UPDATE orders SET status_pesanan=? WHERE id_order=?', 'si', [$status, $id]);
    flash('success', 'Status pesanan diperbarui.');
    header('Location:pesanan.php');
    exit;
}
$orders = rows($conn, 'SELECT o.*,COALESCE(b.nama_lengkap,u.username) customer FROM orders o JOIN users u ON u.id_users=o.id_users LEFT JOIN biodata b ON b.id_users=u.id_users ORDER BY o.created_at DESC LIMIT 100');
require __DIR__ . '/includes/header.php'; ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <tr>
                <th>Order</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr><?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= e($o['id_order']) ?></td>
                    <td><?= e($o['customer']) ?></td>
                    <td><?= rupiah($o['total_harga']) ?></td>
                    <td><?= e($o['status_pembayaran']) ?></td>
                    <td>
                        <form method="post"><input type="hidden" name="id" value="<?= $o['id_order'] ?>"><select name="status"
                                onchange="this.form.submit()">
                                <option value="pending" <?= $o['status_pesanan'] === 'pending' ? 'selected' : '' ?>>Menunggu
                                </option>
                                <option value="diproses" <?= $o['status_pesanan'] === 'diproses' ? 'selected' : '' ?>>Diproses
                                </option>
                                <option value="dikirim" <?= $o['status_pesanan'] === 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="selesai" <?= $o['status_pesanan'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                <option value="dibatalkan" <?= $o['status_pesanan'] === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan
                                </option>
                            </select></form>
                    </td>
                    <td><?= e($o['created_at']) ?></td>
                </tr><?php endforeach;
            if (!$orders): ?>
                <tr>
                    <td colspan="6" class="empty">Belum ada pesanan.</td>
                </tr><?php endif; ?>
        </table>
    </div>
</div><?php require __DIR__ . '/includes/footer.php'; ?>