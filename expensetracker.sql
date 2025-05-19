-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 08:40 AM
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
-- Database: `expensetracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `remarks` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `username`, `date_created`, `category`, `amount`, `remarks`) VALUES
(2, '', '2025-04-27 19:01:56', 'school supplies', 20.00, 'i bought a pen'),
(3, '', '2025-04-27 19:08:35', 'meals', 125.00, 'i bought coke float again...'),
(4, '', '2025-04-27 19:24:01', 'meals', 200.00, 'i bought chicken fillet'),
(7, 'a', '2025-02-01 16:48:06', 'groceries', 3.00, 'ds'),
(8, 'a', '2025-05-02 08:38:31', 'groceries', 1.00, 'i needed some mentos'),
(10, 'a', '2025-05-02 17:12:02', 'groceries', 23.00, 'i needed some coke'),
(13, 'a', '2025-05-03 07:30:54', 'groceries', 10.00, 'i needed some mentos'),
(14, 'a', '2025-05-03 16:49:48', 'meal', 23.00, 'i needed some mentos'),
(15, 'a', '2025-05-04 14:23:26', 'meal', 12.00, 'i needed some mentos'),
(16, 'ashley', '2025-05-04 15:10:01', 'furniture', 20.00, 'asda'),
(17, 'a', '2025-05-04 15:15:22', 'mealz', 12.00, 'i needed some mentos'),
(18, 'a', '2025-05-04 16:38:25', 'furniture', 20.00, 'chaie'),
(19, 'a', '2025-05-04 16:51:44', 'meal', 12.00, 'i needed some mentos'),
(20, 'a', '2025-05-09 07:24:09', 'meal', 12.00, 'i needed some mentos'),
(21, 'a', '2025-05-09 13:46:12', 'groceries', 53.00, 'i bought some pride fabric laundry thing'),
(22, 'a', '2025-03-22 19:01:56', 'meal', 125.00, 'mcdonalds'),
(23, 'a', '2025-01-27 19:24:01', 'meal', 40.00, 'i bought coke float'),
(24, 'ash', '2025-05-10 14:23:04', 'groceries', 53.00, 'i bought some pride fabric laundry thing'),
(25, 'ash', '2025-05-10 14:23:36', 'furniture', 23.00, 'sasssa');

-- --------------------------------------------------------

--
-- Table structure for table `list_category`
--

CREATE TABLE `list_category` (
  `id` int(225) NOT NULL,
  `username` varchar(255) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `category` varchar(225) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_category`
--

INSERT INTO `list_category` (`id`, `username`, `date_created`, `category`, `description`) VALUES
(1, 'ashley', '2025-04-27 17:55:32', 'school supplies', 'things i need for school/college'),
(22, 'a', '2025-05-01 14:52:53', 'mealz', 'something to eat '),
(29, 'a', '2025-05-03 15:24:18', 'furniture', 'dwsds'),
(30, 'a', '2025-05-03 16:08:03', 'meal', 'something to eat'),
(31, 'a', '2025-05-04 16:52:00', 'groceries', 'none'),
(33, 'ash', '2025-05-10 14:21:54', 'groceries', 'things to buy'),
(34, 'ash', '2025-05-10 14:22:22', 'furniture', 'none'),
(35, 'ash', '2025-05-10 14:22:41', 'school supplies', 'for school/college');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `fName` varchar(225) NOT NULL,
  `lName` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `used_money` decimal(10,2) NOT NULL,
  `password` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fName`, `lName`, `email`, `username`, `money`, `used_money`, `password`) VALUES
(1, 'ashley', 'gernale', 'gernale@gmail.com', 'a', 221.00, 212.00, '$2y$10$Db7DvleXqcD6IqudQbiT0esfo4jgdZy3SzRJkk0vz99ewTGKWZHpa'),
(4, 'dede', 'dada', 'dede@gmail.com', 's', 0.00, 0.00, '$2y$10$LYVqo8L9ZwMFn6FbmV7Bm.lbowPsferueDXORfH49dmzHsyaX3FUK'),
(5, 's', 's', 's@gmail.com', 'd', 0.00, 0.00, '$2y$10$.amUK58SiLk1IUR5hIHG9uEha7nxXB/XSsfq4VAeTE./sSmLD4aBu'),
(6, 'ashley', 'gernale', 'gernale@gmail.com', 'ashley', 0.00, 0.00, '$2y$10$iBp51h.TPU8PcPybd07g..loRZr94qGyyV256Arv4bfqrZGZbXHO6'),
(8, 'ash', 'sha', 'ash@gmail.com', 'ash', 424.00, 76.00, '$2y$10$Pg2Z7gxL1jGuPXqeINvCJey.hLwIe6BTCN.osPtI0/oRfEAAQxxoa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_category`
--
ALTER TABLE `list_category`
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
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `list_category`
--
ALTER TABLE `list_category`
  MODIFY `id` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
