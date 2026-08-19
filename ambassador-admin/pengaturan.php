<?php

require_once __DIR__ . '/includes/bootstrap.php';

/*
|--------------------------------------------------------------------------
| AUTH ADMIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$page = 'pengaturan';
$title = 'Pengaturan Toko';

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
| config.php harus menyediakan koneksi mysqli di variabel $conn.
|--------------------------------------------------------------------------
*/

if (!isset($conn) || !($conn instanceof mysqli)) {
    die('Koneksi database tidak tersedia.');
}


/*
|--------------------------------------------------------------------------
| HELPER ESCAPE
|--------------------------------------------------------------------------
*/

function esc($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| SIMPAN PENGATURAN
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $storeName = trim(
        $_POST['store_name'] ?? ''
    );

    $storeTagline = trim(
        $_POST['store_tagline'] ?? ''
    );


    if ($storeName === '') {
        $storeName = 'Ambassador';
    }

    if ($storeTagline === '') {
        $storeTagline = 'Panel Admin Toko';
    }


    /*
    |----------------------------------------------------------------------
    | Pastikan tabel settings tersedia
    |----------------------------------------------------------------------
    */

    $conn->query("
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT NULL
        ) ENGINE=InnoDB
        DEFAULT CHARSET=utf8mb4
    ");


    /*
    |----------------------------------------------------------------------
    | Simpan nama toko
    |----------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        INSERT INTO settings (
            setting_key,
            setting_value
        )
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE
            setting_value = VALUES(setting_value)
    ");

    $key = 'store_name';

    $stmt->bind_param(
        'ss',
        $key,
        $storeName
    );

    $stmt->execute();


    /*
    |----------------------------------------------------------------------
    | Simpan tagline
    |----------------------------------------------------------------------
    */

    $key = 'store_tagline';

    $stmt->bind_param(
        'ss',
        $key,
        $storeTagline
    );

    $stmt->execute();

    $stmt->close();


    /*
    |----------------------------------------------------------------------
    | Redirect
    |----------------------------------------------------------------------
    */

    header(
        'Location: pengaturan.php?saved=1'
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| AMBIL PENGATURAN
|--------------------------------------------------------------------------
*/

$storeName = 'Ambassador';
$storeTagline = 'Panel Admin Toko';


$stmt = $conn->prepare("
    SELECT
        setting_key,
        setting_value
    FROM settings
    WHERE setting_key IN (
        'store_name',
        'store_tagline'
    )
");

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    if ($row['setting_key'] === 'store_name') {
        $storeName = $row['setting_value'];
    }

    if ($row['setting_key'] === 'store_tagline') {
        $storeTagline = $row['setting_value'];
    }
}

$stmt->close();


/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/header.php';

?>

<section class="card form-card">

    <div class="card-head">

        <div>
            <h2>Pengaturan Toko</h2>

            <p>
                Atur informasi dasar toko yang digunakan pada sistem.
            </p>
        </div>

    </div>


    <?php if (isset($_GET['saved'])): ?>

        <div class="flash success">
            Pengaturan berhasil disimpan.
        </div>

    <?php endif; ?>


    <div class="pad">

        <form
            method="post"
            action="pengaturan.php"
        >

            <div class="field">

                <label for="store_name">
                    Nama Toko
                </label>

                <input
                    type="text"
                    id="store_name"
                    name="store_name"
                    value="<?= esc($storeName) ?>"
                    placeholder="Contoh: Ambassador"
                    required
                >

            </div>


            <div class="field">

                <label for="store_tagline">
                    Tagline
                </label>

                <input
                    type="text"
                    id="store_tagline"
                    name="store_tagline"
                    value="<?= esc($storeTagline) ?>"
                    placeholder="Contoh: Panel Admin Toko"
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan Pengaturan
            </button>

        </form>

    </div>

</section>


<?php

require __DIR__ . '/includes/footer.php';

?>