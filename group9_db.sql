-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 03:42 AM
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
-- Database: `group9_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reserved_date` date NOT NULL,
  `reserved_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `reserved_date`, `reserved_time`, `created_at`) VALUES
(9, 2, '2025-10-27', '08:00:00', '2025-10-25 18:01:04'),
(10, 1, '2025-10-27', '08:00:00', '2025-10-26 01:34:29'),
(16, 3, '2025-10-27', '08:00:00', '2025-10-26 02:08:02'),
(18, 4, '2025-10-27', '08:00:00', '2025-10-26 02:15:48'),
(19, 7, '2025-10-27', '08:00:00', '2025-10-26 02:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `school_id` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `firstname`, `lastname`, `school_id`, `phone`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$Ita.We9ISu9I.rYIPhXGb.j0ggAtk6JubGg/3RYlZpRQBajEsnDAq', '', '', '', ''),
(2, 'test', 'test@gmail.com', '$2y$10$n44QIsDwwR2wB/abPpE3EO9i85D5JwFT2oxXpa/BD8bKN8GePMzNS', '', '', '', ''),
(3, 'jerecho earl balingasa', 'admin1@gmail.com', '$2y$10$9DWN6DzVTGuRrgQzexzr2Onrin2IkG4ipxQedROB.Z7dtK5LaVzI6', '', '', '04-2324-043740', '091234518717'),
(4, 'aki toshi', 'admin2@gmail.com', '$2y$10$pwSP/eeAYAkA5abaZ/DXserfD.5buD8BDzvH1/MffCRlxabAFIA4G', '', '', '010101010', '010101010'),
(7, 'Alexander John Caligan', 'adminq@gmail.com', '$2y$10$DGLD5UJN7bMNfK5q/CIU2u9qE2RcPKzp3AaNLi3POUOJNWrkZ7ZVS', '', '', '04-2324-043740', '091234518717');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
