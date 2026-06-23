-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 04:31 AM
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
-- Database: `im102_lab3`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--
-- Error reading structure for table im102_lab3.categories: #1932 - Table 'im102_lab3.categories' doesn't exist in engine
-- Error reading data for table im102_lab3.categories: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `im102_lab3`.`categories`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
-- Error reading structure for table im102_lab3.products: #1932 - Table 'im102_lab3.products' doesn't exist in engine
-- Error reading data for table im102_lab3.products: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `im102_lab3`.`products`' at line 1

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--
-- Error reading structure for table im102_lab3.suppliers: #1932 - Table 'im102_lab3.suppliers' doesn't exist in engine
-- Error reading data for table im102_lab3.suppliers: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM `im102_lab3`.`suppliers`' at line 1

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
(1, 'admin', 'admin123@gmail.com', '$2y$10$i1qd.ah8.nF4q9jBNxe.quW35xJv3agHWV5E1YbyKcBYr/cPZTcrK', 'admin', '2026-06-23 01:37:11'),
(2, 'test', 'test@gmail.com', '$2y$10$838rApcfq4PWLbhocetryeDuT8CEAhOorfg3hayHNdApGfNHT0Hmm', 'staff', '2026-06-23 01:44:24'),
(8, 'jbeboy', 'jb@gmail.com', '$2y$10$j6MPP8A4PP7OnLCz8i7mi.9bIj3MoxG.D4CiUAsWUMQTww/sPtknq', 'staff', '2026-06-23 01:55:03'),
(9, 'earl', 'earl@gmail.com', '$2y$10$2KNsz3r2RunzY9VidftoTuia.cxk/xnFyEnbBeL3Y3WndZgM5TkXu', 'staff', '2026-06-23 02:15:13');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
