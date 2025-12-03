-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2025 at 06:27 PM
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
-- Database: `mdrrmoweb_dbb`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_mdrrmos`
--

CREATE TABLE `about_mdrrmos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ambulances`
--

CREATE TABLE `ambulances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `destination_latitude` decimal(10,7) DEFAULT NULL,
  `destination_longitude` decimal(10,7) DEFAULT NULL,
  `destination_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ambulances`
--

INSERT INTO `ambulances` (`id`, `name`, `latitude`, `longitude`, `status`, `created_at`, `updated_at`, `destination_latitude`, `destination_longitude`, `destination_updated_at`) VALUES
(1, 'AMBULANCE 1', 14.2259949, 120.9696230, 'Out', '2025-11-05 23:30:41', '2025-11-21 06:40:46', 14.2294571, 120.9714031, NULL),
(2, 'AMBULANCE 2', NULL, NULL, 'Out', '2025-11-06 21:59:59', '2025-11-18 00:42:22', 14.2083483, 120.9750938, NULL),
(3, 'AMBULANCE 3', NULL, NULL, 'Available', '2025-11-18 00:41:02', '2025-11-18 00:41:02', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ambulance_billings`
--

CREATE TABLE `ambulance_billings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `ambulance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','completed','canceled') NOT NULL DEFAULT 'active',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_stops`
--

CREATE TABLE `assignment_stops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `priority` enum('high','normal','low') NOT NULL DEFAULT 'normal',
  `status` enum('pending','completed','canceled') NOT NULL DEFAULT 'pending',
  `sequence` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_info` varchar(255) NOT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time` time NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carousels`
--

CREATE TABLE `carousels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `case_num` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'Medium',
  `address` text NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `to_go_to_address` varchar(255) DEFAULT NULL,
  `to_go_to_landmark` varchar(255) DEFAULT NULL,
  `to_go_to_latitude` decimal(11,8) DEFAULT NULL,
  `to_go_to_longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `driver` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `ambulance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_num`, `status`, `name`, `contact`, `age`, `date_of_birth`, `type`, `priority`, `address`, `landmark`, `destination`, `to_go_to_address`, `to_go_to_landmark`, `to_go_to_latitude`, `to_go_to_longitude`, `latitude`, `longitude`, `timestamp`, `driver`, `contact_number`, `ambulance_id`, `driver_accepted`, `notification_sent`, `created_at`, `updated_at`, `completed_at`) VALUES
