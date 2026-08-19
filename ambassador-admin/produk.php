<?php require_once __DIR__ . '/includes/config.php';
$title = 'Produk';
$page = 'produk';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = (int) $_POST['id'];
    $used = (int) scalar($conn, 'SELECT COUNT(*) FROM order_details WHERE id_produk=?', 'i', [$id]);
    if ($used) {
        flash('error', 'Produk sudah dipakai pesanan dan tidak bisa dihapus.');
    } else {
        execute_stmt($conn, 'DELETE FROM produk WHERE id_produk=?', 'i', [$id]);
        flash('success', 'Produk dihapus.');
    }
    header('Location:produk.php');
    exit;
}
$q = trim($_GET['q'] ?? '');
$cat = (int) ($_GET['kategori'] ?? 0);
$cats = rows($conn, 'SELECT id_kategori,nama FROM kategori ORDER BY nama');
$sql = 'SELECT p.*,k.nama kategori FROM produk p LEFT JOIN kategori k ON k.id_kategori=p.kategori_id';
$where = [];
$params = [];
$types = '';
if ($q !== '') {
    $where[] = '(p.nama LIKE ? OR p.detail LIKE ?)';
    $like = "%$q%";
    $params = [$like, $like];
    $types = 'ss';
}
if ($cat > 0) {
    $where[] = 'p.kategori_id=?';
    $params[] = $cat;
    $types .= 'i';
}
if ($where)
    $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY p.id_produk DESC';
$products = rows($conn, $sql, $types, $params);
require __DIR__ . '/includes/header.php'; ?>
<div class="toolbar">
    <form class="search"><input name="q" value="<?= e($q) ?>" placeholder="Cari produk"><select name="kategori">
            <option value="0">Semua kategori</option><?php foreach ($cats as $c): ?>
                <option value="<?= $c['id_kategori'] ?>" <?= $cat == (int) $c['id_kategori'] ? 'selected' : '' ?>><?= e($c['nama']) ?>
                </option><?php endforeach; ?>
        </select><button class="btn">Cari</button></form><a class="btn primary" href="produk_form.php">+ Tambah
        Produk</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr><?php foreach ($products as $p): ?>
                <tr>
                    <td><b><?= e($p['nama']) ?></b><br><small><?= e($p['detail'] ?? '') ?></small></td>
                    <td><?= e($p['kategori'] ?? '-') ?></td>
                    <td><?= rupiah($p['harga']) ?></td>
                    <td><?= $p['stok'] ?></td>
                    <td><span
                            class="pill <?= ((int) $p['stok'] > 0 ? 'ok' : 'bad') ?>"><?= ((int) $p['stok'] > 0 ? 'Aktif' : 'Habis') ?></span>
                    </td>
                    <td class="actions"><a class="btn" href="produk_form.php?id=<?= $p['id_produk'] ?>">Edit</a>
                        <form method="post" onsubmit="return confirm('Hapus produk ini?')"><input type="hidden" name="id"
                                value="<?= $p['id_produk'] ?>"><button class="btn danger" name="delete">Hapus</button></form>
                    </td>
                </tr><?php endforeach;
            if (!$products): ?>
                <tr>
                    <td colspan="6" class="empty">Belum ada produk.</td>
                </tr><?php endif; ?>
        </table>
    </div>
</div><?php require __DIR__ . '/includes/footer.php'; ?>