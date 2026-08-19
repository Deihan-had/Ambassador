<?php require_once __DIR__ . '/includes/config.php';
$title = 'Kategori';
$page = 'kategori';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $n = trim($_POST['nama'] ?? '');
        if ($n !== '') {
            execute_stmt($conn, 'INSERT INTO kategori(nama) VALUES(?)', 's', [$n]);
            flash('success', 'Kategori ditambahkan.');
        }
    } elseif (isset($_POST['delete'])) {
        $id = (int) $_POST['id'];
        $count = (int) scalar($conn, 'SELECT COUNT(*) FROM produk WHERE kategori_id=?', 'i', [$id]);
        if ($count)
            flash('error', 'Kategori masih dipakai produk.');
        else {
            execute_stmt($conn, 'DELETE FROM kategori WHERE id_kategori=?', 'i', [$id]);
            flash('success', 'Kategori dihapus.');
        }
    }
    header('Location:kategori.php');
    exit;
}
$cats = rows($conn, 'SELECT k.id_kategori,k.nama,COUNT(p.id_produk) produk FROM kategori k LEFT JOIN produk p ON p.kategori_id=k.id_kategori GROUP BY k.id_kategori,k.nama ORDER BY k.nama');
require __DIR__ . '/includes/header.php'; ?>
<div class="card">
    <form method="post" class="search"><input name="nama" placeholder="Nama kategori" required><button
            class="btn primary" name="add">Tambah</button></form>
</div>
<div class="card" style="margin-top:15px">
    <table>
        <tr>
            <th>Kategori</th>
            <th>Produk</th>
            <th>Aksi</th>
        </tr><?php foreach ($cats as $c): ?>
            <tr>
                <td><?= e($c['nama']) ?></td>
                <td><?= $c['produk'] ?></td>
                <td><?php if (!$c['produk']): ?>
                        <form method="post"><input type="hidden" name="id" value="<?= $c['id_kategori'] ?>"><button
                                class="btn danger" name="delete">Hapus</button></form><?php else: ?>Dipakai<?php endif; ?>
                </td>
            </tr><?php endforeach; ?>
    </table>
</div><?php require __DIR__ . '/includes/footer.php'; ?>