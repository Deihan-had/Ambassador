<?php
// Koneksi dan konfigurasi dasar
require_once __DIR__ . '/includes/config.php';

// Cek hak akses admin
if (function_exists('admin_required')) {
    admin_required();
} elseif (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}

$title = 'Flash Sale';
$page = 'promo';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$message = '';
$messageType = '';

// Proses utama jika ada kiriman data POST
try {
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception('Koneksi database tidak tersedia.');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // 1. Buat Flash Sale
        if (isset($_POST['save_sale'])) {
            $namaEvent = trim($_POST['nama_event'] ?? '');
            $waktuMulai = trim($_POST['waktu_mulai'] ?? '');
            $waktuSelesai = trim($_POST['waktu_selesai'] ?? '');

            if ($namaEvent === '' || $waktuMulai === '' || $waktuSelesai === '') {
                $message = 'Data Flash Sale belum lengkap.';
                $messageType = 'error';
            } else {
                $waktuMulai = str_replace('T', ' ', $waktuMulai);
                $waktuSelesai = str_replace('T', ' ', $waktuSelesai);

                if (strlen($waktuMulai) === 16) $waktuMulai .= ':00';
                if (strlen($waktuSelesai) === 16) $waktuSelesai .= ':00';

                if ($waktuSelesai <= $waktuMulai) {
                    $message = 'Waktu selesai harus lebih besar dari waktu mulai.';
                    $messageType = 'error';
                } else {
                    $stmt = $conn->prepare("INSERT INTO flash_sales (nama_event, waktu_mulai, waktu_selesai) VALUES (?, ?, ?)");
                    $stmt->bind_param('sss', $namaEvent, $waktuMulai, $waktuSelesai);
                    $stmt->execute();
                    $stmt->close();

                    $message = 'Flash Sale berhasil dibuat.';
                    $messageType = 'success';
                }
            }
        }

        // 2. Tambah Produk ke Flash Sale
        if (isset($_POST['add_item'])) {
            $idFlashSale = (int) ($_POST['id_flash_sale'] ?? 0);
            $idProduk = (int) ($_POST['id_produk'] ?? 0);
            $hargaSale = (float) ($_POST['harga_sale'] ?? 0);

            if ($idFlashSale <= 0 || $idProduk <= 0 || $hargaSale <= 0) {
                $message = 'Event, produk, dan harga sale wajib diisi.';
                $messageType = 'error';
            } else {
                // Cek Event
                $stmt = $conn->prepare("SELECT id_flash_sale FROM flash_sales WHERE id_flash_sale = ? LIMIT 1");
                $stmt->bind_param('i', $idFlashSale);
                $stmt->execute();
                $eventExists = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                // Cek Produk
                $stmt = $conn->prepare("SELECT id_produk, nama, harga, stok FROM produk WHERE id_produk = ? LIMIT 1");
                $stmt->bind_param('i', $idProduk);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if (!$eventExists) {
                    $message = 'Event Flash Sale tidak ditemukan.';
                    $messageType = 'error';
                } elseif (!$product) {
                    $message = 'Produk tidak ditemukan.';
                    $messageType = 'error';
                } else {
                    // Cek Duplikat Produk di Event
                    $stmt = $conn->prepare("SELECT id_item FROM flash_sale_items WHERE id_flash_sale = ? AND id_produk = ? LIMIT 1");
                    $stmt->bind_param('ii', $idFlashSale, $idProduk);
                    $stmt->execute();
                    $duplicate = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    if ($duplicate) {
                        $message = 'Produk tersebut sudah ada di Flash Sale.';
                        $messageType = 'error';
                    } else {
                        // Simpan Item
                        $stmt = $conn->prepare("INSERT INTO flash_sale_items (id_flash_sale, id_produk, harga_sale) VALUES (?, ?, ?)");
                        $stmt->bind_param('iid', $idFlashSale, $idProduk, $hargaSale);
                        $stmt->execute();
                        $stmt->close();

                        $message = 'Produk berhasil dimasukkan ke Flash Sale.';
                        $messageType = 'success';
                    }
                }
            }
        }

        // 3. Hapus Produk Flash Sale
        if (isset($_POST['delete_item'])) {
            $idItem = (int) ($_POST['id_item'] ?? 0);
            if ($idItem > 0) {
                $stmt = $conn->prepare("DELETE FROM flash_sale_items WHERE id_item = ?");
                $stmt->bind_param('i', $idItem);
                $stmt->execute();
                $stmt->close();

                $message = 'Produk berhasil dihapus dari Flash Sale.';
                $messageType = 'success';
            }
        }

        // 4. Hapus Event Flash Sale
        if (isset($_POST['delete_sale'])) {
            $idFlashSale = (int) ($_POST['id_flash_sale'] ?? 0);
            if ($idFlashSale > 0) {
                // Hapus relasi item dulu
                $stmt = $conn->prepare("DELETE FROM flash_sale_items WHERE id_flash_sale = ?");
                $stmt->bind_param('i', $idFlashSale);
                $stmt->execute();
                $stmt->close();

                // Hapus event utama
                $stmt = $conn->prepare("DELETE FROM flash_sales WHERE id_flash_sale = ?");
                $stmt->bind_param('i', $idFlashSale);
                $stmt->execute();
                $stmt->close();

                $message = 'Flash Sale berhasil dihapus.';
                $messageType = 'success';
            }
        }
    }

    // Ambil Data untuk ditampilkan ke Tabel/Form
    $sales = [];
    $result = $conn->query("
        SELECT fs.*, COUNT(fsi.id_item) AS jumlah_produk 
        FROM flash_sales fs 
        LEFT JOIN flash_sale_items fsi ON fsi.id_flash_sale = fs.id_flash_sale 
        GROUP BY fs.id_flash_sale 
        ORDER BY fs.id_flash_sale DESC
    ");
    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }

    $products = [];
    $result = $conn->query("SELECT id_produk, nama, harga, stok FROM produk ORDER BY nama ASC");
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $items = [];
    $result = $conn->query("
        SELECT fsi.*, p.nama AS produk, p.harga AS harga_normal, fs.nama_event, fs.waktu_mulai, fs.waktu_selesai 
        FROM flash_sale_items fsi 
        INNER JOIN produk p ON p.id_produk = fsi.id_produk 
        INNER JOIN flash_sales fs ON fs.id_flash_sale = fsi.id_flash_sale 
        ORDER BY fsi.id_item DESC
    ");
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

} catch (Throwable $e) {
    $message = $e->getMessage();
    $messageType = 'error';
    $sales = [];
    $products = [];
    $items = [];
}

