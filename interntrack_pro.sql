-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 04:17 PM
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
-- Database: `interntrack_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'admins', 'admin@interntrack.com', 'admin123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `company_id`, `application_date`, `status`, `remarks`) VALUES
(2, 10, 4, '2026-05-21 22:13:08', 'Pending', ''),
(3, 7, 6, '2026-05-21 22:22:06', 'Accepted', ''),
(4, 9, 5, '2026-05-21 23:16:02', 'Accepted', '');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `location` varchar(100) NOT NULL,
  `industry` varchar(100) NOT NULL,
  `internship_slots` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `email`, `phone`, `location`, `industry`, `internship_slots`, `created_at`) VALUES
(4, 'GIVINGHUB', 'ggibve@gmail.com', '0627299103', 'SELANGOR', 'MANAGEMENT', 5, '2026-05-21 21:32:36'),
(5, 'JCOBS', 'Jcobs321@gmail.com', '0627299103', 'JOHOR', 'TECHNOLOGY', 7, '2026-05-21 22:19:08'),
(6, 'PETRONAS', 'petronas33@gmail.com', '066628928', 'KUALA LUMPUR', 'FINANCE', 4, '2026-05-21 22:20:45'),
(7, 'PwC', 'ppwc752@gmail.com', '034452689', 'SHAH ALAM', 'WEB DEVELOPER', 3, '2026-05-21 22:21:49'),
(8, 'SETTLE MALAYSIA', 'settleMalaysia@gmail.com', '033445278', 'PUTRAJAYA', 'FINANCE', 6, '2026-05-21 23:15:49');

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `interview_date` date NOT NULL,
  `interview_time` time NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interviews`
--

INSERT INTO `interviews` (`id`, `application_id`, `interview_date`, `interview_time`, `location`, `status`, `notes`, `created_at`) VALUES
(2, 2, '2026-05-28', '08:00:00', 'SELANGOR', 'Scheduled', '', '2026-05-21 22:15:05'),
(3, 3, '2026-05-24', '09:30:00', 'KUALA LUMPUR', 'Scheduled', '', '2026-05-21 22:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `university` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `internship_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `full_name`, `email`, `phone`, `university`, `course`, `internship_status`, `created_at`) VALUES
(7, 'NURKHAIRUNNISA HAYUSZAIMI', 'nurn64558@gmail.com', '011234556676', 'UiTM', 'INFORMATION MANAGEMENT', 'Pending', '2026-05-21 21:23:39'),
(8, 'RAIHAN NASUHA MUHAMAD SHUKRI', 'raii662@gmail.com', '01121179540', 'UiTM', 'INFORMATION MANAGEMENT', 'Pending', '2026-05-21 21:24:05'),
(9, 'NOR AFZA AINA RAHIMI', 'afzza@gmail.com', '0147563389', 'UiTM', 'LIBRARY MANAGEMENT', 'Rejected', '2026-05-21 21:24:27'),
(10, 'MUHAMMD SHAHRUL ABDULLAH', 'shah223@gmail.com', '0126665279', 'UiTM', 'RECORD MANAGEMENT', 'Active', '2026-05-21 21:26:15'),
(11, 'MUHAMMAD ANAS MAHDI ', 'anasmahdi55@gmail.com', '0112437736', 'UiTM', 'LIBRARY SCIENCE', 'Accepted', '2026-05-21 22:16:54'),
(12, 'MUNAWARAH HIDAYAH ', 'munnaa@gmail.com', '0136674528', 'UTM', 'SCIENCE AND TECHNOLOGY', 'Accepted', '2026-05-21 22:18:26'),
(13, 'NUR AMIRAH AISYS', 'miraahh2@gmail.com', '01123327392', 'UUM', 'ENGINEERING', 'Pending', '2026-05-21 23:10:31'),
(14, 'AHMAD SYZWAN ROSLI', 'waann33@gmail.com', '0123372920', 'UPNM', 'ELECTRONIC ENGINEERING', 'Accepted', '2026-05-21 23:11:47'),
(15, 'FARAH LIANA RAHMAT', 'farah77@gmail.com', '0134255278', 'UiTM', 'SCIENCE AND TECHNOLOGY', 'Accepted', '2026-05-21 23:12:41'),
(16, 'MUHAMMAD THARIQ RIDZWAN', 'thariqR@gmail.com', '01122357467', 'UTM', 'SCIENCE AND TECHNOLOGY', 'Pending', '2026-05-21 23:14:28'),
(17, 'MUHAMMAD ZULAMIN ZAKWAN', 'zulamin@gmail.com', '0112356388', 'UKM', 'BUSINESS', 'Accepted', '2026-05-24 10:31:05');

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
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
