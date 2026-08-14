<?php

require_once "config/database.php";

$database = new Database();
$db = $database->getConnection();

$order = null;
$orderDetails = array();
$error = "";

// cek ada input nomor order dari user apa nggak
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["order"])) {

    $orderNumber = trim($_GET["order"]);

    if ($orderNumber == "") {

        $error = "Masukkan nomor pesanan atau invoice terlebih dahulu.";

    } else {

        // ambil data pesanan nya dulu
        $query = "SELECT id_order, id_users, total_harga, metode_pembayaran, status_pembayaran, created_at
                  FROM orders WHERE id_order = ? LIMIT 1";

        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $orderNumber);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $order = mysqli_fetch_assoc($result);

        if (!$order) {

            $error = "Pesanan dengan nomor \"$orderNumber\" tidak ditemukan.";

        } else {

            // kalo ketemu, ambil detail produk yang dibeli
            $detailQuery = "SELECT od.id_detail, od.id_produk, od.jumlah, od.harga_satuan, p.nama, p.foto
                             FROM order_details od
                             LEFT JOIN produk p ON od.id_produk = p.id_produk
                             WHERE od.id_order = ?";

            $detailStmt = mysqli_prepare($db, $detailQuery);
            mysqli_stmt_bind_param($detailStmt, "s", $order["id_order"]);
            mysqli_stmt_execute($detailStmt);
            $detailResult = mysqli_stmt_get_result($detailStmt);

            $orderDetails = array();
            while ($row = mysqli_fetch_assoc($detailResult)) {
                $orderDetails[] = $row;
            }
        }
    }
}


// nentuin status pesanan buat ditampilin
$status = isset($order["status_pembayaran"]) ? $order["status_pembayaran"] : "";

$statusText = "Menunggu Pembayaran";
$statusClass = "bg-yellow-50 text-yellow-700";
$statusDot = "bg-yellow-500";

if ($status == "paid") {

    $statusText = "Pesanan Diproses";
    $statusClass = "bg-blue-50 text-blue-700";
    $statusDot = "bg-blue-500";

} elseif ($status == "completed") {

    $statusText = "Pesanan Selesai";
    $statusClass = "bg-emerald-50 text-emerald-700";
    $statusDot = "bg-emerald-500";

} elseif ($status == "failed") {

    $statusText = "Pembayaran Gagal";
    $statusClass = "bg-red-50 text-red-700";
    $statusDot = "bg-red-500";
}


// format tanggal biar enak dibaca
$orderDate = "-";

if (!empty($order["created_at"])) {
    $orderDate = date("d F Y H:i", strtotime($order["created_at"]));
}


// format total harga jadi rupiah
$total = "-";

