-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2021 at 05:06 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koky`
--

-- --------------------------------------------------------

--
-- Table structure for table `balances`
--

CREATE TABLE `balances` (
  `id` int(11) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `state` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(11) DEFAULT NULL,
  `u_id` int(10) NOT NULL,
  `place` varchar(255) NOT NULL,
  `place_id` int(11) NOT NULL,
  `t_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) DEFAULT NULL,
  `u_id` int(10) NOT NULL,
  `t_id` int(11) DEFAULT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `factories`
--

CREATE TABLE `factories` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `moves`
--

CREATE TABLE `moves` (
  `id` int(10) NOT NULL,
  `p_created_at` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `place_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL,
  `method` varchar(255) NOT NULL COMMENT 'add/minus',
  `state` varchar(255) NOT NULL COMMENT 'move/sell/refund',
  `edited_at` varchar(255) DEFAULT NULL,
  `t_id` int(10) DEFAULT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movestock`
--

CREATE TABLE `movestock` (
  `id` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `place` varchar(255) NOT NULL,
  `place_id` int(10) NOT NULL,
  `p_created_at` varchar(255) NOT NULL,
  `u_id` int(10) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) DEFAULT NULL,
  `from_place` varchar(255) DEFAULT NULL,
  `from_place_id` int(11) DEFAULT NULL,
  `with_u_id` int(10) NOT NULL,
  `state` int(10) NOT NULL,
  `t_id` int(10) DEFAULT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `upload` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) NOT NULL,
  `wholesale_price` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL,
  `u_id` int(10) NOT NULL,
  `indevice_id` int(10) DEFAULT NULL,
  `t_id` int(10) DEFAULT NULL,
  `sent_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `p_created_at` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `place_id` int(10) NOT NULL,
  `u_id` int(10) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) DEFAULT NULL,
  `editors_u_id` varchar(255) DEFAULT NULL,
  `movestock_id` int(10) NOT NULL,
  `t_id` int(10) DEFAULT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `storages`
--

CREATE TABLE `storages` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(11) DEFAULT NULL,
  `t_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `storages`
--

INSERT INTO `storages` (`id`, `name`, `address`, `created_at`, `edited_at`, `sent_at`, `indevice_id`, `t_id`) VALUES
(1, 'الخانكة', 'مثال العنوان', '2021-02-10 23:31:47.044644', '2021-02-10 23:31:47.044670', '2021-08-11 11:21:05.927976', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(11) DEFAULT NULL,
  `t_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `address`, `created_at`, `edited_at`, `sent_at`, `indevice_id`, `t_id`) VALUES
(5, 'الخانكة', 'ش المستشفى المركزي', '2021-02-11 03:51:46.573324', '2021-03-04 16:09:55.174016', '2021-08-11 11:21:04.397375', NULL, NULL),
(6, 'أبوزعبل', 'ابوزعبل', '2021-02-13 06:32:28.295654', '2021-02-13 06:32:28.295678', '2021-08-11 11:21:04.397375', NULL, NULL),
(7, 'العبور', 'عنوان', '2021-02-13 06:45:16.813907', '2021-02-13 06:45:16.813934', '2021-08-11 11:21:04.397375', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(10) NOT NULL,
  `token` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `place_id` int(10) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `place`, `place_id`, `created_at`) VALUES
(1, 'YzKn3VeGlRoU31aqUoLigcK0EPOzX9rd', 'office', 1, '2021-02-07 16:42:07.831645'),
(2, 'g6tMWuZ6vxDNCS75EDCYP1hKgMXtd2Rf', 'store', 5, '2021-02-07 16:42:07.831645'),
(3, '9BSFQkPcpXY6vY7z3g9iCEYAF4u2Z8P6', 'storage', 1, '2021-02-07 16:42:07.831645'),
(4, 'C2SG4AbcPwsKXaxjOSUqcuFSE5JwVLEW', 'store', 6, '2021-02-07 16:42:07.831645'),
(5, 'km8nuNVf3ISdj31IJIBwZQQn6T7u77ko', 'store', 7, '2021-02-07 16:42:07.831645');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) NOT NULL,
  `price` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `discounts` text DEFAULT NULL,
  `items` text NOT NULL,
  `c_id` varchar(255) DEFAULT NULL,
  `u_id` int(10) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `place_id` int(10) NOT NULL,
  `state` int(10) NOT NULL,
  `refund_u_id` int(10) NOT NULL,
  `byvisa` varchar(255) DEFAULT NULL,
  `edited_at` varchar(255) NOT NULL,
  `t_id` int(10) DEFAULT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  `mobile` varchar(255) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) NOT NULL,
  `edited_at` varchar(255) NOT NULL,
  `sent_at` varchar(255) DEFAULT NULL,
  `indevice_id` int(11) DEFAULT NULL,
  `t_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `permissions`, `mobile`, `state`, `created_at`, `edited_at`, `sent_at`, `indevice_id`, `t_id`) VALUES
