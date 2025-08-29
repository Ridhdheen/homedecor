-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2025 at 11:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `home_decor_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(1, 'Admin User', 'admin@gmai.com', 'admin123'),
(2, 'Admin User', 'admin11@gmail.com', 'shubh123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `name`, `price`, `image`, `quantity`, `created_at`) VALUES
(3, 3, 11, 'chair', 255.00, 'uploads/c1.jpg', 1, '2025-08-19 08:48:56'),
(18, 4, 11, 'chair', 255.00, 'uploads/c1.jpg', 1, '2025-08-19 08:56:51'),
(19, 4, 12, 'classy chair', 259.00, 'uploads/c2.jpg', 1, '2025-08-19 08:56:51'),
(22, 2, 11, 'chair', 255.00, 'uploads/c1.jpg', 1, '2025-08-19 15:34:39'),
(25, 12, 11, 'chair', 255.00, 'uploads/c1.jpg', 1, '2025-08-20 05:09:48'),
(27, 20, 11, 'chair', 255.00, 'uploads/c1.jpg', 9, '2025-08-20 07:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'furniture', 'sss', NULL, '2025-08-10 17:57:36', '2025-08-10 17:57:36'),
(2, 'chair', 'sss', 1, '2025-08-10 17:57:52', '2025-08-10 17:57:52'),
(5, 'lighting', '', NULL, '2025-08-11 01:46:17', '2025-08-11 01:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_city` varchar(100) DEFAULT NULL,
  `customer_state` varchar(100) DEFAULT NULL,
  `customer_zip` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending',
  `company` varchar(255) DEFAULT NULL,
  `address2` text DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `customer_city`, `customer_state`, `customer_zip`, `payment_method`, `total_amount`, `created_at`, `status`, `company`, `address2`, `country`, `shipping_cost`, `tax`, `notes`) VALUES
