<?php

admin_required();

$flash = pull_flash();

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
| Semua halaman admin cukup menentukan:
| $page = 'produk';
| $page = 'kategori';
| $page = 'pengaturan';
| dan seterusnya.
*/
$page = $page ?? '';

?>
<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        <?= e($title ?? 'Admin Ambassador') ?>
    </title>

    <link
        rel="stylesheet"
        href="assets/admin.css"
    >

</head>

<body>

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="side">

        <div class="brand">
            AMBASSADOR
            <small>ADMIN</small>
        </div>

        <nav>

            <!-- DASHBOARD -->
            <a
                href="index.php"
                class="<?= $page === 'dashboard' ? 'active' : '' ?>"
            >
                Dashboard
            </a>


            <!-- PRODUK -->
            <a
                href="produk.php"
                class="<?= $page === 'produk' ? 'active' : '' ?>"
            >
                Produk
            </a>


            <!-- KATEGORI -->
            <a
                href="kategori.php"
                class="<?= $page === 'kategori' ? 'active' : '' ?>"
            >
                Kategori
            </a>


            <!-- PESANAN -->
            <a
                href="pesanan.php"
                class="<?= $page === 'pesanan' ? 'active' : '' ?>"
            >
                Pesanan
            </a>


            <!-- PELANGGAN -->
            <a
                href="pelanggan.php"
                class="<?= $page === 'pelanggan' ? 'active' : '' ?>"
            >
                Pelanggan
            </a>


            <!-- FLASH SALE -->
            <a
                href="promo.php"
                class="<?= $page === 'promo' ? 'active' : '' ?>"
            >
                Flash Sale
            </a>


            <!-- PENGATURAN -->
            <a
                href="pengaturan.php"
                class="<?= $page === 'pengaturan' ? 'active' : '' ?>"
            >
                Pengaturan
            </a>


            <!-- TOKO -->
            <a href="../index.php">
                Lihat Toko
            </a>


            <!-- LOGOUT -->
            <a href="logout.php">
                Keluar
            </a>

        </nav>

    </aside>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="main">

        <header class="top">

            <div>

                <small>
                    Admin Panel
                </small>

                <h1>
                    <?= e($title ?? 'Dashboard') ?>
                </h1>

            </div>

            <span class="user">
                👤 <?= e($_SESSION['username'] ?? 'Admin') ?>
            </span>

        </header>


        <!-- FLASH MESSAGE -->

        <?php if ($flash): ?>

            <div
                class="flash <?= $flash['type'] === 'error' ? 'error' : 'success' ?>"
            >
                <?= e($flash['message']) ?>
            </div>

        <?php endif; ?>