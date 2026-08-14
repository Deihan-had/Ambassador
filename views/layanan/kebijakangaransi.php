<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kebijakan Garansi — Ambassador</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

        .policy-card {
            transition: all .2s ease;
        }

        .policy-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <!-- =====================================================
         NAVBAR
    ====================================================== -->

    <header class="
        sticky top-0 z-50
        bg-white
        border-b border-slate-200">

        <div class="
            max-w-7xl
            mx-auto
            px-4 sm:px-6 lg:px-8">

            <div class="
                h-16
                flex
                items-center
                justify-between">

                <!-- Logo -->
                <a href="/index.php">
                    <div class="flex items-center gap-2 cursor-pointer">

                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">

                            <i class="fa-solid fa-bag-shopping text-xl"></i>

                        </div>

                        <div>

                            <span
                                class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-700 to-slate-900 bg-clip-text text-transparent">

                                Ambas<span class="text-emerald-600">sador</span>

                            </span>

                            <span
                                class="block text-[10px] text-slate-400 font-semibold -mt-1 tracking-widest uppercase">

                                Kebijakan Garansi

                            </span>

                        </div>

                    </div>
                </a>

                <!-- BACK -->
                <a href="/index.php"
                    class="
                    inline-flex
                    items-center
                    gap-2
                    text-sm
                    font-semibold
                    text-slate-600
                    hover:text-emerald-600
                    transition">

                    <i class="fa-solid fa-arrow-left"></i>

                    Kembali ke Toko

                </a>

            </div>

        </div>

    </header>


    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="
        bg-white
        border-b border-slate-200">

        <div class="
            max-w-5xl
            mx-auto
            px-4
            py-14
            text-center">

            <!-- Label -->

            <div class="
                inline-flex
                items-center
                gap-2
                px-3 py-1.5
                rounded-full
                bg-emerald-50
                border border-emerald-100
                text-emerald-700
                text-xs
                font-bold
                mb-5">

                <i class="fa-solid fa-shield-halved"></i>

                Kebijakan Ambassador

            </div>


            <!-- Title -->

            <h1 class="
                text-3xl
                sm:text-4xl
                lg:text-5xl
                font-black
                tracking-tight
                text-slate-900
                mb-4">

                Kebijakan Garansi

            </h1>


            <!-- Description -->

            <p class="
                max-w-2xl
                mx-auto
                text-sm
                sm:text-base
                leading-relaxed
                text-slate-500">

                Informasi mengenai perlindungan garansi,
                ketentuan pengajuan, dan proses penanganan
                produk yang mengalami masalah.

            </p>

        </div>

    </section>


    <!-- =====================================================
         CONTENT
    ====================================================== -->

    <main class="
        max-w-5xl
        mx-auto
        px-4 sm:px-6 lg:px-8
        py-12">

        <!-- INTRO -->

        <section class="
            bg-emerald-50
            border border-emerald-100
            rounded-3xl
            p-6 sm:p-8
            mb-8">

            <div class="
                flex
                items-start
                gap-4">

                <div class="
                    w-11 h-11
                    rounded-xl
                    bg-emerald-500
                    text-white
                    flex
                    items-center
                    justify-center
                    shrink-0">

                    <i class="fa-solid fa-shield-heart"></i>

                </div>

                <div>

                    <h2 class="
                        font-black
                        text-slate-900
                        mb-2">

                        Perlindungan Produk

                    </h2>

                    <p class="
                        text-sm
                        leading-relaxed
                        text-slate-600">

                        Ambassador berkomitmen memberikan pengalaman
                        belanja yang aman dan nyaman. Setiap pengajuan
                        garansi akan diperiksa berdasarkan kondisi produk
                        dan ketentuan garansi yang berlaku pada produk
                        tersebut.

                    </p>

                </div>

            </div>

        </section>


        <!-- =================================================
             1. APA ITU GARANSI
        ================================================== -->

        <section class="
            policy-card
            bg-white
            border border-slate-200
            rounded-3xl
            p-6 sm:p-8
            shadow-sm
            mb-5">

            <div class="flex gap-5">

                <div class="
                    w-11 h-11
                    rounded-xl
                    bg-slate-100
                    text-slate-700
                    flex items-center justify-center
                    shrink-0">

                    <i class="fa-solid fa-circle-info"></i>

                </div>

                <div>

                    <h2 class="
                        text-xl
                        font-black
                        text-slate-900
                        mb-3">

                        1. Apa itu garansi?

                    </h2>

                    <p class="
                        text-sm
                        text-slate-500
                        leading-relaxed">

                        Garansi merupakan bentuk perlindungan terhadap
                        produk yang mengalami masalah tertentu sesuai
                        dengan ketentuan garansi yang berlaku.

                        <br><br>

                        Masa dan cakupan garansi dapat berbeda pada
                        setiap produk. Silakan periksa informasi garansi
                        yang tercantum pada halaman produk sebelum
                        melakukan pembelian.

                    </p>

                </div>

            </div>

        </section>


        <!-- =================================================
             2. KONDISI YANG DAPAT DIAJUKAN
        ================================================== -->

        <section class="
            policy-card
            bg-white
            border border-slate-200
            rounded-3xl
            p-6 sm:p-8
            shadow-sm
            mb-5">

            <div class="flex gap-5">

                <div class="
                    w-11 h-11
                    rounded-xl
                    bg-emerald-50
                    text-emerald-600
                    flex items-center justify-center
                    shrink-0">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div class="w-full">

                    <h2 class="
                        text-xl
                        font-black
                        text-slate-900
                        mb-4">

                        2. Kondisi yang dapat diajukan

                    </h2>

                    <div class="space-y-3">

                        <div class="
                            flex
                            items-start
                            gap-3
                            p-4
                            rounded-xl
                            bg-slate-50">

                            <i class="
                                fa-solid
                                fa-check
                                text-emerald-500
                                mt-1">
                            </i>

                            <p class="
                                text-sm
                                text-slate-600">

                                Produk mengalami kerusakan yang
                                termasuk dalam cakupan garansi.

                            </p>

                        </div>


                        <div class="
                            flex
                            items-start
                            gap-3
                            p-4
                            rounded-xl
                            bg-slate-50">

                            <i class="
                                fa-solid
                                fa-check
                                text-emerald-500
                                mt-1">
                            </i>

                            <p class="
                                text-sm
                                text-slate-600">

                                Produk tidak berfungsi sebagaimana
                                mestinya karena masalah yang
                                tercakup dalam garansi.

                            </p>

                        </div>


                        <div class="
                            flex
                            items-start
                            gap-3
                            p-4
                            rounded-xl
                            bg-slate-50">

                            <i class="
                                fa-solid
                                fa-check
                                text-emerald-500
                                mt-1">
                            </i>

                            <p class="
                                text-sm
                                text-slate-600">

                                Pengajuan dilakukan selama masa
                                garansi produk masih berlaku.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =================================================
             3. TIDAK DICAKUP
        ================================================== -->

        <section class="
            policy-card
            bg-white
            border border-slate-200
            rounded-3xl
            p-6 sm:p-8
            shadow-sm
            mb-5">

            <div class="flex gap-5">

                <div class="
                    w-11 h-11
                    rounded-xl
                    bg-red-50
                    text-red-500
                    flex items-center justify-center
                    shrink-0">

                    <i class="fa-solid fa-triangle-exclamation"></i>

                </div>

                <div class="w-full">

                    <h2 class="
                        text-xl
                        font-black
                        text-slate-900
                        mb-4">

                        3. Kondisi yang tidak termasuk garansi

                    </h2>

                    <div class="space-y-3">

                        <div class="
                            flex
                            items-start
                            gap-3
                            text-sm
                            text-slate-600">

                            <i class="
                                fa-solid
                                fa-xmark
                                text-red-500
                                mt-1">
                            </i>

                            Kerusakan akibat penggunaan yang tidak
                            sesuai dengan petunjuk produk.

                        </div>


                        <div class="
                            flex
                            items-start
                            gap-3
                            text-sm
                            text-slate-600">

                            <i class="
                                fa-solid
                                fa-xmark
                                text-red-500
                                mt-1">
                            </i>

                            Kerusakan akibat kecelakaan, benturan,
                            atau kelalaian pengguna.

                        </div>


                        <div class="
                            flex
                            items-start
                            gap-3
                            text-sm
                            text-slate-600">

                            <i class="
                                fa-solid
                                fa-xmark
                                text-red-500
                                mt-1">
                            </i>

                            Kerusakan akibat modifikasi atau
                            perubahan produk oleh pihak yang tidak
                            berwenang.

                        </div>


                        <div class="
                            flex
                            items-start
                            gap-3
                            text-sm
                            text-slate-600">

                            <i class="
                                fa-solid
                                fa-xmark
                                text-red-500
                                mt-1">
                            </i>

                            Kerusakan yang terjadi karena penggunaan
                            yang tidak sesuai dengan fungsi produk.

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =================================================
             4. CARA PENGAJUAN
        ================================================== -->

        <section class="
            policy-card
            bg-white
            border border-slate-200
            rounded-3xl
            p-6 sm:p-8
            shadow-sm
            mb-5">

            <div class="flex gap-5">

                <div class="
                    w-11 h-11
                    rounded-xl
                    bg-blue-50
                    text-blue-600
                    flex items-center justify-center
                    shrink-0">

                    <i class="fa-solid fa-file-circle-check"></i>

                </div>

                <div class="w-full">

                    <h2 class="
                        text-xl
                        font-black
                        text-slate-900
                        mb-5">

                        4. Cara mengajukan garansi

                    </h2>

                    <div class="space-y-4">

                        <!-- STEP 01 -->

                        <div class="flex gap-4">

                            <div class="
                                w-8 h-8
                                rounded-full
                                bg-blue-50
                                text-blue-600
                                flex items-center justify-center
                                font-black
                                text-xs
                                shrink-0">

                                01

                            </div>

                            <div>

                                <h3 class="
                                    font-bold
                                    text-slate-900">

                                    Siapkan informasi pesanan

                                </h3>

                                <p class="
                                    text-sm
                                    text-slate-500
                                    mt-1">

                                    Siapkan nomor pesanan atau invoice
                                    yang berkaitan dengan produk.

                                </p>

                            </div>

                        </div>


                        <!-- STEP 02 -->

                        <div class="flex gap-4">

                            <div class="
                                w-8 h-8
                                rounded-full
                                bg-blue-50
                                text-blue-600
                                flex items-center justify-center
                                font-black
                                text-xs
                                shrink-0">

                                02

                            </div>

                            <div>

                                <h3 class="
                                    font-bold
                                    text-slate-900">

                                    Jelaskan masalah produk

                                </h3>

                                <p class="
                                    text-sm
                                    text-slate-500
                                    mt-1">

                                    Berikan penjelasan mengenai masalah
                                    yang terjadi pada produk.

                                </p>

                            </div>

                        </div>


                        <!-- STEP 03 -->

                        <div class="flex gap-4">

                            <div class="
                                w-8 h-8
                                rounded-full
                                bg-blue-50
                                text-blue-600
                                flex items-center justify-center
                                font-black
                                text-xs
                                shrink-0">

                                03

                            </div>

                            <div>

                                <h3 class="
                                    font-bold
                                    text-slate-900">

                                    Sertakan bukti yang diperlukan

                                </h3>

                                <p class="
                                    text-sm
                                    text-slate-500
                                    mt-1">

                                    Sertakan foto atau video kondisi
                                    produk apabila diperlukan untuk
                                    proses pemeriksaan.

                                </p>

                            </div>

                        </div>


                        <!-- STEP 04 -->

                        <div class="flex gap-4">

                            <div class="
                                w-8 h-8
                                rounded-full
                                bg-blue-50
                                text-blue-600
                                flex items-center justify-center
                                font-black
                                text-xs
                                shrink-0">

                                04

                            </div>

                            <div>

                                <h3 class="
                                    font-bold
                                    text-slate-900">

                                    Tunggu proses pemeriksaan

                                </h3>

                                <p class="
                                    text-sm
                                    text-slate-500
                                    mt-1">

                                    Tim akan melakukan pemeriksaan
                                    berdasarkan informasi dan kondisi
                                    produk yang diajukan.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =================================================
             5. HASIL PEMERIKSAAN
        ================================================== -->

        <section class="
            policy-card
            bg-white
            border border-slate-200
            rounded-3xl
            p-6 sm:p-8
            shadow-sm
            mb-5">

            <div class="flex gap-5">

                <div class="
                    w-11 h-11
                    rounded-xl
                    bg-purple-50
                    text-purple-600
                    flex items-center justify-center
                    shrink-0">

                    <i class="fa-solid fa-clipboard-check"></i>

                </div>

                <div>

                    <h2 class="
                        text-xl
                        font-black
                        text-slate-900
                        mb-3">

                        5. Hasil pemeriksaan

                    </h2>

                    <p class="
                        text-sm
                        text-slate-500
                        leading-relaxed">

                        Setelah pemeriksaan selesai, pengajuan akan
                        ditentukan berdasarkan kondisi produk dan
                        ketentuan garansi yang berlaku.

                        <br><br>

                        Jika pengajuan memenuhi ketentuan, solusi
                        garansi akan diberikan sesuai kebijakan produk.

                    </p>

                </div>

            </div>

        </section>


        <!-- =================================================
             IMPORTANT NOTE
        ================================================== -->

        <section class="
            bg-amber-50
            border border-amber-100
            rounded-3xl
            p-6 sm:p-8
            mt-8">

            <div class="
                flex
                items-start
                gap-4">

                <div class="
                    w-10 h-10
                    rounded-xl
                    bg-amber-100
                    text-amber-600
                    flex items-center justify-center
                    shrink-0">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div>

                    <h2 class="
                        font-black
                        text-amber-900
                        mb-2">

                        Perhatian

                    </h2>

                    <p class="
                        text-sm
                        text-amber-800
                        leading-relaxed">

                        Ketentuan garansi dapat berbeda untuk setiap
                        produk. Pastikan membaca informasi garansi yang
                        tercantum pada halaman produk sebelum melakukan
                        pembelian.

                    </p>

                </div>

            </div>

        </section>


        <!-- =================================================
             CTA
        ================================================== -->

        <section class="text-center mt-14">

            <div class="
                w-12 h-12
                mx-auto
                rounded-2xl
                bg-emerald-50
                text-emerald-600
                flex items-center justify-center
                mb-4">

                <i class="fa-solid fa-headset"></i>

            </div>


            <h2 class="
                text-2xl
                font-black
                text-slate-900
                mb-2">

                Masih membutuhkan bantuan?

            </h2>


            <p class="
                text-sm
                text-slate-500
                mb-6">

                Kunjungi Pusat Bantuan untuk melihat panduan
                dan informasi lainnya.

            </p>


            <a href="/views/pusatbantuan/help.php"
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
                    transition">

                <i class="fa-solid fa-circle-question"></i>

                Pusat Bantuan

            </a>

        </section>

    </main>


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer class="
        bg-white
        border-t border-slate-200
        mt-16">

        <div class="
            max-w-7xl
            mx-auto
            px-4 sm:px-6 lg:px-8
            py-8">

            <div class="
                flex
                flex-col
                sm:flex-row
                items-center
                justify-between
                gap-4">


                <div>

                    <div class="
                        font-black
                        text-slate-900">

                        Ambassador

                    </div>

                    <p class="
                        text-[10px]
                        uppercase
                        tracking-[0.14em]
                        text-slate-400
                        mt-1">

                        Premium Marketplace

                    </p>

                </div>
                <div class="
                    text-xs
                    text-slate-400">

                    © 2026 Ambassador. All rights reserved.

                </div>

            </div>

        </div>

    </footer>


</body>