if (isset($order["total_harga"])) {
    $total = "Rp " . number_format($order["total_harga"], 0, ",", ".");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Melacak Pesanan — Ambassador</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .track-card {
            transition: .2s ease;
        }

        .track-card:hover {
            transform: translateY(-2px);
        }

        .timeline-line {
            position: absolute;
            left: 19px;
            top: 40px;
            bottom: -20px;
            width: 2px;
            background: #e2e8f0;
        }
        
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

<!-- HEADER -->
<header class="
    sticky top-0 z-50
    bg-white
    border-b border-slate-200
">

    <div class="
        max-w-7xl
        mx-auto
        px-4 sm:px-6 lg:px-8
    ">

        <div class="
            h-16
            flex
            items-center
            justify-between
        ">

            <a href="/index.php">
                <div class="flex items-center gap-2">
                    <div class="
                        w-10 h-10
                        rounded-xl
                        bg-emerald-600
                        flex items-center justify-center
                        text-white
                        shadow-lg
                        shadow-emerald-500/30
                    ">

                        <i class="fa-solid fa-bag-shopping text-xl"></i>

                    </div>
                    <div>
                        <span class="
                            text-xl
                            font-extrabold
                            tracking-tight
                            bg-gradient-to-r
                            from-emerald-700
                            to-slate-900
                            bg-clip-text
                            text-transparent
                        ">
                            Ambas<span class="text-emerald-600">sador</span>
                        </span>

                        <span class="
                            block
                            text-[10px]
                            text-slate-400
                            font-semibold
                            -mt-1
                            tracking-widest
                            uppercase
                        ">
                            Melacak Pesanan
                        </span>

                    </div>

                </div>

            </a>

            <a
                href="/index.php"
                class="
                    flex
                    items-center
                    gap-2
                    text-sm
                    font-semibold
                    text-slate-600
                    hover:text-emerald-600
                    transition
                "
            >

                <i class="fa-solid fa-arrow-left"></i>

                Kembali ke Toko

            </a>
        </div>
    </div>
</header>


<!-- HERO -->
<section class="
    bg-slate-900
    text-white
">

    <div class="
        max-w-5xl
        mx-auto
        px-4
        py-14
        text-center
    ">

        <div class="
            inline-flex
            items-center
            gap-2
            px-3 py-1.5
            rounded-full
            bg-white/10
            border border-white/10
            text-xs
            font-semibold
            text-slate-300
            mb-5
        ">

            <i class="
                fa-solid
                fa-location-dot
                text-emerald-400
            "></i>

            Order Tracking

        </div>

        <h1 class="
            text-3xl
            sm:text-4xl
            lg:text-5xl
            font-black
            tracking-tight
            mb-4
        ">
            Melacak Pesanan
        </h1>

        <p class="
            max-w-2xl
            mx-auto
            text-sm
            sm:text-base
            leading-relaxed
            text-slate-400
        ">

            Masukkan nomor pesanan atau invoice untuk
            melihat status dan perkembangan pesananmu.

        </p>

    </div>

</section>


<!-- MAIN -->
<main class="
    max-w-5xl
    mx-auto
    px-4 sm:px-6 lg:px-8
    py-12
">


<!-- FORM -->

<section class="
    bg-white
    border border-slate-200
    rounded-3xl
    shadow-sm
    p-6 sm:p-8
    mb-8
">

    <div class="max-w-2xl mx-auto">

        <div class="text-center mb-6">

            <div class="
                w-12 h-12
                mx-auto
                rounded-2xl
                bg-emerald-50
                text-emerald-600
                flex items-center justify-center
                mb-4
            ">

                <i class="fa-solid fa-magnifying-glass"></i>

            </div>


            <h2 class="
                text-xl
                font-black
                text-slate-900
            ">
                Cari Pesananmu
            </h2>


            <p class="
                text-sm
                text-slate-500
                mt-2
            ">
                Gunakan nomor pesanan atau nomor invoice.
            </p>

        </div>
        <form method="GET">

            <label
                for="order"
                class="
                    block
                    text-sm
                    font-bold
                    text-slate-700
                    mb-2
                "
            >
                Nomor Pesanan / Invoice
            </label>

            <div class="
                flex
                flex-col
                sm:flex-row
                gap-3
            ">
                <div class="relative flex-1">

                    <i class="
                        fa-solid
                        fa-receipt
                        absolute
                        left-4
                        top-1/2
                        -translate-y-1/2
                        text-slate-400
                    "></i>

                    <input
                        type="text"
                        name="order"
                        id="order"
                        value="<?= htmlspecialchars($_GET["order"] ?? "") ?>"
                        placeholder="Contoh: INV-20260810-001"
                        autocomplete="off"
                        class="
                            w-full
                            h-12
                            pl-11
                            pr-4
                            rounded-xl
                            border border-slate-200
                            bg-slate-50
                            text-sm
                            outline-none
                            focus:bg-white
                            focus:border-emerald-500
                            focus:ring-4
                            focus:ring-emerald-500/10
                            transition
                        "
                    >

                </div>
                <button
                    type="submit"
                    class="
                        h-12
                        px-6
                        rounded-xl
                        bg-slate-900
                        hover:bg-emerald-600
                        text-white
                        text-sm
                        font-bold
                        transition
                        flex
                        items-center
                        justify-center
                        gap-2
                    "
                >

                    <i class="fa-solid fa-location-crosshairs"></i>

                    Lacak Pesanan

                </button>
            </div>
            <?php if ($error): ?>

                <p class="
                    mt-3
                    text-xs
                    text-red-500
                ">

                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>
        </form>
    </div>
</section>

<?php if ($order): ?>

<!-- HASIL PESANAN -->
<section>

<!-- SUMMARY -->
<div class="
    track-card
    bg-white
    border border-slate-200
    rounded-3xl
    shadow-sm
    p-6 sm:p-8
    mb-5
">
    <div class="
        flex
        flex-col
        sm:flex-row
        sm:items-center
        justify-between
        gap-5
    ">
        <div>
            <div class="
                text-xs
                uppercase
                tracking-widest
                font-bold
                text-slate-400
                mb-2
            ">
                Nomor Pesanan
            </div>
            <div class="
                text-xl
                font-black
                text-slate-900
            ">

                <?= htmlspecialchars($order["id_order"]) ?>
            </div>
        </div>

        <div class="
            inline-flex
            items-center
            gap-2
            px-4 py-2
            rounded-full
            <?= $statusClass ?>
            text-xs
            font-bold
            self-start
            sm:self-auto
        ">
            <span class="
                w-2 h-2
                rounded-full
                <?= $statusDot ?>
            "></span>

            <?= $statusText ?>
        </div>
    </div>

    <div class="
        border-t
        border-slate-100
        mt-6
        pt-6
        grid
        grid-cols-2
        sm:grid-cols-3
        gap-5
    ">

        <div>
            <div class="
                text-[10px]
                uppercase
                tracking-wider
                font-bold
                text-slate-400
                mb-1
            ">
                Tanggal
            </div>

            <div class="
                text-sm
                font-bold
                text-slate-800
            ">
                <?= $orderDate ?>
            </div>
        </div>

        <div>
            <div class="
                text-[10px]
                uppercase
                tracking-wider
                font-bold
                text-slate-400
                mb-1
            ">
                Total
            </div>
            <div class="
                text-sm
                font-bold
                text-slate-800
            ">

                <?= $total ?>
            </div>
        </div>
        <div>
            <div class="
                text-[10px]
                uppercase
                tracking-wider
                font-bold
                text-slate-400
                mb-1
            ">
                Pembayaran
            </div>

            <div class="
                text-sm
                font-bold
                text-slate-800
            ">

                <?= htmlspecialchars(
                    $order["metode_pembayaran"] ?: "-"
                ) ?>

            </div>

        </div>

    </div>

</div>


<!-- PRODUK -->
<div class="
    bg-white
    border border-slate-200
    rounded-3xl
    shadow-sm
    p-6 sm:p-8
    mb-5
">

    <h2 class="
        text-xl
        font-black
        text-slate-900
        mb-5
    ">
        Detail Pesanan
    </h2>

    <div class="space-y-4">

        <?php foreach ($orderDetails as $item): ?>

            <div class="
                flex
                items-center
                gap-4
                border-b
                border-slate-100
                pb-4
                last:border-0
            ">


                <div class="
                    w-16
                    h-16
                    rounded-xl
                    bg-slate-100
                    overflow-hidden
                    shrink-0
                ">

                    <?php if (!empty($item["foto"])): ?>

                        <img
                            src="<?= htmlspecialchars($item["foto"]) ?>"
                            class="
                                w-full
                                h-full
                                object-cover
                            "
                        >

                    <?php else: ?>

                        <div class="
                            w-full
                            h-full
                            flex
                            items-center
                            justify-center
                            text-slate-400
                        ">

                            <i class="fa-solid fa-image"></i>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="flex-1">

                    <div class="
                        font-bold
                        text-slate-900
                    ">

                        <?= htmlspecialchars(
                            $item["nama"] ?? "Produk"
                        ) ?>

                    </div>


                    <div class="
                        text-xs
                        text-slate-500
                        mt-1
                    ">

                        <?= (int)$item["jumlah"] ?> ×
                        Rp <?= number_format(
                            $item["harga_satuan"],
                            0,
                            ",",
                            "."
                        ) ?>

                    </div>

                </div>


                <div class="
                    text-sm
                    font-bold
                    text-slate-900
                ">

                    Rp <?= number_format(
                        $item["harga_satuan"] * $item["jumlah"],
                        0,
                        ",",
                        "."
                    ) ?>

                </div>

            </div>

        <?php endforeach; ?>

        <?php if (!$orderDetails): ?>

            <p class="
                text-sm
                text-slate-500
            ">
                Detail produk belum tersedia.
            </p>

        <?php endif; ?>
    </div>
</div>

<!-- TIMELINE -->

<div class="
    bg-white
    border border-slate-200
    rounded-3xl
    shadow-sm
    p-6 sm:p-8
">

    <div class="mb-7">

        <h2 class="
            text-xl
            font-black
            text-slate-900
        ">
            Status Pesanan
        </h2>

        <p class="
            text-sm
            text-slate-500
            mt-1
        ">
            Status berdasarkan informasi pesanan di database.
        </p>

    </div>


    <div>


        <!-- DIBUAT -->

        <div class="
            relative
            flex
            gap-4
            pb-8
        ">

            <div class="
                relative
                z-10
                w-10 h-10
                rounded-full
                bg-emerald-500
                text-white
                flex
                items-center
                justify-center
                shrink-0
            ">

                <i class="fa-solid fa-check text-sm"></i>

            </div>

            <div>
                <h3 class="
                    font-bold
                    text-slate-900
                ">
                    Pesanan Dibuat
                </h3>

                <p class="
                    text-xs
                    text-slate-500
                    mt-1
                ">
                    Pesanan berhasil dibuat pada
                    <?= $orderDate ?>.
                </p>
            </div>
            <div class="timeline-line"></div>
        </div>

        <?php if ($status === "paid" || $status === "completed"): ?>


            <!-- DIPROSES -->
            <div class="
                relative
                flex
                gap-4
                pb-8
            ">

                <div class="
                    relative
                    z-10
                    w-10 h-10
                    rounded-full
                    bg-emerald-500
                    text-white
                    flex
                    items-center
                    justify-center
                    shrink-0
                ">

                    <i class="fa-solid fa-box text-sm"></i>

                </div>
                <div>

                    <h3 class="
                        font-bold
                        text-slate-900
                    ">
                        Pesanan Diproses
                    </h3>

                    <p class="
                        text-xs
                        text-slate-500
                        mt-1
                    ">
                        Pembayaran berhasil dan pesanan dapat diproses.
                    </p>

                </div>
                <?php if ($status !== "completed"): ?>

                    <div class="timeline-line"></div>

                <?php endif; ?>

            </div>

        <?php endif; ?>

        <?php if ($status === "completed"): ?>

            <!-- SELESAI -->
            <div class="
                relative
                flex
                gap-4
            ">
                <div class="
                    relative
                    z-10
                    w-10 h-10
                    rounded-full
                    bg-emerald-500
                    text-white
                    flex
                    items-center
                    justify-center
                    shrink-0
                ">

                    <i class="fa-solid fa-house text-sm"></i>

                </div>

                <div>
                    <h3 class="
                        font-bold
                        text-slate-900
                    ">
                        Pesanan Selesai
                    </h3>

                    <p class="
                        text-xs
                        text-slate-500
                        mt-1
                    ">
                        Pesanan telah selesai.
                    </p>
                </div>
            </div>

        <?php endif; ?>

        <?php if ($status === "pending"): ?>


            <div class="
                relative
                flex
                gap-4
            ">

                <div class="
                    w-10 h-10
                    rounded-full
                    bg-yellow-100
                    text-yellow-600
                    flex
                    items-center
                    justify-center
                    shrink-0
                ">

                    <i class="fa-solid fa-clock text-sm"></i>

                </div>
                <div>

                    <h3 class="
                        font-bold
                        text-yellow-700
                    ">
                        Menunggu Pembayaran
                    </h3>

                    <p class="
                        text-xs
                        text-slate-500
                        mt-1
                    ">
                        Pesanan menunggu pembayaran.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($status === "failed"): ?>


            <div class="
                relative
                flex
                gap-4
            ">

                <div class="
                    w-10 h-10
                    rounded-full
                    bg-red-100
                    text-red-600
                    flex
                    items-center
                    justify-center
                    shrink-0
                ">

                    <i class="fa-solid fa-xmark text-sm"></i>

                </div>


                <div>

                    <h3 class="
                        font-bold
                        text-red-600
                    ">
                        Pembayaran Gagal
                    </h3>

                    <p class="
                        text-xs
                        text-slate-500
                        mt-1
                    ">
                        Pembayaran untuk pesanan ini gagal.
                    </p>

                </div>

            </div>

        <?php endif; ?>


    </div>

</div>
</section>

<?php endif; ?>

<!-- INFORMATION -->
<section class="
    mt-8
    grid
    md:grid-cols-2
    gap-5
">

    <div class="
        bg-white
        border border-slate-200
        rounded-3xl
        p-6
    ">

        <div class="
            w-10 h-10
            rounded-xl
            bg-blue-50
            text-blue-600
            flex
            items-center
            justify-center
            mb-4
        ">

            <i class="fa-solid fa-receipt"></i>

        </div>


        <h3 class="
            font-black
            text-slate-900
            mb-2
        ">
            Di mana nomor pesanan?
        </h3>


        <p class="
            text-sm
            text-slate-500
            leading-relaxed
        ">

            Nomor pesanan atau invoice dapat ditemukan
            pada detail pesanan setelah proses pembelian
            berhasil.

        </p>
    </div>

    <div class="
        bg-white
        border border-slate-200
        rounded-3xl
        p-6
    ">

        <div class="
            w-10 h-10
            rounded-xl
            bg-emerald-50
            text-emerald-600
            flex
            items-center
            justify-center
            mb-4
        ">

            <i class="fa-solid fa-headset"></i>

        </div>


        <h3 class="
            font-black
            text-slate-900
            mb-2
        ">
            Pesanan tidak ditemukan?
        </h3>

        <p class="
            text-sm
            text-slate-500
            leading-relaxed
        ">

            Pastikan nomor pesanan yang dimasukkan sudah
            benar. Jika masih mengalami masalah, silakan
            kunjungi Pusat Bantuan.

        </p>
    </div>
</section>

<!-- CTA -->
<section class="text-center mt-14">

    <a
        href="/pusatbantuan/help.html"
        class="
            inline-flex
            items-center
            gap-2
            px-6 py-3
            rounded-xl
            bg-slate-900
            hover:bg-emerald-600
            text-white
            text-sm
            font-bold
            transition
        "
    >
        <i class="fa-solid fa-circle-question"></i>
        Pusat Bantuan
    </a>
</section>
</main>


<!-- FOOTER -->
<footer class="
    bg-white
    border-t border-slate-200
    mt-16
">
    <div class="
        max-w-7xl
        mx-auto
        px-4 sm:px-6 lg:px-8
        py-8
    ">
        <div class="
            flex
            flex-col
            sm:flex-row
            items-center
            justify-between
            gap-4
        ">
            <div>

                <div class="
                    font-black
                    text-slate-900
                ">
                    Ambassador
                </div>

                <p class="
                    text-[10px]
                    uppercase
                    tracking-[0.14em]
                    text-slate-400
                    mt-1
                ">
                    Premium Marketplace
                </p>
            </div>
            <div class="
                text-xs
                text-slate-400
            ">
                © 2026 Ambassador. All rights reserved.
            </div>
        </div>
    </div>
</footer>
</body>