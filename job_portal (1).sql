-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 10:24 AM
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
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`id`, `job_id`, `seeker_id`, `cv`, `cover_letter`, `status`, `applied_at`) VALUES
(7, 29, 6, 'cv_sample.pdf', 'I am very interested in this position.', 'pending', '2026-08-05 07:53:56'),
(8, 29, 8, 'cv_sample.pdf', 'I am very interested.', 'pending', '2026-08-05 08:00:41'),
(9, 31, 8, 'cv_sample.pdf', 'I am very interested in this position.', 'pending', '2026-08-05 08:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(1, 'Software Development', '2026-08-02 23:15:32'),
(2, 'Web Development', '2026-08-02 23:15:58'),
(3, 'Data Science', '2026-08-02 23:16:28'),
(4, 'Mobile Development', '2026-08-02 23:16:52'),
(5, 'Cyber Security', '2026-08-02 23:17:22'),
(6, 'UI/UX Design', '2026-08-02 23:17:43'),
(7, 'Engineering', '2026-08-02 23:18:05'),
(9, 'Digital Marketing', '2026-08-03 20:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `company_name`, `description`, `website`, `location`, `logo`, `created_at`) VALUES
(2, 5, 'Malak Company', 'Software Company', 'https://test.com', 'Cairo', 'logo.png', '2026-08-03 22:59:04'),
(3, 7, 'ZooZ', '', 'https://eng-zooz.vercel.app/', '', '1785908853_WhatsApp Image 2025-12-09 at 2.14.30 PM.jpeg', '2026-08-05 05:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `job_type` enum('full time','part time','remote,internship') NOT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `company_id`, `category_id`, `title`, `description`, `requirements`, `salary`, `location`, `email`, `job_type`, `experience`, `deadline`, `status`, `created_at`) VALUES
(29, 2, 1, 'zooz', 'full stack', NULL, 50000.00, 'cairo', '', 'full time', NULL, NULL, 'open', '2026-08-04 01:46:37'),
(31, 3, 2, 'full stack', 'fdfgdfg', NULL, 50000.00, '111111', 'zizosobhy306@gmail.com', 'full time', NULL, NULL, 'open', '2026-08-05 05:18:33');

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_jobs`
--

INSERT INTO `saved_jobs` (`id`, `seeker_id`, `job_id`, `created_at`) VALUES
(1, 8, 29, '2026-08-05 08:20:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('admin','company','job_seeker') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `skills` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `skills`, `experience`, `education`, `profile_image`, `cv_file`) VALUES
(1, 'kholoud emam', 'kholoudemam918@gmail.com', '$2y$10$QW0Ml53QcV2hx.18FZWMCOcsdz0QTfdgiNbEXpYqW34ul1ILr23Ha', '01011111111', NULL, 'company', '2026-08-02 01:08:45', NULL, NULL, NULL, NULL, NULL),
(3, 'jjjjjj', 'k@gmail.com', '$2y$10$2VdE.ljNdmTEVNcZEdQNRe.tjWKScLBcZKdD/6.H0nwXSbTeKlr/S', '111111111', NULL, 'job_seeker', '2026-08-02 13:57:56', NULL, NULL, NULL, NULL, NULL),
(4, 'admin ', 'Admin@gmail.com', '$2y$10$pdC4Phb.iEAaLMLn8IR9febGqMXSAkvylMErDFCcF7U.UQgQF9hDG', '111111', NULL, 'admin', '2026-08-02 14:14:10', NULL, NULL, NULL, NULL, NULL),
(5, 'malak', 'malak@gmail.com', '$2y$10$u9OUBKMizc/G3mpHI8hwPumNq9qzAirsESyC4Mhb1ilKkt.pyTClK', '0123456', NULL, 'company', '2026-08-03 21:22:53', NULL, NULL, NULL, NULL, NULL),
(6, 'zyad', 'zizosobhy306@gmail.com', '$2y$10$KlJaFnc1rFDZXJhVeXaJSuaWSbrVrATU5fzHBxWqQCYw/SHt0dedG', '01033748811', NULL, 'job_seeker', '2026-08-05 01:34:52', NULL, NULL, NULL, NULL, NULL),
(7, 'zyad', 'zyad@gmail.com', '$2y$10$UFb8jS4K3Fasne5hqGm2/emmOF/ASdStD6uU1iRxSfAnHlb2g5IES', '01033748811', NULL, 'company', '2026-08-05 05:17:00', NULL, NULL, NULL, NULL, NULL),
(8, 'zooz', 'z123@gmail.com', '$2y$10$zNHHj8SqjWqTs7fEwjMq3uqyUO6CxEpUZgTfYj3tN5EaC6WSIX3BS', '01033748811', '', 'job_seeker', '2026-08-05 07:12:40', '', '', '', '1785916315_ZoooooooooooZ.jpeg', '1785915613_M3aarf_Certificate (1).pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `seeker_id` (`seeker_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seeker_id` (`seeker_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD CONSTRAINT `saved_jobs_ibfk_1` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
