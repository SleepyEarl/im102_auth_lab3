-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 04:29 AM
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
-- Database: `im102_lumosad`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electronics'),
(2, 'Apparel'),
(3, 'Kitchen'),
(4, 'Groceries'),
(5, 'Books');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `category_id`, `created_at`, `supplier_id`) VALUES
(1, 'Mechanical keyboard', 2500.00, 10, 1, '2026-06-24 02:01:31', 1),
(2, 'Wireless Mouse', 2500.00, 0, 1, '2026-06-19 01:23:21', 1),
(5, 'Denim Jeans', 2799.00, 120, 2, '2026-06-15 01:34:08', 2),
(6, 'Nike Shoes', 5000.00, 150, 2, '2026-06-15 01:34:08', 2),
(7, 'Non-Stick Frying Pan', 500.00, 100, 3, '2026-06-15 01:34:08', 1),
(8, 'Coffee Maker', 850.00, 100, 3, '2026-06-15 01:34:08', 1),
(9, 'Oil', 250.00, 50, 4, '2026-06-15 01:34:08', 3),
(10, 'Grains & Bread', 350.00, 0, 4, '2026-06-19 01:23:26', 3),
(11, 'Learn JS', 500.00, 50, 5, '2026-06-15 01:34:08', 1),
(14, 'HyperX Mic', 6000.00, 20, 1, '2026-06-18 01:59:21', 1),
(15, 'Gravastar Mehcanical Keyboard', 2022.00, 5, 1, '2026-06-18 02:03:42', 1),
(17, 'RTX 5050 6777Series', 50000.00, 2, 1, '2026-06-19 01:28:18', 1),
(18, 'Crossantssss', 24.00, 22, 4, '2026-06-24 01:53:51', 3);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`) VALUES
(1, 'TechHQ', 'Earls', '555'),
(2, 'GarmentCo', 'Krissy Greasy', '555'),
(3, 'FreshMart', 'MangJomar', '555');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Earl', 'earl@gmail.com', '$2y$10$871ExfaoNrNkVz852ELr9uG7ODjj9eTd9KESaqwFN1Vcidd4M9rHq', 'staff', '2026-06-24 00:54:24'),
(2, 'admin', 'admin@gmail.com', '$2y$10$icBsNbcZFTXr/zNo3sI7pOtEqeXzuO5FbwbgzE6O0p/UTQ.CPNGo.', 'admin', '2026-06-24 00:57:17'),
(3, 'Phel', 'phel@gmail.com', '$2y$10$MiPfz3.no9dOTMjY9twDIubSQZLMc.U1.0LyLSgq5ooaMvDw7xDy6', 'admin', '2026-06-24 01:32:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
