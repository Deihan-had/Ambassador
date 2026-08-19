<?php
require_once __DIR__ . '/includes/config.php';
admin_required();
$title = 'Pelanggan';
$page = 'pelanggan';
if (has_table($conn, 'users')) {
    $rowsData = has_column($conn, 'users', 'id_users') ? rows($conn, "SELECT u.username,u.email,COUNT(o.id_order) pesanan FROM users u LEFT JOIN orders o ON o.id_users=u.id_users WHERE u.role<>'admin' GROUP BY u.id_users,u.username,u.email ORDER BY u.id_users DESC") : [];
} else
    $rowsData = has_table($conn, 'pelanggan') ? rows($conn, 'SELECT p.nama,p.email,p.telepon,COUNT(o.id_order) pesanan FROM pelanggan p LEFT JOIN orders o ON o.id_pelanggan=p.id_pelanggan GROUP BY p.id_pelanggan ORDER BY p.id_pelanggan DESC') : [];
require __DIR__ . '/includes/header.php'; ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <tr>
                <th>Nama/Username</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Pesanan</th>
            </tr><?php foreach ($rowsData as $r): ?>
                <tr>
                    <td><?= e($r['nama'] ?? $r['username'] ?? '-') ?></td>
                    <td><?= e($r['email'] ?? '-') ?></td>
                    <td><?= e($r['telepon'] ?? '-') ?></td>
                    <td><?= e($r['pesanan']) ?></td>
                </tr><?php endforeach;
            if (!$rowsData): ?>
                <tr>
                    <td colspan="4" class="empty">Belum ada pelanggan.</td>
                </tr><?php endif; ?>
        </table>
    </div>
</div><?php require __DIR__ . '/includes/footer.php'; ?>