// Header Tampilan Admin
require __DIR__ . '/includes/header.php';
?>

<div class="card">
    <h2>Flash Sale</h2>
    <p style="margin-top:5px;color:#718078;font-size:13px;">
        Kelola event Flash Sale dan produk yang sedang mendapatkan harga khusus.
    </p>

    <?php if ($message !== ''): ?>
        <div style="margin-top:15px; padding:12px 15px; border-radius:10px; background:<?= $messageType === 'success' ? '#ecfdf5' : '#fef2f2' ?>; color:<?= $messageType === 'success' ? '#047857' : '#b91c1c' ?>; border:1px solid <?= $messageType === 'success' ? '#a7f3d0' : '#fecaca' ?>;">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Form Buat Event -->
<div class="card" style="margin-top:15px;">
    <h2>Buat Flash Sale</h2>
    <form method="POST">
        <div class="form-grid">
            <div class="field">
                <label>Nama Event</label>
                <input type="text" name="nama_event" placeholder="Contoh: Flash Sale Kemerdekaan" required>
            </div>
            <div class="field">
                <label>Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" required>
            </div>
            <div class="field">
                <label>Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" required>
            </div>
        </div>
        <button type="submit" class="btn primary" name="save_sale">Buat Flash Sale</button>
    </form>
</div>

<!-- Form Tambah Produk -->
<div class="card" style="margin-top:15px;">
    <h2>Tambah Produk ke Flash Sale</h2>

    <?php if (empty($sales)): ?>
        <div style="padding:15px; background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; border-radius:10px; margin-top:15px;">
            Belum ada event Flash Sale. Buat event terlebih dahulu.
        </div>
    <?php elseif (empty($products)): ?>
        <div style="padding:15px; background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; border-radius:10px; margin-top:15px;">
            Belum ada produk di database.
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="form-grid">
                <div class="field">
                    <label>Event</label>
                    <select name="id_flash_sale" required>
                        <?php foreach ($sales as $sale): ?>
                            <option value="<?= (int) $sale['id_flash_sale'] ?>">
                                <?= htmlspecialchars($sale['nama_event'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Produk</label>
                    <select name="id_produk" required>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= (int) $product['id_produk'] ?>">
                                <?= htmlspecialchars($product['nama'], ENT_QUOTES, 'UTF-8') ?> — Rp <?= number_format((float) $product['harga'], 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Harga Flash Sale</label>
                    <input type="number" name="harga_sale" min="0" step="1" placeholder="Contoh: 99000" required>
                </div>
            </div>
            <button type="submit" class="btn primary" name="add_item">Tambahkan Produk</button>
        </form>
    <?php endif; ?>
</div>

<!-- Tabel Daftar Event -->
<div class="card" style="margin-top:15px;">
    <h2>Event Flash Sale</h2>
    <?php if (empty($sales)): ?>
        <p style="margin-top:10px;color:#718078;">Belum ada event Flash Sale.</p>
    <?php else: ?>
        <div style="overflow-x:auto;margin-top:15px;">
            <table>
                <tr>
                    <th>Event</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Produk</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= htmlspecialchars($sale['nama_event'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($sale['waktu_mulai'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($sale['waktu_selesai'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= (int) $sale['jumlah_produk'] ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Hapus event Flash Sale ini?');">
                                <input type="hidden" name="id_flash_sale" value="<?= (int) $sale['id_flash_sale'] ?>">
                                <button type="submit" class="btn danger" name="delete_sale">Hapus Event</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Tabel Daftar Produk Flash Sale -->
<div class="card" style="margin-top:15px;">
    <h2>Produk dalam Flash Sale</h2>
    <?php if (empty($items)): ?>
        <p style="margin-top:10px;color:#718078;">Belum ada produk yang dimasukkan ke Flash Sale.</p>
    <?php else: ?>
        <div style="overflow-x:auto;margin-top:15px;">
            <table>
                <tr>
                    <th>Event</th>
                    <th>Produk</th>
                    <th>Harga Normal</th>
                    <th>Harga Sale</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama_event'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($item['produk'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>Rp <?= number_format((float) $item['harga_normal'], 0, ',', '.') ?></td>
                        <td>
                            <strong style="color:#059669;">
                                Rp <?= number_format((float) $item['harga_sale'], 0, ',', '.') ?>
                            </strong>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Hapus produk dari Flash Sale?');">
                                <input type="hidden" name="id_item" value="<?= (int) $item['id_item'] ?>">
                                <button type="submit" class="btn danger" name="delete_item">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>