(1, 'Completed', 'jay', '242342', NULL, NULL, 'NVAT', 'Medium', 'Kalubkob, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Yakal Street, Poblacion V, Banaba, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', 'Yakal Street, Poblacion V, Banaba, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.21799287, 120.96204758, 14.21699516, 120.95209122, '2025-11-07 07:48:25', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 05:12:11', '2025-11-06 23:48:25', '2025-11-06 23:48:25'),
(2, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Poblacion I, Malaking Tatiao, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22667675, 120.97025348, 14.23078093, 120.98316193, '2025-11-07 07:32:27', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:18:33', '2025-11-06 23:32:27', '2025-11-06 23:32:27'),
(3, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Poblacion II, Banaba, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'M. H. del Pilar Street, Sabutan, Silang, Cavite, Calabarzon, 4118, Philippines', 'M. H. del Pilar Street, Sabutan, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22911817, 120.97354889, 14.22354784, 120.96925735, '2025-11-07 07:47:48', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:41:58', '2025-11-06 23:47:48', '2025-11-06 23:47:48'),
(4, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'FiberBlaze Silang, 1, J. P. Rizal Street, Carranza Compound, Poblacion I, Silang, Cavite, Calabarzon, 4118, Philippines', 'FiberBlaze Silang, 1, J. P. Rizal Street, Carranza Compound, Poblacion I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22443143, 120.97397804, 14.22505498, 120.96940756, '2025-11-07 07:47:59', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:43:14', '2025-11-06 23:47:59', '2025-11-06 23:47:59'),
(5, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'P. Montoya Street, Poblacion II, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', 'P. Montoya Street, Poblacion II, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22655202, 120.97200394, 14.22648967, 120.96975088, '2025-11-07 07:48:17', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:43:52', '2025-11-06 23:48:17', '2025-11-06 23:48:17'),
(6, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'M. H. del Pilar Street, San Vicente I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22653321, 120.97005129, 14.22701126, 120.97382784, '2025-11-07 07:44:44', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:44:25', '2025-11-06 23:44:44', '2025-11-06 23:44:44'),
(7, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Poblacion I, Malaking Tatiao, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22613044, 120.97011566, 14.22845833, 120.97904205, '2025-11-07 07:49:11', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:48:54', '2025-11-06 23:49:11', '2025-11-06 23:49:11'),
(8, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Iba Road, Carranza Compound, San Miguel I, Malaking Tatiao, Silang, Cavite, Calabarzon, 4118, Philippines', 'Iba Road, Carranza Compound, San Miguel I, Malaking Tatiao, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22629287, 120.97723961, 14.22562776, 120.96968651, '2025-11-07 08:04:16', 'Jay Mark', '242342', 1, 1, 1, '2025-11-06 23:49:41', '2025-11-07 00:04:16', '2025-11-07 00:04:16'),
(9, 'Completed', 'jay', '242342', NULL, NULL, NULL, 'Medium', 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22640224, 120.96991181, 14.22761768, 120.97090960, '2025-11-07 08:21:22', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:17:24', '2025-11-07 00:21:22', '2025-11-07 00:21:22'),
(10, 'Completed', 'jay', '242342', NULL, NULL, NULL, 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Sabutan, Silang, Cavite, Calabarzon, 4118, Philippines', 'Sabutan, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.23311540, 120.97363472, 14.22641743, 120.96986890, '2025-11-07 08:23:53', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:22:41', '2025-11-07 00:23:53', '2025-11-07 00:23:53'),
(11, 'Completed', 'jay', '242342', NULL, NULL, NULL, 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22800881, 120.97126365, 14.22634752, 120.97002983, '2025-11-07 08:24:47', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:24:17', '2025-11-07 00:24:47', '2025-11-07 00:24:47'),
(12, 'Completed', 'jay', '242342', NULL, NULL, NULL, 'Medium', 'Villanueva Street, Poblacion II, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22676354, 120.97005129, 14.22840553, 120.97260475, '2025-11-07 08:27:47', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:24:58', '2025-11-07 00:27:47', '2025-11-07 00:27:47'),
(13, 'Completed', 'jay', '242342', NULL, NULL, NULL, 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22633353, 120.97072721, 14.22643746, 120.96993327, '2025-11-07 08:29:39', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:28:05', '2025-11-07 00:29:39', '2025-11-07 00:29:39'),
(14, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22655764, 120.97115636, 14.22642774, 120.96988499, '2025-11-07 08:41:20', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:40:41', '2025-11-07 00:41:20', '2025-11-07 00:41:20'),
(15, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Poblacion I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22642814, 120.96990108, 14.22627733, 120.97094715, '2025-11-07 08:42:34', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 00:41:48', '2025-11-07 00:42:34', '2025-11-07 00:42:34'),
(16, 'Completed', 'jay', '242342', NULL, NULL, 'COORDINATION', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'P. Montoya Street, Poblacion II, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', 'P. Montoya Street, Poblacion II, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22722064, 120.97172499, 14.22643314, 120.96987963, '2025-11-08 07:00:47', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 22:59:45', '2025-11-07 23:00:47', '2025-11-07 23:00:47'),
(17, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'San Vicente I, Biluso, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22639138, 120.96991181, 14.23319854, 120.96977234, '2025-11-08 07:01:47', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 23:01:18', '2025-11-07 23:01:47', '2025-11-07 23:01:47'),
(18, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22665608, 120.97074330, 14.22641186, 120.96990645, '2025-11-08 07:19:32', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 23:17:50', '2025-11-07 23:19:32', '2025-11-07 23:19:32'),
(19, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22653155, 120.97013712, 14.23078756, 120.97080231, '2025-11-08 07:26:36', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 23:26:16', '2025-11-07 23:26:36', '2025-11-07 23:26:36'),
(20, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, Poblacion I, Banaba, Biluso, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22669984, 120.96996546, 14.22888223, 120.97039461, '2025-11-08 07:29:32', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 23:29:11', '2025-11-07 23:29:32', '2025-11-07 23:29:32'),
(21, 'Completed', 'jay', '242342', NULL, NULL, 'COORDINATION', 'Medium', 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22651040, 120.96992254, 14.22743532, 120.97072721, '2025-11-08 07:41:20', 'Jay Mark', '242342', 1, 1, 1, '2025-11-07 23:40:48', '2025-11-07 23:41:20', '2025-11-07 23:41:20'),
(22, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22640162, 120.96992254, 14.22718624, 120.96979916, '2025-11-08 08:24:43', 'Jay Mark', '242342', 1, 1, 1, '2025-11-08 00:24:17', '2025-11-08 00:24:43', '2025-11-08 00:24:43'),
(23, 'Completed', 'jay', '242342', NULL, NULL, NULL, 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22643861, 120.96999228, 14.22767530, 120.97000301, '2025-11-08 08:29:46', 'Jay Mark', '242342', 1, 1, 1, '2025-11-08 00:29:07', '2025-11-08 00:29:46', '2025-11-08 00:29:46'),
(24, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22739723, 120.97045898, 14.22666976, 120.96992254, '2025-11-15 07:14:05', 'Jay Mark', '242342', 1, 1, 1, '2025-11-14 22:58:05', '2025-11-14 23:14:05', '2025-11-14 23:14:05'),
(25, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22660667, 120.96986890, 14.22542193, 120.96918225, '2025-11-15 07:20:28', 'Jay Mark', '242342', 1, 1, 1, '2025-11-14 23:15:06', '2025-11-14 23:20:28', '2025-11-14 23:20:28'),
(26, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22660667, 120.96986890, 14.22542193, 120.96918225, '2025-11-15 07:20:22', 'Jay Mark', '242342', 1, 1, 1, '2025-11-14 23:15:10', '2025-11-14 23:20:22', '2025-11-14 23:20:22'),
(27, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'San Miguel I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22692952, 120.96994400, 14.22882040, 120.97226143, '2025-11-17 06:31:15', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 22:30:54', '2025-11-16 22:31:15', '2025-11-16 22:31:15'),
(28, 'Completed', 'jay', '242342', NULL, NULL, 'COORDINATION', 'Medium', 'San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22640784, 120.96987426, 14.22732173, 120.97060919, '2025-11-17 06:37:34', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 22:37:21', '2025-11-16 22:37:34', '2025-11-16 22:37:34'),
(29, 'Completed', 'jay', '242342', NULL, NULL, 'COORDINATION', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22542993, 120.96922517, 14.22648476, 120.96993864, '2025-11-17 06:44:28', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 22:44:10', '2025-11-16 22:44:28', '2025-11-16 22:44:28'),
(30, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Poblacion V, Banaba, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22676277, 120.97011566, 14.22239792, 120.96616745, '2025-11-17 06:55:44', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 22:55:29', '2025-11-16 22:55:44', '2025-11-16 22:55:44'),
(31, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Villa Julia, Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22647019, 120.96995473, 14.22734315, 120.96852779, '2025-11-17 07:01:46', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 23:01:26', '2025-11-16 23:01:46', '2025-11-16 23:01:46'),
(32, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Swimming Pool, Yakal Street, Poblacion II, Banaba, Lucsuhin, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22647969, 120.96996546, 14.22465062, 120.97048044, '2025-11-17 07:08:26', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 23:08:08', '2025-11-16 23:08:26', '2025-11-16 23:08:26'),
(33, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22645944, 120.96992254, 14.22782131, 120.97000837, '2025-11-17 07:14:23', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 23:14:00', '2025-11-16 23:14:23', '2025-11-16 23:14:23'),
(34, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Jemariel Hardware, Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22640726, 120.96990108, 14.22742052, 120.96968114, '2025-11-17 07:20:56', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 23:20:35', '2025-11-16 23:20:56', '2025-11-16 23:20:56'),
(35, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', 'Aguinaldo Highway, San Vicente II, Banaba, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22651083, 120.96989036, 14.22792419, 120.96979916, '2025-11-17 07:26:52', 'Jay Mark', '242342', 1, 1, 1, '2025-11-16 23:26:36', '2025-11-16 23:26:52', '2025-11-16 23:26:52'),
(36, 'Completed', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Tatiao Road, Malaking Tatiao, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Pulong Bunga Road, Tubuan I, Balite I, Silang, Cavite, Calabarzon, 4118, Philippines', 'Pulong Bunga Road, Tubuan I, Balite I, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.20834827, 120.97509384, 14.22281520, 120.98590851, '2025-11-18 08:44:50', NULL, '242342', NULL, 0, 1, '2025-11-18 00:42:22', '2025-11-18 00:44:50', '2025-11-18 00:44:50'),
(37, 'Accepted', 'jay', '242342', NULL, NULL, 'TRAINING', 'Medium', 'Tatiao Road, Malaking Tatiao, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 'Kapitan Sayas Street, Sabutan, Silang, Cavite, Calabarzon, 4118, Philippines', 'Kapitan Sayas Street, Sabutan, Silang, Cavite, Calabarzon, 4118, Philippines', NULL, 14.22945711, 120.97140312, 14.23461162, 120.98796844, '2025-11-21 14:19:52', 'Jay Mark', '242342', 1, 1, 1, '2025-11-21 06:19:22', '2025-11-21 06:19:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `case_ambulances`
--

CREATE TABLE `case_ambulances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_num` bigint(20) UNSIGNED NOT NULL,
  `ambulance_id` bigint(20) UNSIGNED NOT NULL,
  `driver_accepted` tinyint(1) NOT NULL DEFAULT 0,
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `case_ambulances`
--

INSERT INTO `case_ambulances` (`id`, `case_num`, `ambulance_id`, `driver_accepted`, `notification_sent`, `assigned_at`, `accepted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2025-11-06 05:12:11', '2025-11-06 05:15:20', '2025-11-06 05:12:11', '2025-11-06 05:15:20'),
(2, 2, 1, 1, 1, '2025-11-06 23:18:33', '2025-11-06 23:24:55', '2025-11-06 23:18:33', '2025-11-06 23:24:55'),
(3, 3, 1, 1, 1, '2025-11-06 23:41:58', '2025-11-06 23:42:06', '2025-11-06 23:41:58', '2025-11-06 23:42:06'),
(4, 4, 1, 1, 1, '2025-11-06 23:43:14', '2025-11-06 23:43:25', '2025-11-06 23:43:14', '2025-11-06 23:43:25'),
(5, 5, 1, 1, 1, '2025-11-06 23:43:52', '2025-11-06 23:43:57', '2025-11-06 23:43:52', '2025-11-06 23:43:57'),
(6, 6, 1, 1, 1, '2025-11-06 23:44:25', '2025-11-06 23:44:37', '2025-11-06 23:44:25', '2025-11-06 23:44:37'),
(7, 7, 1, 1, 1, '2025-11-06 23:48:54', '2025-11-06 23:48:58', '2025-11-06 23:48:54', '2025-11-06 23:48:58'),
(8, 8, 1, 1, 1, '2025-11-06 23:49:41', '2025-11-06 23:49:50', '2025-11-06 23:49:41', '2025-11-06 23:49:50'),
(9, 9, 1, 1, 1, '2025-11-07 00:17:24', '2025-11-07 00:17:32', '2025-11-07 00:17:24', '2025-11-07 00:17:32'),
(10, 10, 1, 1, 1, '2025-11-07 00:22:41', '2025-11-07 00:22:45', '2025-11-07 00:22:41', '2025-11-07 00:22:45'),
(11, 11, 1, 1, 1, '2025-11-07 00:24:17', '2025-11-07 00:24:23', '2025-11-07 00:24:17', '2025-11-07 00:24:23'),
(12, 12, 1, 1, 1, '2025-11-07 00:24:58', '2025-11-07 00:25:05', '2025-11-07 00:24:58', '2025-11-07 00:25:05'),
(13, 13, 1, 1, 1, '2025-11-07 00:28:05', '2025-11-07 00:28:13', '2025-11-07 00:28:05', '2025-11-07 00:28:13'),
(14, 14, 1, 1, 1, '2025-11-07 00:40:41', '2025-11-07 00:40:50', '2025-11-07 00:40:41', '2025-11-07 00:40:50'),
(15, 15, 1, 1, 1, '2025-11-07 00:41:48', '2025-11-07 00:41:56', '2025-11-07 00:41:48', '2025-11-07 00:41:56'),
(16, 16, 1, 1, 1, '2025-11-07 22:59:45', '2025-11-07 22:59:59', '2025-11-07 22:59:45', '2025-11-07 22:59:59'),
(17, 17, 1, 1, 1, '2025-11-07 23:01:18', '2025-11-07 23:01:23', '2025-11-07 23:01:18', '2025-11-07 23:01:23'),
(18, 18, 1, 1, 1, '2025-11-07 23:17:50', '2025-11-07 23:18:14', '2025-11-07 23:17:50', '2025-11-07 23:18:14'),
(19, 19, 1, 1, 1, '2025-11-07 23:26:16', '2025-11-07 23:26:26', '2025-11-07 23:26:16', '2025-11-07 23:26:26'),
(20, 20, 1, 1, 1, '2025-11-07 23:29:11', '2025-11-07 23:29:18', '2025-11-07 23:29:11', '2025-11-07 23:29:18'),
(21, 21, 1, 1, 1, '2025-11-07 23:40:48', '2025-11-07 23:40:54', '2025-11-07 23:40:48', '2025-11-07 23:40:54'),
(22, 22, 1, 1, 1, '2025-11-08 00:24:17', '2025-11-08 00:24:23', '2025-11-08 00:24:17', '2025-11-08 00:24:23'),
(23, 23, 1, 1, 1, '2025-11-08 00:29:07', '2025-11-08 00:29:14', '2025-11-08 00:29:07', '2025-11-08 00:29:14'),
(24, 24, 1, 1, 1, '2025-11-14 22:58:05', '2025-11-14 22:58:18', '2025-11-14 22:58:05', '2025-11-14 22:58:18'),
(25, 25, 1, 1, 1, '2025-11-14 23:15:06', '2025-11-14 23:15:42', '2025-11-14 23:15:06', '2025-11-14 23:15:42'),
(26, 26, 1, 1, 1, '2025-11-14 23:15:10', '2025-11-14 23:15:36', '2025-11-14 23:15:10', '2025-11-14 23:15:36'),
(27, 27, 1, 1, 1, '2025-11-16 22:30:54', '2025-11-16 22:31:04', '2025-11-16 22:30:54', '2025-11-16 22:31:04'),
(28, 28, 1, 1, 1, '2025-11-16 22:37:21', '2025-11-16 22:37:28', '2025-11-16 22:37:21', '2025-11-16 22:37:28'),
(29, 29, 1, 1, 1, '2025-11-16 22:44:10', '2025-11-16 22:44:17', '2025-11-16 22:44:10', '2025-11-16 22:44:17'),
(30, 30, 1, 1, 1, '2025-11-16 22:55:29', '2025-11-16 22:55:34', '2025-11-16 22:55:29', '2025-11-16 22:55:34'),
(31, 31, 1, 1, 1, '2025-11-16 23:01:26', '2025-11-16 23:01:34', '2025-11-16 23:01:26', '2025-11-16 23:01:34'),
(32, 32, 1, 1, 1, '2025-11-16 23:08:08', '2025-11-16 23:08:16', '2025-11-16 23:08:08', '2025-11-16 23:08:16'),
(33, 33, 1, 1, 1, '2025-11-16 23:14:00', '2025-11-16 23:14:11', '2025-11-16 23:14:00', '2025-11-16 23:14:11'),
(34, 34, 1, 1, 1, '2025-11-16 23:20:35', '2025-11-16 23:20:46', '2025-11-16 23:20:35', '2025-11-16 23:20:46'),
(35, 35, 1, 1, 1, '2025-11-16 23:26:36', '2025-11-16 23:26:41', '2025-11-16 23:26:36', '2025-11-16 23:26:41'),
(36, 36, 2, 0, 1, '2025-11-18 00:42:22', NULL, '2025-11-18 00:42:22', '2025-11-18 00:42:22'),
(37, 37, 1, 1, 1, '2025-11-21 06:19:22', '2025-11-21 06:19:52', '2025-11-21 06:19:22', '2025-11-21 06:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `case_rejections`
--

CREATE TABLE `case_rejections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_num` bigint(20) UNSIGNED NOT NULL,
  `ambulance_id` bigint(20) UNSIGNED NOT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `rejected_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_carousels`
--

CREATE TABLE `dashboard_carousels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ambulance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `availability_status` enum('online','offline','busy','on_break') NOT NULL DEFAULT 'offline',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `certifications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certifications`)),
  `skills` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`skills`)),
  `notes` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `ambulance_id`, `name`, `email`, `phone`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `license_number`, `license_expiry`, `employee_id`, `hire_date`, `photo`, `date_of_birth`, `gender`, `availability_status`, `last_seen_at`, `is_available`, `certifications`, `skills`, `notes`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jay Mark', 'jaymroce@gmail.com', '09471660478', 'Silang, Cavite', 'Jay Mark', '09471660478', NULL, NULL, '545445', NULL, NULL, NULL, 'male', 'online', '2025-11-21 06:40:47', 0, '[]', '[]', NULL, '$2y$10$wAxrLkMT/PQl3sUJ3FOl3eG7WPAg5D2WShNEZoHwI49RALLAJeTWG', 'active', NULL, '2025-11-05 21:46:27', '2025-11-21 06:40:47'),
(2, NULL, 'Prince P Nipaya', 'prince@gmail.com', '1223232332', 'Dasmarinas, Cavite', 'Jay Mark', '09471660478', NULL, NULL, '6565767', NULL, NULL, NULL, NULL, 'offline', NULL, 1, '[]', '[]', NULL, '$2y$10$PYnwji0pbnhGzWbXx44hY.vN4T6NAnF0EHkC/epSbHsHoK2wfr0w6', 'active', NULL, '2025-11-06 21:59:47', '2025-11-18 00:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `driver_ambulance_pairings`
--

CREATE TABLE `driver_ambulance_pairings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `ambulance_id` bigint(20) UNSIGNED NOT NULL,
  `pairing_date` date NOT NULL,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `driver_ambulance_pairings`
--

INSERT INTO `driver_ambulance_pairings` (`id`, `driver_id`, `ambulance_id`, `pairing_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5, 1, 1, '2025-11-06', 'completed', NULL, '2025-11-06 21:56:19', '2025-11-06 21:56:32'),
(9, 1, 1, '2025-11-06', 'cancelled', NULL, '2025-11-07 01:16:04', '2025-11-07 01:16:15'),
(10, 1, 1, '2025-11-06', 'active', NULL, '2025-11-07 23:32:07', '2025-11-07 23:32:07'),
(11, 2, 2, '2025-11-06', 'completed', NULL, '2025-11-07 23:37:14', '2025-11-18 00:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `driver_medic_pairings`
--

CREATE TABLE `driver_medic_pairings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `medic_id` bigint(20) UNSIGNED NOT NULL,
  `pairing_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_reports`
--

CREATE TABLE `emergency_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medics`
--

CREATE TABLE `medics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medics`
--

INSERT INTO `medics` (`id`, `name`, `email`, `phone`, `license_number`, `specialization`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ahlen', 'noemail+1762410491e9tvAH@example.local', '6557567545', NULL, 'ANKAJNS', 'active', '2025-11-05 22:28:11', '2025-11-05 22:32:42'),
(3, 'prins', 'noemail+1762410544Hhc6jz@example.local', '46577676', NULL, 'ANKAJNS', 'active', '2025-11-05 22:29:04', '2025-11-05 22:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_07_18_063430_create_emergency_reports_table', 1),
(6, '2025_07_18_075058_create_ambulance_billings_table', 1),
(7, '2025_07_18_082619_create_dashboard_carousels_table', 1),
(8, '2025_07_20_070818_create_carousels_table', 1),
(9, '2025_07_20_071919_create_mission_visions_table', 1),
(10, '2025_07_20_071929_create_about_mdrrmos_table', 1),
(11, '2025_07_22_075609_create_officials_table', 1),
(12, '2025_07_22_081611_create_trainings_table', 1),
(13, '2025_07_23_083912_create_ambulances_table', 1),
(14, '2025_07_23_100251_create_drivers_table', 1),
(15, '2025_07_23_102138_add_ambulance_id_to_drivers_table', 1),
(16, '2025_07_26_065022_add_destination_to_ambulances_table', 1),
(17, '2025_08_03_072705_create_services_table', 1),
(18, '2025_08_03_074131_add_category_to_services_table', 1),
(19, '2025_08_03_081032_create_reviews_table', 1),
(20, '2025_08_03_081057_create_bookings_table', 1),
(21, '2025_08_03_082858_add_status_to_bookings_table', 1),
(22, '2025_08_03_083836_add_email_to_bookings_table', 1),
(23, '2025_09_12_000001_create_assignments_table', 1),
(24, '2025_09_12_000002_create_assignment_stops_table', 1),
(25, '2025_09_13_064007_create_medics_table', 1),
(26, '2025_09_13_064023_create_driver_medic_pairings_table', 1),
(27, '2025_09_13_064030_create_driver_ambulance_pairings_table', 1),
(28, '2025_09_13_064606_add_status_to_drivers_table', 1),
(29, '2025_09_13_064631_create_pairing_logs_table', 1),
(30, '2025_09_13_065938_remove_time_fields_from_driver_ambulance_pairings_table', 1),
(31, '2025_09_14_073423_add_unique_constraints_to_pairings', 1),
(32, '2025_09_14_075723_fix_pairing_constraints_logic', 1),
(33, '2025_09_14_080128_fix_constraints_with_status', 1),
(34, '2025_09_14_084035_add_enhanced_fields_to_drivers_table', 1),
(35, '2025_09_14_090352_fix_drivers_status_constraint', 1),
(36, '2025_09_21_054346_create_cases_table', 1),
(37, '2025_09_21_060531_add_ambulance_id_to_cases_table', 1),
(38, '2025_09_21_062231_fix_cases_table_primary_key', 1),
(39, '2025_09_21_070223_add_notification_fields_to_cases_table', 1),
(40, '2025_09_21_083644_add_completed_at_to_cases_table', 1),
(41, '2025_09_27_083743_create_saved_locations_table', 1),
(42, '2025_09_30_120000_add_destination_fields_to_cases_table', 1),
(43, '2025_10_02_070054_create_case_ambulances_table', 1),
(44, '2025_10_02_084350_create_case_rejections_table', 1),
(45, '2025_10_19_071938_add_age_and_date_of_birth_to_cases_table', 1),
(46, '2025_10_19_072045_add_priority_to_cases_table', 1),
(47, '2025_10_25_071440_add_location_fields_to_drivers_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mission_visions`
--

CREATE TABLE `mission_visions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pairing_logs`
--

CREATE TABLE `pairing_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pairing_type` varchar(255) NOT NULL,
  `pairing_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pairing_logs`
--

INSERT INTO `pairing_logs` (`id`, `pairing_type`, `pairing_id`, `action`, `old_data`, `new_data`, `admin_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'driver_ambulance', 1, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-06T07:30:55.000000Z\",\"created_at\":\"2025-11-06T07:30:55.000000Z\",\"id\":1}', 3, 'Pairing created by admin', '2025-11-05 23:30:55', '2025-11-05 23:30:55'),
(2, 'driver_ambulance', 1, 'completed', '{\"id\":1,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-06T07:30:55.000000Z\",\"updated_at\":\"2025-11-06T07:30:55.000000Z\"}', '{\"id\":1,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"completed\",\"notes\":null,\"created_at\":\"2025-11-06T07:30:55.000000Z\",\"updated_at\":\"2025-11-07T05:35:48.000000Z\"}', 3, 'Pairing completed by admin', '2025-11-06 21:35:48', '2025-11-06 21:35:48'),
(3, 'driver_ambulance', 2, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T05:49:32.000000Z\",\"created_at\":\"2025-11-07T05:49:32.000000Z\",\"id\":2}', 3, 'Pairing created by admin', '2025-11-06 21:49:32', '2025-11-06 21:49:32'),
(4, 'driver_ambulance', 2, 'cancelled', '{\"id\":2,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T05:49:32.000000Z\",\"updated_at\":\"2025-11-07T05:49:32.000000Z\"}', '{\"id\":2,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"cancelled\",\"notes\":null,\"created_at\":\"2025-11-07T05:49:32.000000Z\",\"updated_at\":\"2025-11-07T05:49:49.000000Z\"}', 3, 'Pairing cancelled by admin', '2025-11-06 21:49:49', '2025-11-06 21:49:49'),
(5, 'driver_ambulance', 3, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T05:50:13.000000Z\",\"created_at\":\"2025-11-07T05:50:13.000000Z\",\"id\":3}', 3, 'Pairing created by admin', '2025-11-06 21:50:13', '2025-11-06 21:50:13'),
(6, 'driver_ambulance', 3, 'completed', '{\"id\":3,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T05:50:13.000000Z\",\"updated_at\":\"2025-11-07T05:50:13.000000Z\"}', '{\"id\":3,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"completed\",\"notes\":null,\"created_at\":\"2025-11-07T05:50:13.000000Z\",\"updated_at\":\"2025-11-07T05:55:36.000000Z\"}', 3, 'Pairing completed by admin', '2025-11-06 21:55:36', '2025-11-06 21:55:36'),
(7, 'driver_ambulance', 4, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T05:56:00.000000Z\",\"created_at\":\"2025-11-07T05:56:00.000000Z\",\"id\":4}', 3, 'Pairing created by admin', '2025-11-06 21:56:01', '2025-11-06 21:56:01'),
(8, 'driver_ambulance', 4, 'cancelled', '{\"id\":4,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T05:56:00.000000Z\",\"updated_at\":\"2025-11-07T05:56:00.000000Z\"}', '{\"id\":4,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"cancelled\",\"notes\":null,\"created_at\":\"2025-11-07T05:56:00.000000Z\",\"updated_at\":\"2025-11-07T05:56:12.000000Z\"}', 3, 'Pairing cancelled by admin', '2025-11-06 21:56:12', '2025-11-06 21:56:12'),
(9, 'driver_ambulance', 5, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T05:56:19.000000Z\",\"created_at\":\"2025-11-07T05:56:19.000000Z\",\"id\":5}', 3, 'Pairing created by admin', '2025-11-06 21:56:19', '2025-11-06 21:56:19'),
(10, 'driver_ambulance', 5, 'completed', '{\"id\":5,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T05:56:19.000000Z\",\"updated_at\":\"2025-11-07T05:56:19.000000Z\"}', '{\"id\":5,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"completed\",\"notes\":null,\"created_at\":\"2025-11-07T05:56:19.000000Z\",\"updated_at\":\"2025-11-07T05:56:32.000000Z\"}', 3, 'Pairing completed by admin', '2025-11-06 21:56:32', '2025-11-06 21:56:32'),
(11, 'driver_ambulance', 6, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T06:00:28.000000Z\",\"created_at\":\"2025-11-07T06:00:28.000000Z\",\"id\":6}', 3, 'Pairing created by admin', '2025-11-06 22:00:28', '2025-11-06 22:00:28'),
(12, 'driver_ambulance', 7, 'created', NULL, '{\"driver_id\":\"2\",\"ambulance_id\":\"2\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T06:08:50.000000Z\",\"created_at\":\"2025-11-07T06:08:50.000000Z\",\"id\":7}', 3, 'Pairing created by admin', '2025-11-06 22:08:50', '2025-11-06 22:08:50'),
(13, 'driver_ambulance', 7, 'completed', '{\"id\":7,\"driver_id\":2,\"ambulance_id\":2,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T06:08:50.000000Z\",\"updated_at\":\"2025-11-07T06:08:50.000000Z\"}', '{\"id\":7,\"driver_id\":2,\"ambulance_id\":2,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"completed\",\"notes\":null,\"created_at\":\"2025-11-07T06:08:50.000000Z\",\"updated_at\":\"2025-11-07T06:08:56.000000Z\"}', 3, 'Pairing completed by admin', '2025-11-06 22:08:56', '2025-11-06 22:08:56'),
(14, 'driver_ambulance', 6, 'cancelled', '{\"id\":6,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T06:00:28.000000Z\",\"updated_at\":\"2025-11-07T06:00:28.000000Z\"}', '{\"id\":6,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"cancelled\",\"notes\":null,\"created_at\":\"2025-11-07T06:00:28.000000Z\",\"updated_at\":\"2025-11-07T06:09:00.000000Z\"}', 3, 'Pairing cancelled by admin', '2025-11-06 22:09:00', '2025-11-06 22:09:00'),
(15, 'driver_ambulance', 8, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T08:02:52.000000Z\",\"created_at\":\"2025-11-07T08:02:52.000000Z\",\"id\":8}', 3, 'Pairing created by admin', '2025-11-07 00:02:52', '2025-11-07 00:02:52'),
(16, 'driver_ambulance', 8, 'cancelled', '{\"id\":8,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T08:02:52.000000Z\",\"updated_at\":\"2025-11-07T08:02:52.000000Z\"}', '{\"id\":8,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"cancelled\",\"notes\":null,\"created_at\":\"2025-11-07T08:02:52.000000Z\",\"updated_at\":\"2025-11-07T09:15:32.000000Z\"}', 3, 'Pairing cancelled by admin', '2025-11-07 01:15:32', '2025-11-07 01:15:32'),
(17, 'driver_ambulance', 9, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-07T09:16:04.000000Z\",\"created_at\":\"2025-11-07T09:16:04.000000Z\",\"id\":9}', 3, 'Pairing created by admin', '2025-11-07 01:16:04', '2025-11-07 01:16:04'),
(18, 'driver_ambulance', 9, 'cancelled', '{\"id\":9,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-07T09:16:04.000000Z\",\"updated_at\":\"2025-11-07T09:16:04.000000Z\"}', '{\"id\":9,\"driver_id\":1,\"ambulance_id\":1,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"cancelled\",\"notes\":null,\"created_at\":\"2025-11-07T09:16:04.000000Z\",\"updated_at\":\"2025-11-07T09:16:15.000000Z\"}', 3, 'Pairing cancelled by admin', '2025-11-07 01:16:15', '2025-11-07 01:16:15'),
(19, 'driver_ambulance', 10, 'created', NULL, '{\"driver_id\":\"1\",\"ambulance_id\":\"1\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-08T07:32:07.000000Z\",\"created_at\":\"2025-11-08T07:32:07.000000Z\",\"id\":10}', 3, 'Pairing created by admin', '2025-11-07 23:32:07', '2025-11-07 23:32:07'),
(20, 'driver_ambulance', 11, 'created', NULL, '{\"driver_id\":\"2\",\"ambulance_id\":\"2\",\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"notes\":null,\"updated_at\":\"2025-11-08T07:37:14.000000Z\",\"created_at\":\"2025-11-08T07:37:14.000000Z\",\"id\":11}', 3, 'Pairing created by admin', '2025-11-07 23:37:14', '2025-11-07 23:37:14'),
(21, 'driver_ambulance', 11, 'completed', '{\"id\":11,\"driver_id\":2,\"ambulance_id\":2,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"active\",\"notes\":null,\"created_at\":\"2025-11-08T07:37:14.000000Z\",\"updated_at\":\"2025-11-08T07:37:14.000000Z\"}', '{\"id\":11,\"driver_id\":2,\"ambulance_id\":2,\"pairing_date\":\"2025-11-06T00:00:00.000000Z\",\"status\":\"completed\",\"notes\":null,\"created_at\":\"2025-11-08T07:37:14.000000Z\",\"updated_at\":\"2025-11-18T08:40:08.000000Z\"}', 3, 'Pairing completed by admin', '2025-11-18 00:40:08', '2025-11-18 00:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_locations`
--

CREATE TABLE `saved_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainings`
--

CREATE TABLE `trainings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Prince', 'princenipaya@superadmin.com', NULL, '$2y$10$YBaJsFN41f5QwsQxK6PxQOcZRysYnmy1/zEx8IX07pLhJ4VktXMMi', NULL, NULL, NULL),
(2, 'Ahlen', 'ahlencorpuz@superadmin.com', NULL, '$2y$10$wJz9xo25PEKuGaZdw4YOxeQCUL1tgJLqWbGDmzJqmxMwKbw8pG7m2', NULL, NULL, NULL),
(3, 'admin', 'admin1@example.com', NULL, '$2y$10$Z.IkjdCAAqE4CLtABDY4uuAriB36Y4x01/OGVkmOO/bInkJ2yC1k6', 'Qi67ynxo5YOKmrpT8I5OVhkHgb9OuLWRW02ub57wsBW4mbir7oH8J5OBhUgJ', '2025-11-05 21:34:17', '2025-11-14 23:02:38'),
(4, 'ahlen', 'ahlen@gmail.com', NULL, '$2y$10$hO.//Z0ONUK51hL3iaMiOeqmbe4FDILrM9wxPJu7PGZkUevfLJQA.', NULL, '2025-11-07 23:15:51', '2025-11-07 23:15:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_mdrrmos`
--
ALTER TABLE `about_mdrrmos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ambulances`
--
ALTER TABLE `ambulances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ambulance_billings`
--
ALTER TABLE `ambulance_billings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignments_driver_id_foreign` (`driver_id`),
  ADD KEY `assignments_ambulance_id_foreign` (`ambulance_id`);

--
-- Indexes for table `assignment_stops`
--
ALTER TABLE `assignment_stops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_stops_assignment_id_sequence_index` (`assignment_id`,`sequence`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_service_id_foreign` (`service_id`);

--
-- Indexes for table `carousels`
--
ALTER TABLE `carousels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_num`),
  ADD KEY `cases_ambulance_id_foreign` (`ambulance_id`);

--
-- Indexes for table `case_ambulances`
--
ALTER TABLE `case_ambulances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `case_ambulances_case_num_ambulance_id_unique` (`case_num`,`ambulance_id`),
  ADD KEY `case_ambulances_ambulance_id_foreign` (`ambulance_id`);

--
-- Indexes for table `case_rejections`
--
ALTER TABLE `case_rejections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `case_rejections_case_num_ambulance_id_unique` (`case_num`,`ambulance_id`),
  ADD KEY `case_rejections_ambulance_id_foreign` (`ambulance_id`);

--
-- Indexes for table `dashboard_carousels`
--
ALTER TABLE `dashboard_carousels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_email_unique` (`email`),
  ADD KEY `drivers_ambulance_id_foreign` (`ambulance_id`);

--
-- Indexes for table `driver_ambulance_pairings`
--
ALTER TABLE `driver_ambulance_pairings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ambulance_date_status` (`ambulance_id`,`pairing_date`,`status`);

--
-- Indexes for table `driver_medic_pairings`
--
ALTER TABLE `driver_medic_pairings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_medic_date_status` (`medic_id`,`pairing_date`,`status`);

--
-- Indexes for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `medics`
--
ALTER TABLE `medics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medics_email_unique` (`email`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mission_visions`
--
ALTER TABLE `mission_visions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pairing_logs`
--
ALTER TABLE `pairing_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_service_id_foreign` (`service_id`);

--
-- Indexes for table `saved_locations`
--
ALTER TABLE `saved_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_mdrrmos`
--
ALTER TABLE `about_mdrrmos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ambulances`
--
ALTER TABLE `ambulances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ambulance_billings`
--
ALTER TABLE `ambulance_billings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignment_stops`
--
ALTER TABLE `assignment_stops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carousels`
--
ALTER TABLE `carousels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `case_num` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `case_ambulances`
--
ALTER TABLE `case_ambulances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `case_rejections`
--
ALTER TABLE `case_rejections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_carousels`
--
ALTER TABLE `dashboard_carousels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_ambulance_pairings`
--
ALTER TABLE `driver_ambulance_pairings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `driver_medic_pairings`
--
ALTER TABLE `driver_medic_pairings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medics`
--
ALTER TABLE `medics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `mission_visions`
--
ALTER TABLE `mission_visions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pairing_logs`
--
ALTER TABLE `pairing_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_locations`
--
ALTER TABLE `saved_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assignments_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_stops`
--
ALTER TABLE `assignment_stops`
  ADD CONSTRAINT `assignment_stops_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `case_ambulances`
--
ALTER TABLE `case_ambulances`
  ADD CONSTRAINT `case_ambulances_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_ambulances_case_num_foreign` FOREIGN KEY (`case_num`) REFERENCES `cases` (`case_num`) ON DELETE CASCADE;

--
-- Constraints for table `case_rejections`
--
ALTER TABLE `case_rejections`
  ADD CONSTRAINT `case_rejections_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `case_rejections_case_num_foreign` FOREIGN KEY (`case_num`) REFERENCES `cases` (`case_num`) ON DELETE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
