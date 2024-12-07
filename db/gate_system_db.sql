-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 02:53 AM
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
-- Database: `vehicle-gate`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','security','owner') NOT NULL,
  `gate_number` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `password`, `role`, `gate_number`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'Jay', 'admin1', '$2y$10$ybZjgbqGO/.d6aCV8GRgeO1ww6wkuuEWbWB3k9RwvJ5Enh.x5lGQy', 'admin', '', 'active', '2024-12-04 03:10:43', '2024-12-06 13:34:11'),
(4, 'John', 'Doe', 'john_doe', '$2y$10$x8EuX6XHXeffOLcqkCeYxO0RlhZy2QLmlAXoVYMHyQO3LYjBhhEyu', 'owner', '1', 'active', '2024-12-04 03:30:11', '2024-12-06 04:27:15'),
(5, 'June', 'Bee', 'june', '$2y$10$2Q8cPJUQhz6dyuzY2TDASuZn7fnKDCg/MM5cEKq.BXAcIbzcNjZA2', 'security', '4', 'active', '2024-12-04 11:18:53', '2024-12-06 09:32:23'),
(6, 'Jean', 'Cruz', 'jean_cruz', '$2y$10$1bYkCLsOpCVCQLagjq5OjO3rdrj3N3FGnHk963pMq4PktPQC4CjFq', 'admin', '', 'active', '2024-12-06 02:32:44', '2024-12-06 02:32:44'),
(7, 'Edwin', 'Cruz', 'edwin', '$2y$10$/gyAaepJ.gv6vZEbpoG/dO8FLv1pyPnto5WiD/iWC/5wp4ECxdpFG', 'security', '2', 'active', '2024-12-05 21:54:25', '2024-12-06 13:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(15) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `owner_id` varchar(255) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_contact` varchar(15) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','denied') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `vehicle_type`, `owner_id`, `owner_name`, `owner_contact`, `registration_date`, `status`, `approved_by`) VALUES
(7, 'CBK9090', 'SUV', '4', 'John Doe', '0987654321', '2024-12-06 09:43:59', 'pending', NULL),
(8, 'CBA7090', 'Sedan', '4', 'John Doe', '0987654321', '2024-12-06 09:44:12', 'approved', 3);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_logs`
--

CREATE TABLE `vehicle_logs` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `entry_exit` enum('entry','exit') NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gate_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_logs`
--

INSERT INTO `vehicle_logs` (`id`, `plate_number`, `entry_exit`, `date_time`, `gate_number`) VALUES
(5, 'CBA7090', 'entry', '2024-12-04 06:41:20', 2),
(6, 'CBA7090', 'exit', '2024-12-04 06:41:34', 4),
(7, 'CBK9090', 'entry', '2024-12-05 22:33:15', 4),
(8, 'CBK9090', 'exit', '2024-12-05 22:33:29', 4),
(9, 'CBA7090', 'entry', '2024-12-06 17:54:01', 4),
(10, 'CBA7090', 'exit', '2024-12-06 17:54:25', 4),
(11, 'CBA7090', 'entry', '2024-12-06 18:16:32', 2),
(12, 'CBA7090', 'entry', '2024-12-06 18:16:38', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `vehicle_logs`
--
ALTER TABLE `vehicle_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_number` (`plate_number`),
  ADD KEY `date_time` (`date_time`),
  ADD KEY `gate_number` (`gate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vehicle_logs`
--
ALTER TABLE `vehicle_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
