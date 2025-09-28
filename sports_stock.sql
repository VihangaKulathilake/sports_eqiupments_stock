-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2025 at 08:58 AM
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
-- Database: `sports_stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `image_path`, `email`, `phone`, `address`, `username`, `password`, `role`, `reset_token`, `reset_expires`) VALUES
(1, 'Vihanga', '68c2c04a1f6e64.99799816.jpg', 'vihangajanith12m@gmail.com', '0718519445', 'colombo', 'vjk2525', '$2y$10$aFmfdxHJkjlAgArRuF9j9.7RGSGvpzraQxHnUtsJcMr3U.IZNDUPS', 'admin', '9c4dbeb78c01c550dee9a76e77ece9dcecbbdd95c37c61d63e0402e2466bf8b35029d275ea02a25ff45974c89cbd797f19b0', '2025-09-22 20:29:55'),
(2, 'Vihanga Janith', '68c2c484693d74.72588766.jpg', 'kulathilake@gmail.com', '0718519555', 'No.164,Thibbotugoda,Ganemulla.', 'vjk25', '$2y$10$1/3gFMOuutuyh/jpfsRWlOY/dSoy0UGTRExEcqfTZVoV8NrW.xcZm', 'user', NULL, NULL),
(3, 'Vihanga Kulathilake', '68c8f631e0d3e8.22435254.jpg', 'vihangakule12m@gmail.com', '0718519469', 'Colombo', 'kule25', '$2y$10$8sLiLdbuXc3In9lbd4ZEhOeA1oR2015FpaggE4duG3pJ1zCXlg2WC', 'user', NULL, NULL),
(4, 'Admin', 'default.jpg', 'admin@gmail.com', '0718519666', 'colombo', 'admin', '$2y$10$j3JEZrcRlvOwhLbIliu.K.vE5KYreNEnSQlyomGnRIMMzEHmgxg4.', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_cart`
--

CREATE TABLE `customer_cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_cart`
--

