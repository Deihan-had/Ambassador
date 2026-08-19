-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2026 at 12:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ambas_sador`
--

-- --------------------------------------------------------

--
-- Table structure for table `alamat`
--

CREATE TABLE `alamat` (
  `id_alamat` int(11) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `kota` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kode_pos` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biodata`
--

CREATE TABLE `biodata` (
  `id_bio` int(11) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `no_telephone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id_chat` bigint(20) NOT NULL,
  `sender_id` varchar(50) NOT NULL,
  `receiver_id` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_sales`
--

CREATE TABLE `flash_sales` (
  `id_flash_sale` int(11) NOT NULL,
  `nama_event` varchar(150) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `status` enum('draft','active','ended') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flash_sale_items`
--

CREATE TABLE `flash_sale_items` (
  `id_item` int(11) NOT NULL,
  `id_flash_sale` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `harga_flash` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama`) VALUES
(1, 'Kaos'),
(2, 'Jaket'),
(3, 'Topi'),
(4, 'Aksesoris'),
(5, 'Furnitture');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_otp`
--

CREATE TABLE `login_otp` (
  `id` int(11) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` varchar(50) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `id_alamat` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status_pembayaran` enum('pending','paid','failed','completed') DEFAULT 'pending',
  `status_pesanan` varchar(30) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id_detail` int(11) NOT NULL,
  `id_order` varchar(50) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `foto` varchar(255) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `kategori_id`, `nama`, `harga`, `foto`, `detail`, `stok`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ambassador Oversized Black T-Shirt', 120000.00, NULL, 'Kaos oversized Ambassador warna hitam.', 20, '2026-08-19 04:52:26', '2026-08-19 04:52:26'),
(2, 2, 'Ambassador Casual Jacket Black', 850000.00, NULL, 'Jaket casual Ambassador warna hitam.', 10, '2026-08-19 04:52:26', '2026-08-19 04:52:26'),
(3, 3, 'Ambassador Classic Baseball Cap', 99000.00, NULL, 'Topi baseball premium Ambassador.', 25, '2026-08-19 04:52:26', '2026-08-19 04:52:26'),
(4, 4, 'Ambassador Pixel Sunglasses Black', 499000.00, NULL, 'Kacamata hitam Ambassador.', 15, '2026-08-19 04:52:26', '2026-08-19 04:52:26'),
(5, 5, 'Hiasan Dinding', 9000000.00, '', 'Contoh', 12, '2026-08-19 07:27:48', '2026-08-19 07:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id_rewards` int(11) NOT NULL,
  `nama_rewards` varchar(100) NOT NULL,
  `poin_dibutuhkan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id_rewards`, `nama_rewards`, `poin_dibutuhkan`) VALUES
(1, 'Reward Belanja', 90);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'store_name', 'adad'),
(2, 'store_tagline', 'adad');

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `store_name` varchar(150) NOT NULL DEFAULT 'Ambassador',
  `tagline` varchar(255) NOT NULL DEFAULT 'Your Trusted Partner For Every Journey',
  `hero_desc` text DEFAULT NULL,
  `free_shipping_min` decimal(12,2) NOT NULL DEFAULT 250000.00,
  `shipping_estimate` varchar(255) NOT NULL DEFAULT '2–4 hari kerja, seluruh Indonesia',
  `admin_email` varchar(255) DEFAULT NULL,
  `qris_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `bca_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `mandiri_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `cod_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `store_name`, `tagline`, `hero_desc`, `free_shipping_min`, `shipping_estimate`, `admin_email`, `qris_enabled`, `bca_enabled`, `mandiri_enabled`, `cod_enabled`, `updated_at`) VALUES
(1, 'Ambassador', 'Your Trusted Partner For Every Journey', NULL, 250000.00, '2–4 hari kerja, seluruh Indonesia', NULL, 1, 1, 1, 1, '2026-08-18 14:12:04');

-- --------------------------------------------------------

--
-- Table structure for table `userrewards`
--

CREATE TABLE `userrewards` (
  `id_user_rewards` int(11) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `id_rewards` int(11) NOT NULL,
  `point_terkumpul` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `email`, `password`, `role`, `created_at`) VALUES
