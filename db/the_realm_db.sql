-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2024 at 10:20 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `the_realm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','User') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$RlSRcY3Z0YT/JRjkbXbPjebNkXP7pv9RkVmFqSP0c3bekUMwOjlBm', 'Admin'),
(5, 'user3', '$2y$10$REHqNF.FUEIl0MWVaqPF3ebCB3hbiCrJaaswDKG4WPeSdqZY.8C9y', 'User'),
(6, 'user123', '$2y$10$8wBAKURVXrdTfBW2xkzKTubjR6M8KsMkyLb7mbq.qg5.md7oaCLwy', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int NOT NULL,
  `donor_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_name`, `amount`, `date`, `ip_address`) VALUES
(1, 'Anas', '2000.00', '2024-02-14', '::1'),
(13, 'Anas1123', '1223.00', '1999-11-13', '123.123.123.132');

-- --------------------------------------------------------

--
-- Table structure for table `finalized_votes`
--

CREATE TABLE `finalized_votes` (
  `id` int NOT NULL,
  `server_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `toplist` text COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` text COLLATE utf8mb4_general_ci NOT NULL,
  `reward_amount` int NOT NULL,
  `time_voted` bigint NOT NULL,
  `claimed` int NOT NULL DEFAULT '0',
  `vpn` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finalized_votes`
--

INSERT INTO `finalized_votes` (`id`, `server_name`, `username`, `toplist`, `ip_address`, `reward_amount`, `time_voted`, `claimed`, `vpn`) VALUES
(2, '9Cdr3rJ8kG1', 'PLU1DgTmvA1', 'Iy5yFiUdhz1', 'hiSz2vqsOr1', 5471951, 2280511, 17913311, 1122),
(3, '9Cdr3rJ8kG', 'PLU1DgTmvA', 'Iy5yFiUdhz', 'hiSz2vqsOr', 547195, 228051, 179133, 468282);

-- --------------------------------------------------------

--
-- Table structure for table `online_users`
--

CREATE TABLE `online_users` (
  `id` int NOT NULL,
  `account_id` int NOT NULL,
  `last_activity` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `online_users`
--

INSERT INTO `online_users` (`id`, `account_id`, `last_activity`) VALUES
(5, 1, '2024-02-16 16:14:00'),
(6, 6, '2024-02-16 16:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `realm_games`
--

CREATE TABLE `realm_games` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `realm_games`
--

INSERT INTO `realm_games` (`id`, `name`, `image`, `link`) VALUES
(1, 'Valor', 'Discord-goldpng.png', 'https://example.com/item1'),
(2, 'Astral', 'Discord-goldpng.png', 'https://example.com/item2'),
(3, 'Game 3', 'Discord-goldpng.png', 'https://example.com/item3');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int NOT NULL,
  `store_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `logo` text COLLATE utf8mb4_general_ci NOT NULL,
  `favicon` text COLLATE utf8mb4_general_ci NOT NULL,
  `color` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `store_name`, `logo`, `favicon`, `color`) VALUES
(2, 'Store1', 'logo_65cfaa4522ede.jpg', 'favicon_65cfa02fe5160.png', '#ffcccc');

-- --------------------------------------------------------

--
-- Table structure for table `store_categories`
--

CREATE TABLE `store_categories` (
  `id` int NOT NULL,
  `store` text COLLATE utf8mb4_general_ci NOT NULL,
  `category_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `category_image` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_items`
--

CREATE TABLE `store_items` (
  `id` int NOT NULL,
  `store` text COLLATE utf8mb4_general_ci NOT NULL,
  `item_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `item_amount` int NOT NULL DEFAULT '1',
  `item_id` int NOT NULL DEFAULT '1',
  `item_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `default_price` double NOT NULL DEFAULT '0',
  `sale_price` double NOT NULL DEFAULT '0',
  `category` text COLLATE utf8mb4_general_ci,
  `amount_purchased` int DEFAULT '0',
  `hot_deal` int DEFAULT '0',
  `image` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_items`
--

INSERT INTO `store_items` (`id`, `store`, `item_name`, `item_amount`, `item_id`, `item_description`, `default_price`, `sale_price`, `category`, `amount_purchased`, `hot_deal`, `image`) VALUES
(1, '2', 'Abcd1', 11, 12, 'Acd Acbd cbda cbajs jsad\r\nsad shajd asd sad\r\nsad sadsadasj1', 302, 202, 'Fruits2', 502, 2, 'item_65cfb8eb2cc31.png');

-- --------------------------------------------------------

--
-- Table structure for table `vote_links`
--

CREATE TABLE `vote_links` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `site_id` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `vote_links`
--

INSERT INTO `vote_links` (`id`, `title`, `url`, `site_id`, `active`) VALUES
(1, 'Example Title', 'http://example.com', '1', 1),
(2, 'Another Title', 'http://another.com', '2', 1),
(3, 'Yet Another123', 'http://yetanother.com1', '11', 0),
(4, 'Test Title', 'http://test.com', '3', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finalized_votes`
--
ALTER TABLE `finalized_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_users`
--
ALTER TABLE `online_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_id` (`account_id`);

--
-- Indexes for table `realm_games`
--
ALTER TABLE `realm_games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_categories`
--
ALTER TABLE `store_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_items`
--
ALTER TABLE `store_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vote_links`
--
ALTER TABLE `vote_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `finalized_votes`
--
ALTER TABLE `finalized_votes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `online_users`
--
ALTER TABLE `online_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `realm_games`
--
ALTER TABLE `realm_games`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `store_categories`
--
ALTER TABLE `store_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_items`
--
ALTER TABLE `store_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vote_links`
--
ALTER TABLE `vote_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