INSERT INTO `customer_cart` (`id`, `customer_id`, `product_id`, `quantity`, `added_at`) VALUES
(22, 1, 9, 2, '2025-09-11 22:32:27'),
(25, 1, 8, 8, '2025-09-16 06:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` enum('pending','completed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `order_status`) VALUES
(1, 2, '2025-09-11', 'pending'),
(2, 2, '2025-09-11', 'pending'),
(3, 2, '2025-09-11', 'pending'),
(4, 2, '2025-09-11', 'pending'),
(5, 2, '2025-09-11', 'pending'),
(6, 2, '2025-09-12', 'pending'),
(7, 2, '2025-09-12', 'pending'),
(8, 2, '2025-09-12', 'pending'),
(9, 2, '2025-09-12', 'pending'),
(10, 3, '2025-09-16', 'pending'),
(13, 2, '2025-09-16', 'pending'),
(14, 2, '2025-09-16', 'pending'),
(15, 2, '2025-09-16', 'pending'),
(17, 2, '2025-09-16', 'completed'),
(18, 2, '2025-09-17', 'pending'),
(19, 2, '2025-09-17', 'pending'),
(20, 2, '2025-09-17', 'pending'),
(21, 2, '2025-09-17', 'pending'),
(22, 2, '2025-09-21', 'completed'),
(23, 2, '2025-09-21', 'completed'),
(25, 2, '2025-09-23', 'pending'),
(26, 2, '2025-09-23', 'pending'),
(27, 2, '2025-09-23', 'pending'),
(28, 2, '2025-09-23', 'pending'),
(29, 2, '2025-09-23', 'pending'),
(30, 2, '2025-09-23', 'pending'),
(31, 2, '2025-09-23', 'pending'),
(32, 2, '2025-09-23', 'pending'),
(33, 2, '2025-09-23', 'pending'),
(34, 2, '2025-09-23', 'pending'),
(35, 2, '2025-09-23', 'pending'),
(36, 2, '2025-09-23', 'completed'),
(38, 2, '2025-09-23', 'completed'),
(39, 2, '2025-09-23', 'pending'),
(40, 2, '2025-09-23', 'pending'),
(41, 2, '2025-09-23', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`) VALUES
(6, 5, 8, 2),
(7, 6, 8, 5),
(8, 7, 8, 1),
(9, 8, 8, 8),
(10, 9, 9, 1),
(11, 9, 11, 3),
(12, 9, 14, 3),
(13, 9, 8, 2),
(14, 10, 9, 1),
(19, 13, 9, 1),
(20, 14, 11, 1),
(21, 15, 9, 4),
(23, 17, 9, 2),
(24, 18, 11, 4),
(25, 19, 11, 1),
(26, 20, 9, 3),
(27, 21, 9, 1),
(28, 22, 9, 6),
(29, 22, 11, 5),
(30, 23, 11, 1),
(32, 25, 11, 5),
(33, 26, 11, 1),
(34, 27, 11, 5),
(35, 28, 11, 1),
(36, 29, 11, 2),
(37, 30, 14, 4),
(38, 31, 14, 4),
(39, 32, 9, 2),
(40, 33, 11, 1),
(41, 33, 14, 2),
(42, 34, 11, 1),
(43, 34, 14, 1),
(44, 35, 11, 3),
(45, 36, 11, 4),
(47, 38, 17, 1),
(48, 39, 11, 1),
(49, 40, 9, 1),
(50, 41, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `image_path`, `category`, `price`, `supplier_id`) VALUES
(8, 'Cricket Batting Pads', 'Cricket batting pads', '68c30682a5b940.52845064.jpg', 'Cricket', 10000.00, 1),
(9, 'SS Cricket Bat', 'Cricket bat', '68c3dea83ce739.52925644.jpg', 'Cricket', 40000.00, 1),
(11, 'Cricket Red Ball', 'cricket red ball', '68c3e0974cc3a6.60359718.jpg', 'Cricket', 2500.00, 1),
(14, 'Cricket Shoes', 'cricket shoes', '68c3e1197d6537.87068702.jpg', 'Cricket', 20000.00, 1),
(17, 'Volleyball Ball', 'volleyball ball', '68d0fe9b1e81d8.39435584.jpg', 'Volleyball', 10000.00, 1),
(19, 'PUMA FUTURE 8 MATCH Playmaker', 'Firm/Artificial Ground Soccer Cleats', '68d32cd26a79d5.76534322.avif', 'Football', 40000.00, 1),
(20, 'YONEX ASTROX 100VA ZZ', 'Premium Badminton Rackets', '68d32eef15e397.51217266.webp', 'Badminton', 30000.00, 1),
(21, 'YONEX MAVIS 2000', 'The Mavis 2000 is designed to be the ultimate practice and tournament shuttlecock for club players.', '68d32f72534c32.08030321.webp', 'Badminton', 10000.00, 1),
(22, 'YONEX POWER CUSHION 65 Z VA X', 'POWER CUSHION +, Power Graphite Lite, Seameless Upper, Inner Bootie, Ergonomic Shaped Outsole', '68d32fec352f70.06777108.webp', 'Badminton', 30000.00, 1),
(23, 'YONEX EXPERT RACQUET BAG VA (6PCS)', 'Premium Badminton Racquet Bags', '68d33095bca609.20519151.webp', 'Badminton', 40000.00, 1),
(24, 'PUMA HALI 1', 'Basketball Shoes', '68d33141d0bc37.23557619.avif', 'Basketball', 40000.00, 1),
(25, 'MIKASA Volleyball', 'volleyball ball', '68d3322a48cd15.33706664.jpg', 'Volleyball', 15000.00, 1),
(26, 'COSCO Munich S-5', 'Cosco Munich Ball made with PU using BONDED Technology Reinforced with Latex Casting for High Rebound, liked by all Professional Players. Suitable for All Weather Conditions.', '68d3371240ee89.54501446.png', 'Football', 15000.00, 2),
(27, 'COSCO Swiss S-5', 'Rubber Laminate ball with Thermo Technology. Highly Durable under all Ground Conditions.', '68d33ae6997e19.26099728.png', 'Football', 15000.00, 3),
(28, 'COSCO Torino S-5', 'Entry Level Training Balls made with Imported PU in Vibrant Colours and Designs.', '68d33bf3138b32.30145873.png', 'Football', 15.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stock_id`, `product_id`, `quantity`, `last_updated`) VALUES
(3, 11, 87, '2025-09-23 13:58:57'),
(4, 14, 23, '2025-09-23 13:36:51'),
(7, 9, 12, '2025-09-23 15:29:23'),
(8, 11, 72, '2025-09-23 13:42:41'),
(9, 14, 26, '2025-09-23 13:06:58'),
(12, 17, 13, '2025-09-23 13:53:59'),
(13, 8, 50, '2025-09-24 05:29:47'),
(14, 9, 30, '2025-09-24 05:29:47'),
(15, 11, 100, '2025-09-24 05:29:47'),
(16, 14, 25, '2025-09-24 05:29:47'),
(17, 17, 40, '2025-09-24 05:29:47'),
(18, 19, 20, '2025-09-24 05:29:47'),
(19, 20, 15, '2025-09-24 05:29:47'),
(20, 21, 60, '2025-09-24 05:29:47'),
(21, 22, 18, '2025-09-24 05:29:47'),
(22, 23, 10, '2025-09-24 05:29:47'),
(23, 24, 12, '2025-09-24 05:29:47'),
(24, 25, 35, '2025-09-24 05:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `email`, `phone`, `address`) VALUES
(1, 'abc', 'abc@gmail.com', '0332262970', 'colombo'),
(2, 'Global Sports Co', 'global@sports.com', '0112233445', 'Kandy'),
(3, 'SportZone Pvt Ltd', 'zone@sports.com', '0778899000', 'Galle'),
(4, 'Super Sports Lanka', 'super@sports.com', '0751122334', 'Negombo');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_orders`
--

CREATE TABLE `supplier_orders` (
  `supplier_order_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_orders`
--

INSERT INTO `supplier_orders` (`supplier_order_id`, `supplier_id`, `order_date`, `order_status`) VALUES
(1, 1, '2025-09-12', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_order_items`
--

CREATE TABLE `supplier_order_items` (
  `supplier_order_item_id` int(11) NOT NULL,
  `supplier_order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_order_items`
--

INSERT INTO `supplier_order_items` (`supplier_order_item_id`, `supplier_order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 8, 100, 7000.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_favourites`
--

CREATE TABLE `user_favourites` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_favourites`
--

INSERT INTO `user_favourites` (`id`, `customer_id`, `product_id`, `added_at`) VALUES
(16, 3, 9, '2025-09-20 22:04:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customer_cart`
--
ALTER TABLE `customer_cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`customer_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  ADD PRIMARY KEY (`supplier_order_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplier_order_items`
--
ALTER TABLE `supplier_order_items`
  ADD PRIMARY KEY (`supplier_order_item_id`),
  ADD KEY `supplier_order_id` (`supplier_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user_favourites`
--
ALTER TABLE `user_favourites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favourite` (`customer_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_cart`
--
ALTER TABLE `customer_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  MODIFY `supplier_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_order_items`
--
ALTER TABLE `supplier_order_items`
  MODIFY `supplier_order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_favourites`
--
ALTER TABLE `user_favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_cart`
--
ALTER TABLE `customer_cart`
  ADD CONSTRAINT `customer_cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  ADD CONSTRAINT `supplier_orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `supplier_order_items`
--
ALTER TABLE `supplier_order_items`
  ADD CONSTRAINT `supplier_order_items_ibfk_1` FOREIGN KEY (`supplier_order_id`) REFERENCES `supplier_orders` (`supplier_order_id`),
  ADD CONSTRAINT `supplier_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `user_favourites`
--
ALTER TABLE `user_favourites`
  ADD CONSTRAINT `user_favourites_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_favourites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