(2, 10, 'shubh santoki', 'ridheen@gmail.com', '9624227170', 'vankiya', 'jamnagar,gujrat,india', 'gujrat', '361210', 'cod', 2550.00, '2025-08-21 05:30:17', 'Pending', NULL, NULL, NULL, 0.00, 0.00, NULL),
(3, 10, 'prince panara', 'shubhusantoki@gmail.com', '9624227170', 'vankiya', 'jamnagar,gujrat,india', 'gujrat', '361210', 'cod', 255.00, '2025-08-21 05:32:19', 'Pending', NULL, NULL, NULL, 0.00, 0.00, NULL),
(4, 21, 'soham padaliya', 'soham@gmail.com', '9624227170', 'vankiya', 'jamnagar,gujrat,india', 'gujrat', '361210', 'cod', 604.00, '2025-08-22 09:03:30', 'Pending', NULL, NULL, NULL, 0.00, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`) VALUES
(1, 2, 11, 'chair', 10, 255.00, 2550.00),
(2, 3, 11, 'chair', 1, 255.00, 255.00),
(3, 4, 11, 'chair', 1, 255.00, 255.00),
(4, 4, 13, 'light', 2, 26.00, 52.00),
(5, 4, 16, 'light3', 1, 297.00, 297.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(100) DEFAULT 'Uncategorized'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `subcategory_id`, `stock`, `image`, `created_at`, `updated_at`, `category`) VALUES
(11, 'chair', 'cahir is comfortable', 255.00, 1, NULL, 10, 'uploads/c1.jpg', '2025-08-18 06:25:47', '2025-08-18 06:25:47', 'Uncategorized'),
(12, 'classy chair', 'ww', 259.00, 1, NULL, 10, 'uploads/c2.jpg', '2025-08-18 06:26:54', '2025-08-18 06:26:54', 'Uncategorized'),
(13, 'light', 'sss', 26.00, 5, NULL, 10, 'uploads/l1.jpg', '2025-08-22 08:58:17', '2025-08-22 08:58:17', 'Uncategorized'),
(15, 'light2', 'ddd', 289.00, 5, NULL, 10, 'uploads/l1.jpg', '2025-08-22 09:01:34', '2025-08-22 09:01:34', 'Uncategorized'),
(16, 'light3', 'sss', 297.00, 5, NULL, 22, 'uploads/l2.jpg', '2025-08-22 09:02:36', '2025-08-22 09:02:36', 'Uncategorized'),
(17, 'light3', 'sss', 297.00, 5, NULL, 22, 'uploads/l2.jpg', '2025-08-22 09:03:38', '2025-08-22 09:03:38', 'Uncategorized');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(2, 'shubh', 'shubh@gmail.com', '$2y$10$wsQgMaL1TC4huTADSNIX6uWkM7Cs6mhfXaaNFApNJHFZrqrJCd6Ba', 'admin', 'active', '2025-08-19 21:27:59'),
(3, 'shubh', 'santokishubh82@gmail.com', '$2y$10$w4yVZlzsAsRmzBJDyXqgpOHoVqa5b0W6RbAZ6ExDe6KphrlQXTaTS', 'user', 'active', '2025-08-19 21:27:59'),
(4, 'shubh', 'shubh12@gmail.com', '$2y$10$jsozJ4FjfWci22fN5SAyWeSOrDLTunu7Xg0toJbfH3yy4ladynN46', 'user', 'active', '2025-08-19 21:27:59'),
(5, 'admin12', 'admin23@gmail.com', '$2y$10$Vlc3UG.bGsUApDkWA0d.dOsbBEFYRszUmT0TXvsv214HiC4JqOvNa', 'user', 'active', '2025-08-19 21:28:18'),
(6, '23020201143', 'admin8@gmail.com', '$2y$10$MS8EpXbi2K5x/DwmzWPcxuTP/Kaj4h/TxCM93psYQ1fAXjRB6QPsu', 'user', 'active', '2025-08-19 21:29:46'),
(7, 'admin128', 'admin28@gmail.com', '$2y$10$aoik8NsHhn.fIDIBYIWCBOEzCyFe2er7T8AcqI4SmfOm4t8FUqQ..', 'user', 'active', '2025-08-19 21:35:10'),
(8, 'shubhusantoki_11', 'admin1111@gmail.com', '$2y$10$j1j.8HRN1XlPy70/7qL1zeT38xOdCvBt/5UpkXEm1LJ9KrxIeBQbq', 'user', 'active', '2025-08-19 21:39:13'),
(9, 'krish', 'krish@gmail.com', '$2y$10$TlZQDUbe4nKnSwsWOpclrO/ZNt0Xt36i52D7553UBUE/XBNF5Yary', 'user', 'active', '2025-08-20 10:21:13'),
(10, 'ridheen', 'ridheen@gmail.com', '$2y$10$3DafYNSFMkIl.1wz2uTcCurvFP2QA1.2wPCX1g664AEGbu6oZa//.', 'user', 'active', '2025-08-20 10:26:23'),
(11, 'prince', 'prince@gmail.com', '$2y$10$fESX2L94CFxXKHi4XBJvN.2s.idY04bQ7fiYYxaSP0YPw2tfji3vO', 'user', 'active', '2025-08-20 10:29:29'),
(12, 'aryan', 'aryan@gmail.com', '$2y$10$V7oviOMf3yNOD2UuOLDZ3.2MGPa8v6zrQz3IJXsgIxcVyw9YmdmCe', 'user', 'active', '2025-08-20 10:33:23'),
(13, 'ashit', 'ashit@gmail.com', '$2y$10$B.77xOg.VsHkvRMK3P.pfOBpISPWXKg35.Sk.to4KG4..FlybhZFO', 'user', 'active', '2025-08-20 12:30:16'),
(14, 'mihir', 'mihir@gmail.com', '$2y$10$hCKiPkqvcXmD/R6kr0PK8Oxxh0DpxOPxNhk3VmUQL/KxrHybZFQ.C', 'user', 'active', '2025-08-20 12:31:56'),
(15, 'dharmik', 'dharmik@gmail.com', '$2y$10$QHcxWSpmKlz811UnTWCJDOJxYV8IN4a.OQ1rAtXEFlxo/RmhQh8PO', 'user', 'active', '2025-08-20 12:34:06'),
(16, 'deep', 'deep@gmail.com', '$2y$10$C9Ly2vRnaWchTGn.0CjRDOdXksp/xARiniCp6LVcmsL/I5SZODIL6', 'user', 'active', '2025-08-20 12:35:45'),
(17, 'ss', 'ss1@gmail.com', '$2y$10$5H5R3ZBTZBtkrkwZ3nOx.O8vt148HpCAL6DVSeF09RlIFlYTErZNu', 'user', 'active', '2025-08-20 12:37:26'),
(18, 'santoki', 'santoki@gmail.com', '$2y$10$Eq7NjQqEpBI18bQt1i6jZOQnhIZLqHjZt9y6a3Ks8dMsBXYvzys0a', 'user', 'active', '2025-08-20 12:38:25'),
(19, 'gadara', 'gadara@gmail.com', '$2y$10$8v13OhJ7m/JZu9LX/QOA5OJXg7DoIZKu6HLQ3U4bkqr5oKloROn3G', 'user', 'active', '2025-08-20 12:39:10'),
(20, 'kano', 'kano@gmail.com', '$2y$10$CgCRx27rzfR34M5DUKfmZe5iT3Q7qJh7bHOF.JheOSaRiSqvUWIIK', 'user', 'active', '2025-08-20 12:41:34'),
(21, 'soham', 'soham@gmail.com', '$2y$10$PKWtWaIZVMAurmE3ZKayg.X0W2A0R8Y2SNlo.Gth1/U2nvnW0klPS', 'user', 'active', '2025-08-22 14:22:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE `user_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_cart`
--
ALTER TABLE `user_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