('USR-1786710583-130', 'deihan', NULL, '$2y$10$EOmmJFCd5LkaTVPB5130wekLSCiniW2Wmw7NHyn11H/.nQ736WCLK', 'user', '2026-08-14 12:29:43'),
('USR-1786768864-359', 'akmal', NULL, '$2y$10$UePguCNcroKV7.UWl48d0O0fEbeSBOsglXc.J4rINCAUhRsROVMnG', 'user', '2026-08-15 04:41:04'),
('USR-1786795059-267', 'andro', 'bcdiamond73@gmail.com', '$2y$10$OtyVs849KRfh2tHfXtd27ecd.cllEr/Ar8N4K0n6TfmMzwa3feXGu', 'user', '2026-08-15 11:57:39'),
('USR-1786795246-676', 'farel', 'papananjay281@gmail.com', '$2y$10$5X5JBOrtgr/XtKIIji.k/.VCN4x9.6bsN3LqlMMDWjnJl01Iys59e', 'user', '2026-08-15 12:00:46'),
('USR-1787061778-240', 'Aliando', 'syarifmaulanayusuf@gmail.com', '$2y$10$C0iDJKtHEqg8GsNVe2ghde.oPYRmK9bjG998FttvV/UE1fXyYu4jm', 'user', '2026-08-18 14:02:58'),
('USR-1787124306-274', 'Fajar', 'akmaldjo938@gmail.com', '$2y$10$rnBsP7fixzO3hbq9IIf6dOS6PZjYlrSpBYNpNJQfCl6yfobqi2cBK', 'user', '2026-08-19 07:25:06'),
('USR-ADMIN-01', 'admin', NULL, '$2y$10$OLVbTWdr24ldbJizrsu.VuDfdvtKr7.j7pNNOfLxDVd7CsO6xpthu', 'admin', '2026-08-14 12:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id_voucher` int(11) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tipe` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `nilai` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_belanja` decimal(12,2) NOT NULL DEFAULT 0.00,
  `maks_diskon` decimal(12,2) DEFAULT NULL,
  `kuota` int(11) NOT NULL DEFAULT 0,
  `terpakai` int(11) NOT NULL DEFAULT 0,
  `maksimal_per_user` int(11) NOT NULL DEFAULT 1,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `status` enum('draft','active','ended') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_usages`
--

CREATE TABLE `voucher_usages` (
  `id_voucher_usage` bigint(20) NOT NULL,
  `id_voucher` int(11) NOT NULL,
  `id_users` varchar(50) NOT NULL,
  `id_order` varchar(50) NOT NULL,
  `kode_voucher` varchar(50) NOT NULL,
  `nilai_diskon` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alamat`
--
ALTER TABLE `alamat`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `biodata`
--
ALTER TABLE `biodata`
  ADD PRIMARY KEY (`id_bio`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `idx_chat_receiver_read` (`receiver_id`,`is_read`,`created_at`);

--
-- Indexes for table `flash_sales`
--
ALTER TABLE `flash_sales`
  ADD PRIMARY KEY (`id_flash_sale`);

--
-- Indexes for table `flash_sale_items`
--
ALTER TABLE `flash_sale_items`
  ADD PRIMARY KEY (`id_item`),
  ADD UNIQUE KEY `unique_flash_product` (`id_flash_sale`,`id_produk`),
  ADD KEY `idx_flash_product` (`id_produk`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD UNIQUE KEY `unique_cart_product` (`id_users`,`id_produk`),
  ADD KEY `idx_cart_user` (`id_users`),
  ADD KEY `idx_cart_product` (`id_produk`);

--
-- Indexes for table `login_otp`
--
ALTER TABLE `login_otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_users` (`id_users`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_alamat` (`id_alamat`),
  ADD KEY `idx_orders_status_created` (`status_pesanan`,`created_at`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `idx_order_detail_order` (`id_order`),
  ADD KEY `idx_order_detail_produk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `idx_produk_kategori` (`kategori_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id_rewards`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userrewards`
--
ALTER TABLE `userrewards`
  ADD PRIMARY KEY (`id_user_rewards`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_rewards` (`id_rewards`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id_voucher`),
  ADD UNIQUE KEY `uk_voucher_kode` (`kode`);

--
-- Indexes for table `voucher_usages`
--
ALTER TABLE `voucher_usages`
  ADD PRIMARY KEY (`id_voucher_usage`),
  ADD KEY `idx_voucher` (`id_voucher`),
  ADD KEY `idx_user` (`id_users`),
  ADD KEY `idx_order` (`id_order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alamat`
--
ALTER TABLE `alamat`
  MODIFY `id_alamat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biodata`
--
ALTER TABLE `biodata`
  MODIFY `id_bio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id_chat` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flash_sales`
--
ALTER TABLE `flash_sales`
  MODIFY `id_flash_sale` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flash_sale_items`
--
ALTER TABLE `flash_sale_items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_otp`
--
ALTER TABLE `login_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id_rewards` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `userrewards`
--
ALTER TABLE `userrewards`
  MODIFY `id_user_rewards` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id_voucher` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_usages`
--
ALTER TABLE `voucher_usages`
  MODIFY `id_voucher_usage` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alamat`
--
ALTER TABLE `alamat`
  ADD CONSTRAINT `alamat_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `biodata`
--
ALTER TABLE `biodata`
  ADD CONSTRAINT `biodata_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id_users`),
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id_users`);

--
-- Constraints for table `flash_sale_items`
--
ALTER TABLE `flash_sale_items`
  ADD CONSTRAINT `fk_flash_item_product` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_flash_item_sale` FOREIGN KEY (`id_flash_sale`) REFERENCES `flash_sales` (`id_flash_sale`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_otp`
--
ALTER TABLE `login_otp`
  ADD CONSTRAINT `fk_login_otp_users` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_alamat` FOREIGN KEY (`id_alamat`) REFERENCES `alamat` (`id_alamat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_detail_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_detail_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE;

--
-- Constraints for table `userrewards`
--
ALTER TABLE `userrewards`
  ADD CONSTRAINT `userrewards_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE,
  ADD CONSTRAINT `userrewards_ibfk_2` FOREIGN KEY (`id_rewards`) REFERENCES `rewards` (`id_rewards`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_usages`
--
ALTER TABLE `voucher_usages`
  ADD CONSTRAINT `fk_voucher_usages_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_voucher_usages_user` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_voucher_usages_voucher` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id_voucher`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