(6, 'حاتم شلوبة', 'demo@kokykidswear.com', 'b64d1828a343bbecfd82314f76f3c94ac87b69d2795698586b854d4261529566c74848f5278815a416f7c718ceadcdee54e9b74701e095111b09db446b5357cb6tLfur8+qNlj0rTi+XWfKYAjfmkrd+3ATqDrarEpTA1pOPVWCJy8Sh6GzuoK+3cm', '0448aa88ae9c230accdbd4ed1a6ed92c.png', 'products,barcode,viewProducts,acceptProducts,balance,cashier,cashierReports,stores,storages', '01000000000', NULL, '2021-08-12 14:58:41.251977', '2021-08-12 14:58:41.252049', '2021-08-11 11:21:05.167408', NULL, NULL),
(7, 'أحمد زعتر', 'ahmedzater@kokykidswear.com', '189a20279ca31497d75cf43aa9810eb61923bbddfa6a37d0c5efd4eb2e328d258b66a2cbea12231db76d63d55cbe1f5dbb43d97d6356d0144f8fc430dbfda98euL4OhUUFvZtYE2BZdAdHMuhd7RgajXJ+kl98iTWgZuA=', '859dc3177f11458ad099987d3a5f505b.jpg', 'products,barcode,viewProducts,acceptProducts,balance,cashier,cashierReports,stores,storages', '01008258494', NULL, '2021-08-12 14:58:00.177973', '2021-08-12 14:58:00.178021', '2021-08-11 11:21:05.167408', NULL, NULL),
(8, 'مصطفى قرعلي', 'mostafa@kokykidswear.com', 'ad6cab13b336afdece9e2e7f2be5ab467a96dd8bc53dc11d3e91f41b8087a2e23eaae3218c6c0b835161d470c4e586568e4fdbc4afc4e9fca8189667acaa112bD9QeJzJVyD7G6VuSoZ5glQ/b8fEhXjtbvN0ySjb3+pEwOjCERmnOz7F+dwWb/Yi7', '859dc3177f11458ad099987d3a5f505b.jpg', 'products,barcode,viewProducts,acceptProducts,balance,cashier,cashierReports,stores,storages', '01092750650', NULL, '2021-08-12 14:58:17.282434', '2021-08-12 14:58:17.282479', '2021-08-11 11:21:05.167408', NULL, NULL),
(9, 'محمود الخياط', 'kemo@kokykidswear.com', '3c2b72186dd9099f57ddbe346b06509b1da202e2d5e9caf70e0b70de1546a1e04c4bbb99494e561728c1093b50f2c27a26e114a6fc86cbbb243c6008d7b37ee7DKHvtwJqHT6OvLU7MYjuInpgi+RGZ7pqdWWJbxHq7BEKOp+0cbEhLUpvwncvHnsK', '859dc3177f11458ad099987d3a5f505b.jpg', 'products,barcode,viewProducts,acceptProducts,balance,cashier,cashierReports,stores,storages', '01064998996', NULL, '2021-08-12 14:58:26.266655', '2021-08-12 14:58:26.266698', '2021-08-11 11:21:05.167408', NULL, NULL),
(10, 'أحمد الحجار', 'ahmedelhagar74@gmail.com', 'a235d29f6a24d8075187c93187c81eae6286f00ed3ab38bda48f51da9ed1c878b6d429e5f6ddd414ae5fbcd061ff58d6075aae8a39b152a575d47235189d7d32pVnqrPHN+KtJWNjHOY9bAwLZFJMgtRhoGaZ5Mkh/4U8=', '', 'products,barcode,viewProducts,acceptProducts,users,balance,cashier,cashierReports,stores,storages', '01065007516', NULL, '2021-08-12 14:27:05.563606', '2021-08-12 14:27:05.563643', '2021-08-11 11:21:05.167408', NULL, NULL),
(11, 'سامح العوفي', 'sameh@kokykidswear.com', 'c3b1cfd8feea608d2ef2fefe7eab25f54076a0b8e26334910c4c34f67e8820a0d39ea92826b60111a9f8d5b50d748efbaeb6522109a0c3715716fcba2b59787bbBr65bqHwSzzpxZ4njMbtwjB9m2vBIGCBcOs6q+l0BuP8f6v9NEiZWNcHMHXQBWX', '', 'products,barcode,viewProducts,acceptProducts,users,balance,cashier,cashierReports,stores,storages', '01003352923', NULL, '2021-08-12 14:57:48.735388', '2021-08-12 14:57:48.735442', NULL, NULL, NULL),
(12, 'محمد سباعي القصبي', 'mohamed@kokykidswear.com', '0f018137d1db6fcb956ac55eb5105a2c43c3dae3a6b8680fb402a1e5e2490d903ad1d77f7600278309dab01731c30663922510527dcf5867c0a8e0d65e4d55b7G6nxFROV0LFEhD72O17IsseiRcNrgQ7pr+a+8FKExT5M2E8jX2A2CwIQ71Bou+d1', '', 'products,barcode,viewProducts,acceptProducts,users,balance,cashier,cashierReports,stores,storages', '01013444467', NULL, '2021-08-12 15:03:36.059606', '2021-08-12 15:03:36.059629', NULL, NULL, NULL),
(13, 'أحمد جمال ابوشويشة', 'ahmed@kokykidswear.com', '6990d8f64c03a244686d324de0333a8e336264c4411acc82dd296fbc9fb64e969886b49d16886bbdbf4acd97446a8300f89abc21dab2ae59bae54e3becae1da9bsOIhy7kAQMiBoKMVHIlVRu4eRMUGeK/3p8TCzCUz5LEgk7RdA3AHoCt/phfEncU', '', 'products,barcode,viewProducts,acceptProducts,users,balance,cashier,cashierReports,stores,storages', '01111999331', NULL, '2021-08-12 15:04:56.036517', '2021-08-12 15:04:56.036540', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balances`
--
ALTER TABLE `balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factories`
--
ALTER TABLE `factories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moves`
--
ALTER TABLE `moves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movestock`
--
ALTER TABLE `movestock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `storages`
--
ALTER TABLE `storages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balances`
--
ALTER TABLE `balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factories`
--
ALTER TABLE `factories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `moves`
--
ALTER TABLE `moves`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movestock`
--
ALTER TABLE `movestock`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `storages`
--
ALTER TABLE `storages`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
