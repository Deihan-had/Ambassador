<?php require_once __DIR__ . '/includes/config.php';
$id = (int) ($_GET['id'] ?? $_POST['id_produk'] ?? 0);
$edit = $id > 0;
$title = $edit ? 'Edit Produk' : 'Tambah Produk';
$page = 'produk';
$cats = rows($conn, 'SELECT id_kategori,nama FROM kategori ORDER BY nama');
$p = ['id_produk' => 0, 'kategori_id' => '', 'nama' => '', 'harga' => '', 'foto' => '', 'detail' => '', 'stok' => 0];
if ($edit) {
    $p = row($conn, 'SELECT id_produk,kategori_id,nama,harga,foto,detail,stok FROM produk WHERE id_produk=?', 'i', [$id]) ?? $p;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = ['id_produk' => $id, 'kategori_id' => (int) $_POST['kategori_id'], 'nama' => trim($_POST['nama'] ?? ''), 'harga' => (float) $_POST['harga'], 'foto' => trim($_POST['foto'] ?? ''), 'detail' => trim($_POST['detail'] ?? ''), 'stok' => (int) $_POST['stok']];
    $err = [];
    if (!$p['kategori_id'])
        $err[] = 'Kategori wajib dipilih.';
    if ($p['nama'] === '')
        $err[] = 'Nama wajib diisi.';
    if ($p['harga'] < 0)
        $err[] = 'Harga tidak valid.';
    if ($p['stok'] < 0)
        $err[] = 'Stok tidak valid.';
    if (!$err) {
        if ($edit) {
            execute_stmt($conn, 'UPDATE produk SET kategori_id=?,nama=?,harga=?,foto=?,detail=?,stok=? WHERE id_produk=?', 'isdssii', [$p['kategori_id'], $p['nama'], $p['harga'], $p['foto'], $p['detail'], $p['stok'], $id]);
            flash('success', 'Produk diperbarui.');
        } else {
            execute_stmt($conn, 'INSERT INTO produk(kategori_id,nama,harga,foto,detail,stok) VALUES(?,?,?,?,?,?)', 'isdssi', [$p['kategori_id'], $p['nama'], $p['harga'], $p['foto'], $p['detail'], $p['stok']]);
            flash('success', 'Produk ditambahkan.');
        }
        header('Location:produk.php');
        exit;
    }
}
require __DIR__ . '/includes/header.php';
if (!empty($err)): ?>
    <div class="flash error"><?php foreach ($err as $x)
        echo e($x) . '<br>'; ?></div><?php endif; ?>
<div class="card">
    <form method="post"><input type="hidden" name="id_produk" value="<?= $p['id_produk'] ?>">
        <div class="field"><label>Nama Produk</label><input name="nama" value="<?= e($p['nama']) ?>" required></div>
        <div class="form-grid">
            <div class="field"><label>Kategori</label><select name="kategori_id" required>
                    <option value="">Pilih</option><?php foreach ($cats as $c): ?>
                        <option value="<?= $c['id_kategori'] ?>" <?= $p['kategori_id'] == $c['id_kategori'] ? 'selected' : '' ?>>
                            <?= e($c['nama']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="field"><label>Harga</label><input type="number" name="harga" min="0" value="<?= $p['harga'] ?>"
                    required></div>
            <div class="field"><label>Stok</label><input type="number" name="stok" min="0" value="<?= $p['stok'] ?>"
                    required></div>
            <div class="field"><label>Foto / URL</label><input name="foto" value="<?= e($p['foto']) ?>"></div>
        </div>
        <div class="field"><label>Detail</label><textarea name="detail" rows="6"><?= e($p['detail']) ?></textarea></div>
        <button class="btn primary">Simpan Produk</button> <a class="btn" href="produk.php">Batal</a>
    </form>
</div><?php require __DIR__ . '/includes/footer.php'; ?>