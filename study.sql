-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 04:26 AM
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
-- Database: `study`
--

-- --------------------------------------------------------

--
-- Table structure for table `study_goals`
--

CREATE TABLE `study_goals` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL DEFAULT 1,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `status` enum('Active','Completed','Abandoned') DEFAULT 'Active',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_goals`
--

INSERT INTO `study_goals` (`id`, `student_id`, `title`, `description`, `target_date`, `status`, `priority`, `created_at`, `completed_at`) VALUES
(1, 1, 'Mathematics', 'I want to cover two topics under matrix', '2025-08-30', 'Active', 'Medium', '2025-08-29 08:54:12', NULL),
(3, 1, 'Science', 'Study a complete topics on bonding', '2025-08-31', 'Active', 'Medium', '2025-08-29 08:59:38', NULL),
(5, 1, 'Books', 'Buy new story books, Title: How it', '2025-08-29', 'Active', 'Medium', '2025-08-29 09:08:18', NULL),
(6, 1, 'Physics', 'A topic under projectile motion must be completed', '2025-08-31', 'Active', 'Medium', '2025-08-29 09:34:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `index_number` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `index_number`, `gender`, `password`, `email`, `phone`, `created_at`) VALUES
(1, 'Eshun Kingsley Mintah', 'UEB3221622', 'Male', 'eshun123321', 'Kingsley@eshun.com', NULL, '2025-08-29 10:30:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `study_goals`
--
ALTER TABLE `study_goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `index_number` (`index_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `study_goals`
--
ALTER TABLE `study_goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
