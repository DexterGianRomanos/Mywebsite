-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2026 at 04:31 PM
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
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','reviewed','shortlisted','rejected','hired') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `user_id`, `cover_letter`, `resume_path`, `status`, `applied_at`, `updated_at`) VALUES
(1, 1, 12, 'fgfdtgfdgd', '', 'hired', '2026-04-15 11:07:53', '2026-04-15 12:01:28'),
(2, 2, 12, 'dasdas', '', 'hired', '2026-04-15 12:17:18', '2026-04-15 12:17:26'),
(3, 1, 12, '', '', 'pending', '2026-04-15 12:38:39', NULL),
(4, 1, 12, 'fdgfdg', '1776257059_Screenshot 2026-04-15 123813.png', 'pending', '2026-04-15 12:44:19', '2026-04-15 12:46:01'),
(5, 3, 12, 'sdadasd', '1776258209_Screenshot 2025-12-08 210546.png', 'hired', '2026-04-15 13:03:29', '2026-04-15 13:17:22'),
(6, 3, 4, 'dsfsdf', '1776258835_Screenshot 2025-12-08 210546.png', 'hired', '2026-04-15 13:13:55', '2026-04-15 13:17:09'),
(7, 4, 12, 'Good', '1776263057_Resume Template Example in PDF and WORD Format.jpg', 'hired', '2026-04-15 14:24:17', '2026-04-15 14:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `type` enum('Full-time','Part-time','Remote','Contract') DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `slots` int(11) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `posted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `company`, `location`, `type`, `salary_range`, `description`, `requirements`, `slots`, `status`, `posted_by`, `created_at`) VALUES
(4, 'Front-End Developer', 'TechNova Solutions', 'Butuan City / Remote', 'Full-time', '₱18,000 – ₱30,000 / month', 'We are looking for a creative Front-End Developer responsible for building user-friendly web interfaces and improving user experience across web applications.', 'Knowledge in HTML, CSS, JavaScript\r\nExperience with React or Vue (optional but preferred)\r\nBasic understanding of UI/UX design\r\nCan work with API integration\r\nGood problem-solving skills', 5, 'open', 12, '2026-04-15 14:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 4, '🎉 You have been hired!', 0, '2026-04-15 13:14:17'),
(2, 4, '🎉 Congratulations! You have been hired. Welcome to the team!', 0, '2026-04-15 13:17:09'),
(3, 12, '🎉 Congratulations! You have been hired. Welcome to the team!', 0, '2026-04-15 13:17:22'),
(4, 12, '🎉 Congratulations! You have been hired. Welcome to the team!', 0, '2026-04-15 14:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `basic_salary` decimal(10,2) DEFAULT 0.00,
  `allowance` decimal(10,2) DEFAULT 0.00,
  `deduction` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) DEFAULT 0.00,
  `pay_period_start` date DEFAULT NULL,
  `pay_period_end` date DEFAULT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `user_id`, `job_id`, `basic_salary`, `allowance`, `deduction`, `net_salary`, `pay_period_start`, `pay_period_end`, `status`, `created_at`) VALUES
(1, 12, 1, 20000.00, 20000.00, 20000.00, 20000.00, '2026-04-15', '2026-04-15', 'paid', '2026-04-15 11:50:15'),
(2, 12, 2, 20.00, 0.00, 0.00, 20.00, '2026-04-15', '2026-04-15', 'paid', '2026-04-15 12:17:26'),
(3, 12, 3, 20.00, 0.00, 0.00, 20.00, '2026-04-15', '2026-04-15', 'paid', '2026-04-15 13:04:01'),
(4, 4, 3, 20.00, 0.00, 0.00, 20.00, '2026-04-15', '2026-04-15', 'paid', '2026-04-15 13:14:17'),
(5, 12, 4, 0.00, 0.00, 0.00, 0.00, '2026-04-15', '2026-04-15', '', '2026-04-15 14:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `profile_pic` varchar(255) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `login_attempts` int(11) DEFAULT 0,
  `last_attempt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `profile_pic`, `resume_path`, `phone`, `address`, `created_at`, `login_attempts`, `last_attempt`) VALUES
(1, 'Dexqt', 'admin@gmail.com', '$2y$10$nQznkCDU.A.5ELvHm.Ku4OQbzhWbEh7VcCx9yCbSmfgIJZ1aMWZR2', 'user', NULL, NULL, NULL, NULL, '2026-04-14 11:31:10', 0, NULL),
(3, 'Dexter Gian Romanos', 'admins@gmail.com', '$2y$10$3YdSrLz.9CX55jQ3dWrGLu4I3zK8RVufbQhlSFuBTVXWrSHDNlmAi', 'user', NULL, NULL, NULL, NULL, '2026-04-15 02:08:51', 0, NULL),
(4, 'Dexter Gian Romanos', 'mawmaw@qtgmail.com', '$2y$10$z7srI0D9HdwHZjrAE.RPxuWCl5FCsakslfBJKwRneZ1vctPPYLd2G', 'user', 'profile_4_1776261040.jpg', NULL, '', '', '2026-04-15 02:17:23', 0, NULL),
(12, 'Kim Matt', 'admin@example.com', '<?php echo password_hash(\"admin123\", PASSWORD_DEFAULT); ?>', 'admin', 'profile_12_1776260165.jpg', NULL, '091234474584', 'San Isidro Tomas Oppus', '2026-04-15 10:44:18', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
