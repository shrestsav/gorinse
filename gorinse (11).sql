-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 27, 2019 at 06:09 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gorinse`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_defaults`
--

DROP TABLE IF EXISTS `app_defaults`;
CREATE TABLE IF NOT EXISTS `app_defaults` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `VAT` int(10) UNSIGNED NOT NULL,
  `delivery_charge` int(10) UNSIGNED NOT NULL,
  `OTP_expiry` int(10) UNSIGNED NOT NULL COMMENT 'Minute',
  `order_time` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `FAQ_link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `online_chat` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `hotline_contact` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_defaults`
--

INSERT INTO `app_defaults` (`id`, `VAT`, `delivery_charge`, `OTP_expiry`, `order_time`, `driver_notes`, `FAQ_link`, `online_chat`, `hotline_contact`, `company_email`, `company_logo`, `created_at`, `updated_at`) VALUES
(1, 5, 105, 5, '[\"00:00 - 01:00\",\"01:00 - 02:00\",\"02:00 - 03:00\",\"03:00 - 04:00\",\"04:00 - 05:00\",\"05:00 - 06:00\",\"06:00 - 07:00\",\"07:00 - 08:00\",\"08:00 - 09:00\",\"09:00 - 10:00\"]', '[\"Hole in pant\",\"Button is missing\",\"Very Rough pant\",\"Tears in Heaven\"]', 'https://www.faq.com', '{\"time\":\"9am - 12pm\",\"url\":\"https://www.online_chat.com\"}', '+9779808225547', 'gorinse@gmail.com', 'company_logo.png', '2019-08-20 04:27:20', '2019-08-21 01:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(4, 'Tops', 'Top clothings like T-Shirt, Shirt, Blouse, Tops and what else', 1, '2019-08-15 00:38:21', '2019-08-15 00:38:21'),
(5, 'Bottoms', 'Bottom clothings like Pant, Skirt, Half Pant', 1, '2019-08-15 00:39:01', '2019-08-15 00:39:01'),
(6, 'Undergarments', NULL, 1, '2019-08-15 00:39:16', '2019-08-15 00:39:16'),
(7, 'Coats', NULL, 1, '2019-08-15 00:39:23', '2019-08-15 00:39:23'),
(8, 'Miscellaneous', NULL, 1, '2019-08-15 00:39:29', '2019-08-15 00:39:29'),
(9, 'ff', 'fff', 1, '2019-08-26 00:54:40', '2019-08-26 00:54:40'),
(10, 'Illiana Roberson', 'Vel ut atque dolor s', 1, '2019-08-26 00:54:59', '2019-08-26 00:54:59'),
(11, 'dsdsdsd', 'dasdasdasd', 1, '2019-08-26 00:55:05', '2019-08-26 00:55:05');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int(10) UNSIGNED DEFAULT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `name`, `description`, `price`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Blouse', NULL, 100, '', 1, '2019-08-15 00:40:24', '2019-08-15 00:40:24'),
(2, 4, 'Shirt', NULL, 100, '', 1, '2019-08-15 00:40:16', '2019-08-15 00:40:16'),
(4, 4, 'T-Shirt', NULL, 100, '', 1, '2019-08-15 00:39:51', '2019-08-15 00:39:51'),
(7, 4, 'Tops', NULL, 100, '', 1, '2019-08-15 00:40:27', '2019-08-15 00:40:27'),
(8, 5, 'Pant', NULL, 100, '', 1, '2019-08-15 00:40:44', '2019-08-15 00:40:44'),
(9, 5, 'Jeans', NULL, 100, '', 1, '2019-08-15 00:40:47', '2019-08-15 00:40:47'),
(10, 5, 'Skirt', NULL, 100, '', 1, '2019-08-15 00:40:50', '2019-08-15 00:40:50'),
(11, 6, 'Mark Woodward', 'Nihil non accusamus', 263, '', 1, '2019-08-15 05:00:34', '2019-08-15 05:00:34'),
(12, 6, 'Colton Melendez', 'Aut totam est magnam', 660, 'Sequi in ullam rem m', 1, '2019-08-26 00:35:41', '2019-08-26 00:35:41'),
(13, 5, 'Jeremy George', 'Magna ullamco sapien', 880, 'Aspernatur in id lib', 1, '2019-08-26 00:45:52', '2019-08-26 00:45:52'),
(14, 7, 'Lana Copeland', 'Non tempora eius ess', 484, 'Illo minim vel commo', 1, '2019-08-26 00:48:25', '2019-08-26 00:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM AUTO_INCREMENT=300 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(295, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:09:55.003200\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562195, 1566562190),
(296, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:10:10.545362\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562210, 1566562205),
(297, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:10:19.783262\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562219, 1566562214),
(298, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:11:06.539222\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562266, 1566562261),
(299, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:11:35.335636\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562295, 1566562290),
(292, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:07:12.710119\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 255, NULL, 1566562035, 1566562035),
(293, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:07:50.230181\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562070, 1566562065),
(294, 'default', '{\"displayName\":\"App\\\\Jobs\\\\PendingNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\PendingNotification\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\PendingNotification\\\":8:{s:11:\\\"\\u0000*\\u0000order_id\\\";i:2;s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2019-08-23 12:09:15.491390\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1566562155, 1566562150);

-- --------------------------------------------------------

--
-- Table structure for table `main_areas`
--

DROP TABLE IF EXISTS `main_areas`;
CREATE TABLE IF NOT EXISTS `main_areas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `main_areas`
--

INSERT INTO `main_areas` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'dfgdfgdfgdfg', '2019-08-26 03:00:44', '2019-08-26 03:00:50', '2019-08-26 03:00:50'),
(2, 'Dubai', '2019-08-26 03:01:46', '2019-08-26 03:04:45', '2019-08-26 03:04:45'),
(3, 'Qatar', '2019-08-26 03:01:51', '2019-08-26 03:05:40', '2019-08-26 03:05:40'),
(4, 'qatars', '2019-08-26 03:06:28', '2019-08-26 03:06:28', NULL),
(5, 'DUBA', '2019-08-26 23:30:10', '2019-08-26 23:30:10', NULL),
(6, 'sfsdfsdf', '2019-08-26 23:30:12', '2019-08-26 23:30:12', NULL),
(7, 'dfdfdfdf', '2019-08-26 23:30:15', '2019-08-26 23:30:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_06_19_082410_entrust_setup_tables', 1),
(32, '2019_08_07_071358_create_orders_table', 11),
(13, '2019_08_12_044431_create_services_table', 3),
(14, '2019_08_12_052708_create_categories_table', 3),
(15, '2019_08_12_060911_create_items_table', 4),
(35, '2019_08_12_065003_create_order_items_table', 13),
(27, '2019_08_12_092002_create_user_addresses_table', 10),
(23, '2019_08_13_090849_create_notifications_table', 9),
(26, '2019_08_11_090343_create_user_details_table', 10),
(34, '2019_08_19_070303_create_app_defaults_table', 12),
(36, '2019_08_23_113421_create_jobs_table', 14),
(38, '2019_08_25_050543_create_payment_cards_table', 15),
(40, '2019_08_26_060924_create_main_areas_table', 16),
(42, '2019_08_26_090050_create_offers_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('b839bbf1-38fc-4cc3-b27b-af40f93a6c50', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler dffdTest 2fasfs\",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 12:04:15\"}', '2019-08-23 06:22:41', '2019-08-23 06:19:15', '2019-08-23 06:22:41'),
('89cb1941-a888-4726-936c-b1370327b0c3', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler dffdTest fasdfasdfasdfasdffasfs\",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 12:03:57\"}', '2019-08-23 06:22:41', '2019-08-23 06:18:57', '2019-08-23 06:22:41'),
('5a1901ee-6058-4b27-9dc8-4f2a0c562ff3', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 12:03:25\"}', '2019-08-23 06:22:41', '2019-08-23 06:18:25', '2019-08-23 06:22:41'),
('3570085f-1370-4089-87df-253c5a5cbd37', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 12:00:21\"}', '2019-08-23 06:22:41', '2019-08-23 06:15:21', '2019-08-23 06:22:41'),
('4d9f059f-109e-4749-8216-1fa12bb9d739', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 12:02:54\"}', '2019-08-23 06:22:41', '2019-08-23 06:17:54', '2019-08-23 06:22:41'),
('590c09e0-8610-42f0-8b2a-41420ed16c6d', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 12:00:14\"}', '2019-08-23 06:22:41', '2019-08-23 06:15:14', '2019-08-23 06:22:41'),
('4dcd64ea-e3ed-41c9-897b-bbe0a0fadbf8', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:58:56\"}', '2019-08-23 06:22:41', '2019-08-23 06:13:56', '2019-08-23 06:22:41'),
('fafeba1e-356c-4374-83ae-330b71f09333', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:56:40\"}', '2019-08-23 06:22:41', '2019-08-23 06:11:40', '2019-08-23 06:22:41'),
('5fa552e1-552e-4c27-821e-1a0e42656572', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:54:49\"}', '2019-08-23 06:22:41', '2019-08-23 06:09:49', '2019-08-23 06:22:41'),
('6315d860-3437-4a63-bc16-fccadceb95e0', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:54:01\"}', '2019-08-23 06:22:41', '2019-08-23 06:09:01', '2019-08-23 06:22:41'),
('2e16e7ad-8b3c-43ce-b8cf-9a2f59476f9b', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:54:00\"}', '2019-08-23 06:22:41', '2019-08-23 06:09:00', '2019-08-23 06:22:41'),
('b40e2d12-2498-4f04-9b99-732544df64e4', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:53:45\"}', '2019-08-23 06:22:41', '2019-08-23 06:08:45', '2019-08-23 06:22:41'),
('f18abd72-6cac-4aa2-8409-c5ce674a3093', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:53:15\"}', '2019-08-23 06:22:41', '2019-08-23 06:08:15', '2019-08-23 06:22:41'),
('a7eb0591-1f8e-4891-a708-ad4ef6ef2858', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:50:44\"}', '2019-08-23 06:22:41', '2019-08-23 06:05:44', '2019-08-23 06:22:41'),
('d4045d70-0c83-4bee-97b8-21234e4e5d35', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:48:27\"}', '2019-08-23 06:03:37', '2019-08-23 06:03:27', '2019-08-23 06:03:37'),
('af3fca8b-3296-4f25-8636-5bb43601b1ae', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:48:52\"}', '2019-08-23 06:05:17', '2019-08-23 06:03:52', '2019-08-23 06:05:17'),
('8dc67544-be77-4bf6-ba78-04aea18f4ca4', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:48:27\"}', '2019-08-23 06:03:37', '2019-08-23 06:03:27', '2019-08-23 06:03:37'),
('bb2d1787-6881-48af-845e-bb4c5b9d9d4e', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:48:27\"}', '2019-08-23 06:03:37', '2019-08-23 06:03:27', '2019-08-23 06:03:37'),
('69b98c8b-98d1-4a46-b574-768ac3d78a1d', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:46:46\"}', '2019-08-23 06:02:27', '2019-08-23 06:01:46', '2019-08-23 06:02:27'),
('7b7d048a-6cc5-4e7f-a757-c4cbdb7584f4', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:45:45\"}', '2019-08-23 06:02:28', '2019-08-23 06:00:45', '2019-08-23 06:02:28'),
('db916f35-9a6b-415a-bf13-2200e8659483', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:45:35\"}', '2019-08-23 06:02:28', '2019-08-23 06:00:35', '2019-08-23 06:02:28'),
('59e36748-436a-46d8-8135-eeb715eac685', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:45:21\"}', '2019-08-23 06:02:28', '2019-08-23 06:00:21', '2019-08-23 06:02:28'),
('a047db9c-edb2-4c98-97e7-c8e11efd404b', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:45:10\"}', '2019-08-23 06:02:28', '2019-08-23 06:00:10', '2019-08-23 06:02:28'),
('dfbd0bc4-4cc0-440e-851f-fd2e594880f3', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:44:59\"}', '2019-08-23 06:02:28', '2019-08-23 05:59:59', '2019-08-23 06:02:28'),
('6c4cc428-4ec4-444c-9ff7-26f693fdd971', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:44:49\"}', '2019-08-23 06:02:28', '2019-08-23 05:59:49', '2019-08-23 06:02:28'),
('fc1a301d-26c8-4e21-ad11-8f0aa88abc26', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:44:42\"}', '2019-08-23 06:02:28', '2019-08-23 05:59:42', '2019-08-23 06:02:28'),
('a484a9a1-f6cc-4a97-aac1-4cbd9c002373', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:44:42\"}', '2019-08-23 06:02:28', '2019-08-23 05:59:42', '2019-08-23 06:02:28'),
('21cb59b5-6305-4597-860f-5c6cdaf2a211', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:43:47\"}', '2019-08-23 06:02:28', '2019-08-23 05:58:47', '2019-08-23 06:02:28'),
('6746ca92-4366-471f-ad1c-3e5251b1682a', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:43:44\"}', '2019-08-23 06:02:28', '2019-08-23 05:58:44', '2019-08-23 06:02:28'),
('3ca11edc-1b91-4859-bfa8-d2e93026574c', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:43:29\"}', '2019-08-23 06:02:28', '2019-08-23 05:58:29', '2019-08-23 06:02:28'),
('14acc21f-4ad0-4a19-9ffc-d559e8b5a3bf', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\" Jobs Scheduler Test \",\"url\":\"$order->id\",\"created_at\":\"2019-08-23 11:43:11\"}', '2019-08-23 06:02:28', '2019-08-23 05:58:11', '2019-08-23 06:02:28'),
('0b940dc4-866c-45b3-b3bb-b84f9c47f538', 'App\\Notifications\\SystemNotification', 'App\\User', 51, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 15\",\"url\":15,\"created_at\":\"2019-08-23 10:34:12\"}', NULL, '2019-08-23 04:49:12', '2019-08-23 04:49:12'),
('070f73ed-3eef-4ae1-82c1-bfb7923a026c', 'App\\Notifications\\SystemNotification', 'App\\User', 34, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 15\",\"url\":15,\"created_at\":\"2019-08-23 10:34:12\"}', NULL, '2019-08-23 04:49:12', '2019-08-23 04:49:12'),
('5eced5da-2a74-4052-b072-9f1eb1f65938', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 15\",\"url\":15,\"created_at\":\"2019-08-23 10:34:11\"}', '2019-08-23 06:02:28', '2019-08-23 04:49:11', '2019-08-23 06:02:28'),
('6d129abb-4921-4001-9922-d63936435df4', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 14\",\"url\":14,\"created_at\":\"2019-08-23 10:33:10\"}', '2019-08-23 06:02:28', '2019-08-23 04:48:10', '2019-08-23 06:02:28'),
('23c4edaa-d8de-4c2a-b0d2-1fdb8d33e078', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 13\",\"url\":13,\"created_at\":\"2019-08-23 10:32:45\"}', '2019-08-23 06:02:28', '2019-08-23 04:47:45', '2019-08-23 06:02:28'),
('8b1b359f-138f-4864-9328-b7049d46a080', 'App\\Notifications\\SystemNotification', 'App\\User', 51, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 12\",\"url\":12,\"created_at\":\"2019-08-22 08:43:08\"}', NULL, '2019-08-22 02:58:08', '2019-08-22 02:58:08'),
('54c7e83b-0476-46b0-ac64-7957a2a70700', 'App\\Notifications\\SystemNotification', 'App\\User', 1, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 12\",\"url\":12,\"created_at\":\"2019-08-22 08:43:01\"}', '2019-08-22 03:06:49', '2019-08-22 02:58:01', '2019-08-22 03:06:49'),
('64addec6-6209-4749-bcc0-0320a5eb3517', 'App\\Notifications\\SystemNotification', 'App\\User', 34, '{\"notifyType\":\"new_order\",\"message\":\"Test just created a new order 12\",\"url\":12,\"created_at\":\"2019-08-22 08:43:08\"}', NULL, '2019-08-22 02:58:08', '2019-08-22 02:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('2a47dfe1decbdf1fcce0b84b9b06fc7957fd7a1ed843afe420096e74e139675a5cc257f2c5479f1b', 1, 2, NULL, '[]', 0, '2019-08-08 23:35:12', '2019-08-08 23:35:12', '2020-08-09 05:20:12'),
('0b907ecb196a6d2c0b8ea576fcfa45ffa38350a7e8b0bc589f416dbb6d7c24468b6f922c75adc0db', 2, 1, 'authToken hola', '[]', 0, '2019-08-08 23:36:12', '2019-08-08 23:36:12', '2020-08-09 05:21:12'),
('2785f0a1af549e9aff711fbbcfa1e57ccc4620996d10b82de9640a0ef75db7a2146505a5fd0500ed', 3, 1, 'authToken hola', '[]', 0, '2019-08-08 23:36:29', '2019-08-08 23:36:29', '2020-08-09 05:21:29'),
('8ddb1b726f6e9b9baa26edad603b7167f9fd9f1172c1a89017c1c5162d19add4acaed750dc74fe5a', 4, 1, 'authToken hola', '[]', 0, '2019-08-08 23:37:01', '2019-08-08 23:37:01', '2020-08-09 05:22:01'),
('6381064fe706a0334d8e8bedc09804189ae4da5d064e1cd7932d0006251d7bdaf9e4005b0634fbb4', 5, 1, 'authToken hola', '[]', 0, '2019-08-08 23:42:23', '2019-08-08 23:42:23', '2020-08-09 05:27:23'),
('c2af42d22247fc4fa145b8c68ef44e5cea5e46eb88bc8167590ddcfa51a15daccfcf070cd7c44956', 6, 1, 'authToken hola', '[]', 0, '2019-08-08 23:43:26', '2019-08-08 23:43:26', '2020-08-09 05:28:26'),
('043cca234da682a7af8fa632864cb16bdecfc41923b43453d84cd2337f085d5560ff37c2a6897657', 9, 1, 'authToken hola', '[]', 0, '2019-08-09 00:12:38', '2019-08-09 00:12:38', '2020-08-09 05:57:38'),
('f0000a1730d31d3573e3be7d896de63750eb05a4254422e6f426b104b59e348f9013ba974ee8c6b0', 16, 1, 'authToken hola', '[]', 0, '2019-08-09 02:55:39', '2019-08-09 02:55:39', '2020-08-09 08:40:39'),
('070c714bb491a21cc72ff6b66a4c5e2ab9637d25fe8df63dd3fd8924a174dd8b43b3e6fb3495eb0f', 17, 1, 'authToken hola', '[]', 0, '2019-08-09 03:04:05', '2019-08-09 03:04:05', '2020-08-09 08:49:05'),
('59fd9874c9ab27c16a79fbfb3319bcb9ac602c6179d25881d19d1337ed4e03bb3d49ce2953b5fdf9', 18, 1, 'authToken hola', '[]', 0, '2019-08-09 03:41:32', '2019-08-09 03:41:32', '2020-08-09 09:26:32'),
('1009c20a0376b1ff1ed77ca0f2c028521be1fe514a8b513b6f836ac84754bdf188310c99c3e4f498', 19, 1, 'authToken hola', '[]', 0, '2019-08-09 03:45:39', '2019-08-09 03:45:39', '2020-08-09 09:30:39'),
('cd9b10b4164d04cfd8384e166ce4db1ab997d22be75a36d7ff7780508dd83622cf4f405af1d37a85', 19, 1, 'authToken hola', '[]', 0, '2019-08-09 04:00:59', '2019-08-09 04:00:59', '2020-08-09 09:45:59'),
('a580cb408c557e6837a826dc8137f1d66512f78c929b746be2a673058f007a116a15695f2215b63a', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:18:03', '2019-08-10 23:18:03', '2020-08-11 05:03:03'),
('33b763ed79d1152888d013d6a41ce57eb0d5289745bf8c46dde71a0001b345dac8c8a9bb3685aa1e', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:18:55', '2019-08-10 23:18:55', '2020-08-11 05:03:55'),
('0ea2f4ef371f48b796d1794649b1215fb178ac2a09458f9ffe61456f3d6612f570f2f66f9bad7b48', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:18:58', '2019-08-10 23:18:58', '2020-08-11 05:03:58'),
('30d156a22f78a6a2641564bdf8a88aac913f2b96125b242775e94871f1fa08a2591ab76c35d45054', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:19:24', '2019-08-10 23:19:24', '2020-08-11 05:04:24'),
('3b37d90119cb1d47469ae838d3200e904675ff4748454eaf0e981c5e5cc81c7271665bab6dd94243', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:19:28', '2019-08-10 23:19:28', '2020-08-11 05:04:28'),
('2dca451968bfbf5f7a0dd132770ead51d38ca0370b0cf3ecb5d8edc94d7c4b62a43603a27b6a23d1', 1, 2, NULL, '[]', 0, '2019-08-10 23:23:42', '2019-08-10 23:23:42', '2020-08-11 05:08:42'),
('d2518b6b234620f3ef301178c319cb474b843e02ec5a6171de4d0767b053ca52f3387de21e28bb1c', 1, 2, NULL, '[]', 0, '2019-08-10 23:23:54', '2019-08-10 23:23:54', '2020-08-11 05:08:54'),
('16a18f12a38d89e4aa3a32a5f82f6f85764997152650c54d5fe441ae9fb182715fa552ed1fb4677c', 1, 2, NULL, '[]', 0, '2019-08-10 23:28:57', '2019-08-10 23:28:57', '2020-08-11 05:13:57'),
('72519685dd7f43b557d845c71dedf6f94b73ff5062a0ef96ed7fce79c4b11b055ddcceca2a8e57e8', 1, 2, NULL, '[]', 0, '2019-08-10 23:30:11', '2019-08-10 23:30:11', '2020-08-11 05:15:11'),
('371af5400084d8531cacf0dec470bd72d152faa02229ce345c0c2aae44c2e27f92c96a5c28bf3093', 26, 1, 'authToken hola', '[]', 0, '2019-08-10 23:33:56', '2019-08-10 23:33:56', '2020-08-11 05:18:56'),
('13a678f030d4814b13948ab5e50a873caa6515495d49ba1e4d7975b0e00bc8e4358ae15793ae1da0', 26, 1, 'authToken hola', '[]', 0, '2019-08-10 23:34:44', '2019-08-10 23:34:44', '2020-08-11 05:19:44'),
('b45c07a972c5be1ff9963c0a2be63835436fee03c83ffdc22c022cc1459b9c8dd77511f720604ce1', 1, 2, NULL, '[]', 1, '2019-08-10 23:46:18', '2019-08-10 23:46:18', '2020-08-11 05:31:18'),
('0433e00aef99e0573c67bb5428e64bd7fb3e38a40d3c6f74cf97828aa662b4a2b72d165bf8f74241', 1, 2, NULL, '[]', 0, '2019-08-10 23:48:05', '2019-08-10 23:48:05', '2020-08-11 05:33:05'),
('a0fe73273544341645d4514b31ba385dd3ef943639690f3fdad9b7fe62340d1c9fbf640e44d1ad0d', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:50:40', '2019-08-10 23:50:40', '2020-08-11 05:35:40'),
('2db3ddecc64de37bc5e5253affcc3ae02c536d7c7416850e5eb24c9f1cd06cb809c77f475af55d51', 1, 1, 'manual token', '[]', 0, '2019-08-10 23:52:49', '2019-08-10 23:52:49', '2020-08-11 05:37:49'),
('97573228fa31ecefad67beb10c2d29b16f915650aec83b5ea7d2b2197f634f4ab98511a02e54caa8', 1, 2, NULL, '[]', 0, '2019-08-11 00:32:23', '2019-08-11 00:32:23', '2020-08-11 06:17:23'),
('500695ec82265025dd96a720ac7f91a0a0c3d84cfdec0b0ea4daaaea27e45cb06f800a037b13a1c6', 1, 2, NULL, '[]', 0, '2019-08-11 00:33:02', '2019-08-11 00:33:02', '2020-08-11 06:18:02'),
('b2cbdce08cd09cbe2bb431235c6efbd0e91257c52674907aae5e8d45fa4aaaa5c998a6811e36878a', 1, 2, NULL, '[]', 0, '2019-08-11 00:33:07', '2019-08-11 00:33:07', '2020-08-11 06:18:07'),
('3e7b7057a623a24d2d21c4dc96244308320f5a6b819b915cf0c038fb3ec9ba062971bcde200c4cd3', 1, 2, NULL, '[]', 0, '2019-08-11 00:33:13', '2019-08-11 00:33:13', '2020-08-11 06:18:13'),
('36dc8b0e556b25c5d807db55c53a476ca36d81b1e6abca782fe4a3a055d2c55580dba3a80759fda2', 1, 2, NULL, '[]', 0, '2019-08-11 00:34:34', '2019-08-11 00:34:34', '2020-08-11 06:19:34'),
('10dbc477c39d0d5247c15b9c7eea49426e98d606e75d8d6e68b589b1fe52ff36351c1e5ced48d210', 1, 2, NULL, '[]', 0, '2019-08-11 00:34:54', '2019-08-11 00:34:54', '2020-08-11 06:19:54'),
('cac9f19e705d069a4a2da1bece168b1f5f2b7c64bdaa479600be28ea768e0f1ccc2b475fa0867097', 1, 2, NULL, '[]', 0, '2019-08-11 00:34:57', '2019-08-11 00:34:57', '2020-08-11 06:19:57'),
('c1e639b5048e968225377942a845710309a749d8aa0ce1c970a376a4b608bb38aa43398fdf70e901', 1, 2, NULL, '[]', 0, '2019-08-11 00:35:01', '2019-08-11 00:35:01', '2020-08-11 06:20:01'),
('23cf2fff2920885d0027db6a5bc08616c9edb5714631b0058c5fb747d59e5c3452d1a3bc1ee38f00', 1, 2, NULL, '[]', 0, '2019-08-11 00:35:05', '2019-08-11 00:35:05', '2020-08-11 06:20:05'),
('1549a8aac1840b94582b6dbf522dbe67e18a74f5fecbf391d7462a098a4aac902b55750d24a77663', 1, 2, NULL, '[]', 0, '2019-08-11 00:35:42', '2019-08-11 00:35:42', '2020-08-11 06:20:42'),
('c57739d1b1ff9e54b3c58e6196d4fe7c8cc66a05b8ad63f5ace760ce926fd291becf3b8b1b0b88d3', 1, 2, NULL, '[]', 0, '2019-08-11 00:38:12', '2019-08-11 00:38:12', '2020-08-11 06:23:12'),
('4775621bc061119fe303d0f8c077f5e9a8e4d394d865fc69d14c75166f1f6b29714b119eb921530d', 1, 2, NULL, '[]', 0, '2019-08-11 00:38:15', '2019-08-11 00:38:15', '2020-08-11 06:23:15'),
('01e9ef310ed97b0d9814de10ffe563db85b19785c9d1d36a3ab27584962b0e19a07dd980d997fba0', 1, 2, NULL, '[]', 0, '2019-08-11 00:38:27', '2019-08-11 00:38:27', '2020-08-11 06:23:27'),
('9829ea70dcea97f514b65639fdc5f2c653812a43a2988979cea86ef2cb12c51abec7bce88210ddf2', 1, 2, NULL, '[]', 0, '2019-08-11 00:39:56', '2019-08-11 00:39:56', '2020-08-11 06:24:56'),
('483ff6371dbf4ffec7daf00f5625612d2bc406b874865b73545f8c0950efc79af25c995a2ff307b7', 1, 2, NULL, '[]', 0, '2019-08-11 00:40:28', '2019-08-11 00:40:28', '2020-08-11 06:25:28'),
('3094f0a1d97a9677f0ebb1b9f56585b3cbe7a68b1d958a5529a15fbb8a534d74dd3e72ccbdee81c1', 1, 2, NULL, '[]', 0, '2019-08-11 00:40:37', '2019-08-11 00:40:37', '2020-08-11 06:25:37'),
('837d067f5687640315595b5d6d62292dd7bb81db28e9561f27b3030485765ebc40a01c1a607a4ad7', 1, 2, NULL, '[]', 0, '2019-08-11 00:40:53', '2019-08-11 00:40:53', '2020-08-11 06:25:53'),
('c0b2dd55751936dd8ad944de2cda469a6df7e558abf6fe86392a20c53c88dd8d7b475bdf5718c75e', 1, 2, NULL, '[]', 0, '2019-08-11 00:41:01', '2019-08-11 00:41:01', '2020-08-11 06:26:01'),
('87de9a9caa165c77964794c94c3be51681704640be2f27fe1c91a5424f5765f5af70430d7d53fcf6', 1, 2, NULL, '[]', 0, '2019-08-11 00:41:04', '2019-08-11 00:41:04', '2020-08-11 06:26:04'),
('6b97b77602bd36f9f6d8b14c070bcf80512a024075dc784dd47d073b610e7becb0d9df297e029907', 1, 2, NULL, '[]', 0, '2019-08-11 00:41:45', '2019-08-11 00:41:45', '2020-08-11 06:26:45'),
('2bc27d6966e0403413cbb2d5cd09036a36a6335e6d515d1d09f0b93c31e25295e4f2dbe09b3aaf4e', 1, 2, NULL, '[]', 0, '2019-08-11 00:41:58', '2019-08-11 00:41:58', '2020-08-11 06:26:58'),
('10440c98ef3f4001fa2d497abaf8168328188f2d84b1ffe2cc6b59d7bf8405e984b3e3f33ed931e3', 1, 2, NULL, '[]', 0, '2019-08-11 00:42:03', '2019-08-11 00:42:03', '2020-08-11 06:27:03'),
('90b8cc8758b4a00c5c913cc52b2671391455128b78c1d2113b26a0c1d159ec8e44adc7b1b653ee48', 1, 2, NULL, '[]', 0, '2019-08-11 00:45:33', '2019-08-11 00:45:33', '2020-08-11 06:30:33'),
('c9556896bfeb7e2365a66c2f7beab21eda224d6e7197d29021c13b4e045c041dd86c1c86313a5453', 1, 2, NULL, '[]', 0, '2019-08-11 00:47:20', '2019-08-11 00:47:20', '2020-08-11 06:32:20'),
('ec84a0c60be0177ca992a79b3d236a102e7240d951d6fc413e61c1679c9c761f39ac4d8026b2ae76', 27, 2, NULL, '[]', 1, '2019-08-11 00:50:41', '2019-08-11 00:50:41', '2020-08-11 06:35:41'),
('eb2c88147e6bf5e3fc174b62eb11b3b5a93f8cb3e8edf4db9a81f2590bc848fccecb61dae46c986a', 27, 2, NULL, '[]', 0, '2019-08-11 00:51:24', '2019-08-11 00:51:24', '2020-08-11 06:36:24'),
('5c3089dcf96fd2320437a143d9847a03138fd6d5fb77266f961dd4646585c483a1a235d2f077f377', 27, 2, NULL, '[]', 1, '2019-08-11 00:52:05', '2019-08-11 00:52:05', '2020-08-11 06:37:05'),
('aaf167bacbede90e1c249e391497b4561f17d4485bb1fefa421cf16c35b37fcac8404879564022c5', 27, 2, NULL, '[]', 0, '2019-08-11 00:52:27', '2019-08-11 00:52:27', '2020-08-11 06:37:27'),
('ccb9e3e14aa1371a29128d70598c9a4f185452e44585eda8170863b7355245ff9f5a934e2446dc3f', 27, 2, NULL, '[]', 0, '2019-08-11 01:01:54', '2019-08-11 01:01:54', '2020-08-11 06:46:54'),
('962f42bfa7c39cd70a401d27eb6c992de7b8d8169994803156cee2c84ec4c1429114cbab70630a12', 27, 2, NULL, '[]', 0, '2019-08-11 01:12:11', '2019-08-11 01:12:11', '2020-08-11 06:57:11'),
('9f5a2d944f05cdc4acbfaac712f118193c8a101312e89d37b2449f344a36cb198a7cad832e289867', 27, 2, NULL, '[]', 0, '2019-08-11 01:12:36', '2019-08-11 01:12:36', '2020-08-11 06:57:36'),
('5ad2a25e8d12e893ac71cd76c74ed9a8c6d4bb38dd933b61a7017ba0d19edcd931c801051d2d8973', 27, 2, NULL, '[]', 0, '2019-08-11 01:12:39', '2019-08-11 01:12:39', '2020-08-11 06:57:39'),
('441a9930d232fe5a47d720b5fa96bda2c21c9df845c3c078ecdc579264930972198fcd1867445580', 27, 2, NULL, '[]', 0, '2019-08-11 01:12:56', '2019-08-11 01:12:56', '2020-08-11 06:57:56'),
('0f113b92294250bb38ddff87d6f7b14f725db23e798d538abdae7a7d2d0e860853c2f3167a63927f', 27, 2, NULL, '[]', 0, '2019-08-11 01:13:34', '2019-08-11 01:13:34', '2020-08-11 06:58:34'),
('4056eb2f5037da56d2b3bcac907570cf95a641bb0390fdc9e2085e61c488f82fbea20e9b4c4b82b0', 27, 2, NULL, '[]', 0, '2019-08-11 01:13:37', '2019-08-11 01:13:37', '2020-08-11 06:58:37'),
('647a56e8f5dc59c97792e087b8311a9e8140b55fe0b2832e541cac8a18dd9e3b0681bfb5d2988918', 27, 2, NULL, '[]', 0, '2019-08-11 04:27:42', '2019-08-11 04:27:42', '2020-08-11 10:12:42'),
('f429d24fad0ddadaef19ba0fc955053ad1155cf9eb3b800787cee5e06a3761fc4751cbef24116658', 27, 2, NULL, '[]', 0, '2019-08-11 04:28:02', '2019-08-11 04:28:02', '2020-08-11 10:13:02'),
('570c030480decd711df53d896c077219f90a82379c59aece3b9f66dc68593cc301e1f304f9ca9a9e', 27, 2, NULL, '[]', 0, '2019-08-11 04:28:52', '2019-08-11 04:28:52', '2020-08-11 10:13:52'),
('5d3cf02ca49c3c61a24f5cff3e54e94c8f444fc59ea20ea095f9177668761d2fe0ea92f8939ae5fe', 27, 2, NULL, '[]', 0, '2019-08-11 04:29:04', '2019-08-11 04:29:04', '2020-08-11 10:14:04'),
('ae8d13da74b2bb5fa6c0d8d298ad493d965a543ad1d5d6129eb1d98fc7e42623d286c84745d352f5', 27, 2, NULL, '[]', 0, '2019-08-11 04:29:30', '2019-08-11 04:29:30', '2020-08-11 10:14:30'),
('60a78f69300717df0be3ac0aa8539d7aec07fa2310ef830ccc39eb4ef3f050f5aefacc5f3c64a5fe', 27, 2, NULL, '[]', 0, '2019-08-11 04:30:57', '2019-08-11 04:30:57', '2020-08-11 10:15:57'),
('3cf6e84befa0b05ec632f0112f45b730f203273fddabc44943b9e0cac90379ad1d118984dc5fc34b', 27, 2, NULL, '[]', 0, '2019-08-11 04:31:02', '2019-08-11 04:31:02', '2020-08-11 10:16:02'),
('9a0acb880eb810b410b9c7cfccca47da3724e1ca677d7c3ddcd04834b2981750ef3d007b45f49aac', 27, 2, NULL, '[]', 0, '2019-08-11 05:45:35', '2019-08-11 05:45:35', '2020-08-11 11:30:35'),
('2de5b9607d0629b66824f9a04fc3cfc41f6eabaf2369dab4ff870871fd0bda6cbb2ee05284e65129', 27, 2, NULL, '[]', 0, '2019-08-11 05:46:04', '2019-08-11 05:46:04', '2020-08-11 11:31:04'),
('643fec056ce4acffd6f632c2e0a6a3711ddced053a5ab180f2d3172b6e04b9db0421b76cf7019ebc', 40, 2, NULL, '[]', 0, '2019-08-16 04:20:05', '2019-08-16 04:20:05', '2020-08-16 10:05:05'),
('f5d9ace480357b0020926a9e7fd92e5bd6d1265e8d5d2fdc29adb613e510c38237d48d8fa95ce386', 40, 2, NULL, '[]', 0, '2019-08-16 04:40:54', '2019-08-16 04:40:54', '2020-08-16 10:25:54'),
('eeb57904c2688ee6304c0ae47f608bd7d024bf56f7d742a042cb36d5614d2cc7f7b8c9571e0dea01', 40, 2, NULL, '[]', 0, '2019-08-16 05:06:04', '2019-08-16 05:06:04', '2020-08-16 10:51:04'),
('f26ed1ee7bf4282e799e0cb2b3f468482179c765e9985553cf7923b4696c87e024f7b12b367a0282', 40, 2, NULL, '[]', 0, '2019-08-16 05:13:50', '2019-08-16 05:13:50', '2020-08-16 10:58:50'),
('f1ee21aa0d5209060022321b4e3ae64188fcc409d298c8c3165edd058044cb9f91164432ff20625e', 40, 2, NULL, '[]', 0, '2019-08-16 05:14:10', '2019-08-16 05:14:10', '2020-08-16 10:59:10'),
('fc390ab3f8fb4656a63777e6643d56286ad651c1a8b732b6e41a4f1dda26ee28c95642b2526d763f', 40, 2, NULL, '[]', 0, '2019-08-16 05:14:20', '2019-08-16 05:14:20', '2020-08-16 10:59:20'),
('4a7304d1d3e96a0b714450f79865ebcbfc78e76b2acd422ad83ea22b209314023a7e40d170825a91', 46, 2, NULL, '[]', 0, '2019-08-20 13:17:55', '2019-08-20 13:17:55', '2020-08-20 08:17:55'),
('da3d5ab61d3bed31a177c1d15a366a20dcf0423d3ae94c9b516f37f8398fc0557b2bccc14c40d416', 47, 2, NULL, '[]', 0, '2019-08-20 13:18:19', '2019-08-20 13:18:19', '2020-08-20 08:18:19'),
('1847dfddef527411bf89b733df096bfe58f71faa81955a11bcf1fadfd53023b5823282f0a1d3efac', 51, 2, NULL, '[]', 0, '2019-08-21 05:05:03', '2019-08-21 05:05:03', '2020-08-21 10:50:03'),
('8e762b42aa1ee3e72a98d30b2124cf8d137dfa7cd84e2d01db9e5b64d01ef89df828039c10a29cb7', 52, 2, NULL, '[]', 0, '2019-08-21 05:07:38', '2019-08-21 05:07:38', '2020-08-21 10:52:38'),
('b4344ca74427076893c3ca3e6d2d3a7801655a1e8de247bddf273c578d707bba04b70e1c3358549f', 53, 2, NULL, '[]', 1, '2019-08-21 05:35:05', '2019-08-21 05:35:05', '2020-08-21 11:20:05'),
('fdeb85bab31251d97215c8d21ed99dc00cb387d09b16f4e92096fb56a29d154cb73159ddfe8ee0a7', 53, 2, NULL, '[]', 0, '2019-08-21 05:36:36', '2019-08-21 05:36:36', '2020-08-21 11:21:36'),
('1a9a1beaf1f131f97bcd5891f17d5d9576dfd285804aa865005349957799a9dd40ff29ce589ad586', 54, 2, NULL, '[]', 0, '2019-08-22 23:51:11', '2019-08-22 23:51:11', '2020-08-23 05:36:11'),
('9710570ff3435c02208b6c1d2769a1a3ea54930105ab8c304697a7c51b86ae82ba54ac05653d4497', 54, 2, NULL, '[]', 0, '2019-08-22 23:52:31', '2019-08-22 23:52:31', '2020-08-23 05:37:31'),
('f6cca48f5ab4b4cc8c4f952e863a521bda524d4c9f0c40453de0d0a026bedf2a8082750b06ce4e13', 52, 2, NULL, '[]', 0, '2019-08-22 23:52:58', '2019-08-22 23:52:58', '2020-08-23 05:37:58'),
('0036799f0c171f22b29e842074a52723cdf5fabaddbdbcabb3d419b73bb1ec5b9a1f250f696ab58c', 52, 2, NULL, '[]', 0, '2019-08-22 23:53:55', '2019-08-22 23:53:55', '2020-08-23 05:38:55'),
('89f3db7ec98322c10ff010c4bf3a7864e8a9a1790f29fc1dfffb3f185f3357824ca0d4e676f27314', 55, 2, NULL, '[]', 0, '2019-08-22 23:54:13', '2019-08-22 23:54:13', '2020-08-23 05:39:13'),
('82e15aa23f8e518691dfe318801b3b27b5de38ac88a7d2d276f0426b1b9c297c6194a357b1ce4432', 55, 2, NULL, '[]', 0, '2019-08-22 23:54:39', '2019-08-22 23:54:39', '2020-08-23 05:39:39'),
('d910fd10f991d3a3e98d28f3576c7cc9f2aa7f4834af53b22c5e52157441d6e458c1d9cfcb901730', 55, 2, NULL, '[]', 0, '2019-08-22 23:56:25', '2019-08-22 23:56:25', '2020-08-23 05:41:25'),
('a5e339f5e41baf1f0b4f82686bfe003cc94cb25c49f45c5c6fd40c1b8af3c72da931dd9c5fe744a6', 52, 2, NULL, '[]', 0, '2019-08-22 23:56:38', '2019-08-22 23:56:38', '2020-08-23 05:41:38'),
('a3ae471ba4c900847fdf8da7decefaaf67f31a21d60721d0797a3447c1f20ffd409c3bcf83a383e2', 56, 2, NULL, '[]', 0, '2019-08-23 03:16:10', '2019-08-23 03:16:10', '2020-08-23 09:01:10'),
('980d2382fb3b5bbcf1d6459ec2c965e2f75c9266a2e914c50c32db89b1656ae050dcbce2c2092c83', 56, 2, NULL, '[]', 0, '2019-08-23 03:17:00', '2019-08-23 03:17:00', '2020-08-23 09:02:00'),
('3ff3199381f817edf75e5bb185eee3214e85ebf5b3afd1d06ca8aed5c8fd2dca6c531f490bf0c6ef', 56, 2, NULL, '[]', 0, '2019-08-23 03:17:05', '2019-08-23 03:17:05', '2020-08-23 09:02:05'),
('41e59c898f77ba5e4c35b3180897deff20381e4ac00d70dcb52c990cb7593b1ed051f3ac5836218a', 56, 2, NULL, '[]', 0, '2019-08-23 03:17:10', '2019-08-23 03:17:10', '2020-08-23 09:02:10'),
('3cf1794fa75957585419e683b0ec2a12b30702fa28e8855a13f582f12cd847bbcd34452c59966c44', 57, 2, NULL, '[]', 0, '2019-08-25 22:54:17', '2019-08-25 22:54:17', '2020-08-26 04:39:17'),
('5d8e6f657b6777684b9c385653aa7bcaeed02b6f2331e8945b00d95c00416806b3241a1844d8b9b2', 57, 2, NULL, '[]', 0, '2019-08-25 22:55:34', '2019-08-25 22:55:34', '2020-08-26 04:40:34'),
('44a1b717218893fb475a019efa2aa404079cb3ec3eb4c0e8ac6c6209ae838e2165d487ff9350e827', 57, 2, NULL, '[]', 0, '2019-08-25 22:55:41', '2019-08-25 22:55:41', '2020-08-26 04:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'GO-RINSE Personal Access Client', 'Gpk9tpsj5Lnb48xHEDdF2rbHF165V5uFvB5damsY', 'http://localhost', 1, 0, 0, '2019-08-08 23:34:28', '2019-08-08 23:34:28'),
(2, NULL, 'GO-RINSE Password Grant Client', 'wNfEEpIS6VUHVZKVhgUWGNjcNTUvkd5gGtsnCgnb', 'http://localhost', 0, 1, 0, '2019-08-08 23:34:28', '2019-08-08 23:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-08-08 23:34:28', '2019-08-08 23:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('d3c08fbeb446ccda69ee8b12253401846f7dd344832a4583dbff6bf24b9c393c65a09e70fb1e6ebc', '2a47dfe1decbdf1fcce0b84b9b06fc7957fd7a1ed843afe420096e74e139675a5cc257f2c5479f1b', 0, '2020-08-09 05:20:12'),
('765272f38e2780a3e60b69d84e69b8fe40d9dbb7fe44a7b968abb638554e8e0396147cd1876cdd4b', '2dca451968bfbf5f7a0dd132770ead51d38ca0370b0cf3ecb5d8edc94d7c4b62a43603a27b6a23d1', 0, '2020-08-11 05:08:42'),
('c1c3ff902337c90f1706ee5c24bc3fa0c5bda20d61aacb97b8036237a0fb2b5f4d0a9c497089ecfa', 'd2518b6b234620f3ef301178c319cb474b843e02ec5a6171de4d0767b053ca52f3387de21e28bb1c', 0, '2020-08-11 05:08:54'),
('1ad7db640d7cf1099ffe55ea3623d0d74006330f5093537c70f731b4a976aee3b0d83b631a13e2ec', '16a18f12a38d89e4aa3a32a5f82f6f85764997152650c54d5fe441ae9fb182715fa552ed1fb4677c', 0, '2020-08-11 05:13:57'),
('bb0d186ac84a633fae31d73b22dbbc42e87ea2905e9058b927a866bb768d53e81787e4e92aa5da95', '72519685dd7f43b557d845c71dedf6f94b73ff5062a0ef96ed7fce79c4b11b055ddcceca2a8e57e8', 0, '2020-08-11 05:15:11'),
('e20658323c780527925508a607ad5f86439a192ea463d1466ecec47c0568e2035a41f9689a55169d', 'b45c07a972c5be1ff9963c0a2be63835436fee03c83ffdc22c022cc1459b9c8dd77511f720604ce1', 1, '2020-08-11 05:31:18'),
('13e2fffb432697efe24946496c48361d46209154fcc8df961339dc078fa7d8dddf870099a53e35e5', '0433e00aef99e0573c67bb5428e64bd7fb3e38a40d3c6f74cf97828aa662b4a2b72d165bf8f74241', 0, '2020-08-11 05:33:05'),
('6c9330ad849efc588c488c8ac15a38d9cf1317f1690056b19542583747d2541b691e1a5848d9470a', '97573228fa31ecefad67beb10c2d29b16f915650aec83b5ea7d2b2197f634f4ab98511a02e54caa8', 0, '2020-08-11 06:17:23'),
('c2d909d2b69096e694086b69f3578de38b5b3ea6a99b1d5301d90a5c83442c9d212bd0ffc32565a8', '500695ec82265025dd96a720ac7f91a0a0c3d84cfdec0b0ea4daaaea27e45cb06f800a037b13a1c6', 0, '2020-08-11 06:18:02'),
('d5b520a928eb65080ceea0be78ffd7ecd6b79c2379671c164a4c0bedde091b54b7ef33e26ec8320a', 'b2cbdce08cd09cbe2bb431235c6efbd0e91257c52674907aae5e8d45fa4aaaa5c998a6811e36878a', 0, '2020-08-11 06:18:07'),
('1e8bc57cd971f0a8f31b58926a0aed93fb88512b0e863a5736fb7f8cbc98ed9a6dd6a8fe68fcfa7f', '3e7b7057a623a24d2d21c4dc96244308320f5a6b819b915cf0c038fb3ec9ba062971bcde200c4cd3', 0, '2020-08-11 06:18:13'),
('d3f765022090f2103b2494e272e65678f2b02b29735f893f4f6b34f879ae0f0a60b840308d46b0d7', '36dc8b0e556b25c5d807db55c53a476ca36d81b1e6abca782fe4a3a055d2c55580dba3a80759fda2', 0, '2020-08-11 06:19:34'),
('76de7b75b06feb520debd272688d674dffcb7c95453f5e0c76f2c9e0f2e423d9b28051f0c4e56d94', '10dbc477c39d0d5247c15b9c7eea49426e98d606e75d8d6e68b589b1fe52ff36351c1e5ced48d210', 0, '2020-08-11 06:19:54'),
('975ea38187b3fd4a6a4df9da17a2d3e078db8dd59a1efe8dd20c9f59ba6e5b3dbc12cb1e17ed5731', 'cac9f19e705d069a4a2da1bece168b1f5f2b7c64bdaa479600be28ea768e0f1ccc2b475fa0867097', 0, '2020-08-11 06:19:57'),
('4a9755ac8887e8b3050fe3426cc8c468002ca3859ded1fa69930f7dd6118c6aa85fc49d9850f3ca9', 'c1e639b5048e968225377942a845710309a749d8aa0ce1c970a376a4b608bb38aa43398fdf70e901', 0, '2020-08-11 06:20:01'),
('4dea0e6455e36392d5e6b74d99d2b6dd203f2e17b608372a22f1b8b66b9e820b24ba2747200f35a6', '23cf2fff2920885d0027db6a5bc08616c9edb5714631b0058c5fb747d59e5c3452d1a3bc1ee38f00', 0, '2020-08-11 06:20:05'),
('b547dc70f1162cc2fdcb599d177c33364270d7aad2fa99f812b8b62512f4c5b5da8b95401c564867', '1549a8aac1840b94582b6dbf522dbe67e18a74f5fecbf391d7462a098a4aac902b55750d24a77663', 0, '2020-08-11 06:20:42'),
('232eae584e73b88071965eb7be4dc129d3c14ec4e43bdcc3da354516cdedafe39b54ee758c67b3c6', 'c57739d1b1ff9e54b3c58e6196d4fe7c8cc66a05b8ad63f5ace760ce926fd291becf3b8b1b0b88d3', 0, '2020-08-11 06:23:12'),
('f7853818e550f9a676a50957eff3ddcb0e2570fd05f278cdd52a2e19e3680344f34e5b6a6a0939a1', '4775621bc061119fe303d0f8c077f5e9a8e4d394d865fc69d14c75166f1f6b29714b119eb921530d', 0, '2020-08-11 06:23:15'),
('487591d3389149367d7fb1e38ca6d40960a0f40e9901ec0208adec94beb351888dde467fb34b9d3d', '01e9ef310ed97b0d9814de10ffe563db85b19785c9d1d36a3ab27584962b0e19a07dd980d997fba0', 0, '2020-08-11 06:23:27'),
('e3914071242fab88baa705fa1cb3af9569f62b4e19219ec74396a640a5fcc8a322d689c9ead4191c', '9829ea70dcea97f514b65639fdc5f2c653812a43a2988979cea86ef2cb12c51abec7bce88210ddf2', 0, '2020-08-11 06:24:56'),
('b71a9e6127af01eb73adc18f4f1d54dbe2d93d7ba84c254dc9a7578898cb48a790ba43bed27caa22', '483ff6371dbf4ffec7daf00f5625612d2bc406b874865b73545f8c0950efc79af25c995a2ff307b7', 0, '2020-08-11 06:25:28'),
('46a3e48655c43108b118e1d40348d60ff075c5240afed6af54dff059c2778bbb987e7a5270747563', '3094f0a1d97a9677f0ebb1b9f56585b3cbe7a68b1d958a5529a15fbb8a534d74dd3e72ccbdee81c1', 0, '2020-08-11 06:25:37'),
('195d9ecb1a653bb9c2826f538779fd898f7cf986cfe951966f34e426c85ba0efe83a9becff203bc6', '837d067f5687640315595b5d6d62292dd7bb81db28e9561f27b3030485765ebc40a01c1a607a4ad7', 0, '2020-08-11 06:25:53'),
('8e1a3420892d2000ae00b99099c73e941aa51d5fe251a75e12b653d040d81f1b5bf235e6594b0b5d', 'c0b2dd55751936dd8ad944de2cda469a6df7e558abf6fe86392a20c53c88dd8d7b475bdf5718c75e', 0, '2020-08-11 06:26:01'),
('ddf13547bf8d2bff170b22c0447c3a76a54b83f891a48c5f1b582b2f92a382e02e9948573c9f4613', '87de9a9caa165c77964794c94c3be51681704640be2f27fe1c91a5424f5765f5af70430d7d53fcf6', 0, '2020-08-11 06:26:04'),
('522daf04efb860ff8e1486f254c20addd9e70b3bbcbd62687261cd9627de6304c2ffff54d76deefa', '6b97b77602bd36f9f6d8b14c070bcf80512a024075dc784dd47d073b610e7becb0d9df297e029907', 0, '2020-08-11 06:26:45'),
('8646a39326f5f075c94c6c47b38235254c16f2d800fd4464696be3bdf99e71eb426888880a03002d', '2bc27d6966e0403413cbb2d5cd09036a36a6335e6d515d1d09f0b93c31e25295e4f2dbe09b3aaf4e', 0, '2020-08-11 06:26:58'),
('d5bcdddca57cbda55e48542c541f059e70413e0b73bd78c8bf927417c84f4e0c9b45e543ab05e807', '10440c98ef3f4001fa2d497abaf8168328188f2d84b1ffe2cc6b59d7bf8405e984b3e3f33ed931e3', 0, '2020-08-11 06:27:03'),
('d288d81ad019d73e414250d5cc19f2fe5fb233d7513c8841465926eb039fcc0bb1c384be2a4c2bf9', '90b8cc8758b4a00c5c913cc52b2671391455128b78c1d2113b26a0c1d159ec8e44adc7b1b653ee48', 0, '2020-08-11 06:30:33'),
('3fdceb63e64158dbb37b78093016872b7850808e3ee27e960494e5e10d68ad2aad91aecc742a5f4e', 'c9556896bfeb7e2365a66c2f7beab21eda224d6e7197d29021c13b4e045c041dd86c1c86313a5453', 0, '2020-08-11 06:32:20'),
('103e09f7fadbce5321bbd888944c7e5192ec8c9de7e6cb0b4e152e1963fae0102f8ed14e280911ad', 'ec84a0c60be0177ca992a79b3d236a102e7240d951d6fc413e61c1679c9c761f39ac4d8026b2ae76', 1, '2020-08-11 06:35:41'),
('d56ca67cf7873fdef676dbb8b42d3e1899e6abc010321de0f0647ddbdc469fc777bad10c73dd2a51', 'eb2c88147e6bf5e3fc174b62eb11b3b5a93f8cb3e8edf4db9a81f2590bc848fccecb61dae46c986a', 0, '2020-08-11 06:36:24'),
('3b2f1c188abb2bbc393f7bdef630c3dea4684e5f5b2677afccfca2e2ce2eeb6aeaa36403accaf143', '5c3089dcf96fd2320437a143d9847a03138fd6d5fb77266f961dd4646585c483a1a235d2f077f377', 1, '2020-08-11 06:37:05'),
('96ac32c7b9710dd3c6e041bfa4e8f0a294814555b8a1a4efdfb102ab58af2907e6db08102ddd5968', 'aaf167bacbede90e1c249e391497b4561f17d4485bb1fefa421cf16c35b37fcac8404879564022c5', 0, '2020-08-11 06:37:27'),
('dae5ffa86df9c984da356c7271382b8b81187eb591b33ba2adc1444c3b8857b772cca7db5506f191', 'ccb9e3e14aa1371a29128d70598c9a4f185452e44585eda8170863b7355245ff9f5a934e2446dc3f', 0, '2020-08-11 06:46:54'),
('2243b53194d0f33a0dedce5c2101a111b8fd78fa4318e37f7f93b10728e30b08cbbdacc1f6e8d392', '962f42bfa7c39cd70a401d27eb6c992de7b8d8169994803156cee2c84ec4c1429114cbab70630a12', 0, '2020-08-11 06:57:11'),
('19179042b693c13700e7787417cff3fb8d081ce012460e0cc2bba501a345ec78ea95968a721fabec', '9f5a2d944f05cdc4acbfaac712f118193c8a101312e89d37b2449f344a36cb198a7cad832e289867', 0, '2020-08-11 06:57:36'),
('875de71d69c0900c0b78d1614cbe76de2f33f5a544f3aa929330365fd348bc96f57d56d15414f3b0', '5ad2a25e8d12e893ac71cd76c74ed9a8c6d4bb38dd933b61a7017ba0d19edcd931c801051d2d8973', 0, '2020-08-11 06:57:39'),
('22543f27653a90cec0af37f92e27e59cac0dc2437ca17e7e79350fc18549c6a32ab51381fcd3580f', '441a9930d232fe5a47d720b5fa96bda2c21c9df845c3c078ecdc579264930972198fcd1867445580', 0, '2020-08-11 06:57:56'),
('c586254fec62f6a3182cc4561613d9e7967803144c382d74ffebbe8600ebe31abd1e8adef0e23497', '0f113b92294250bb38ddff87d6f7b14f725db23e798d538abdae7a7d2d0e860853c2f3167a63927f', 0, '2020-08-11 06:58:34'),
('1739a7a08093e7aefaccc548b8e78b29819b17f23fea9692dd99d15e3f3658eee64c692111f51786', '4056eb2f5037da56d2b3bcac907570cf95a641bb0390fdc9e2085e61c488f82fbea20e9b4c4b82b0', 0, '2020-08-11 06:58:37'),
('48b5bbac9e595e6249bf69e87adbc0617a09056477dd7f4c87ff0f96122b3aafaf042ae93f2bb35b', '647a56e8f5dc59c97792e087b8311a9e8140b55fe0b2832e541cac8a18dd9e3b0681bfb5d2988918', 0, '2020-08-11 10:12:42'),
('6a72e7b96fb43f634cfffa7dd44a9cdcaa372a1e8ecb47ca9fc502dc0f6dd4d7953523bbbbe8f04b', 'f429d24fad0ddadaef19ba0fc955053ad1155cf9eb3b800787cee5e06a3761fc4751cbef24116658', 0, '2020-08-11 10:13:02'),
('1396b48efb171f82cf9bcda64f1826ca9b5448d3857dfab77c5a4d736eede14cb4c9d07e7fe6a44e', '570c030480decd711df53d896c077219f90a82379c59aece3b9f66dc68593cc301e1f304f9ca9a9e', 0, '2020-08-11 10:13:52'),
('d418e1a0bb9480265ca7a4f78f70f78da5775443cfa6f093c6a59628c4c8a055bfd8694d73620e3a', '5d3cf02ca49c3c61a24f5cff3e54e94c8f444fc59ea20ea095f9177668761d2fe0ea92f8939ae5fe', 0, '2020-08-11 10:14:04'),
('e28e5b0b8900cf3f7796d0842648c7f3e640c804745ee246f927a4861f75cf06a55be0c7cd0446f4', 'ae8d13da74b2bb5fa6c0d8d298ad493d965a543ad1d5d6129eb1d98fc7e42623d286c84745d352f5', 0, '2020-08-11 10:14:30'),
('2233a49b6ae33f24923368132a6527d918625fa7ff7052613d749f8b3856ee6cbf901b0fdacd9c3a', '60a78f69300717df0be3ac0aa8539d7aec07fa2310ef830ccc39eb4ef3f050f5aefacc5f3c64a5fe', 0, '2020-08-11 10:15:57'),
('79a749998f6f196fd47de016b7ef7122b7a86ad700dfb6a38877df31f1cf825b9d4804dcb56b4aaa', '3cf6e84befa0b05ec632f0112f45b730f203273fddabc44943b9e0cac90379ad1d118984dc5fc34b', 0, '2020-08-11 10:16:02'),
('c3978eb85782fb3886b639513cf36245aa9ebf2c09be03547ada3d933911453165d4dd33c36f7176', '9a0acb880eb810b410b9c7cfccca47da3724e1ca677d7c3ddcd04834b2981750ef3d007b45f49aac', 0, '2020-08-11 11:30:35'),
('94c865b713b56d4a6febb58949ddb2c0e8378a084d05a17339b65112861855167dc9a04f0cef2f9a', '2de5b9607d0629b66824f9a04fc3cfc41f6eabaf2369dab4ff870871fd0bda6cbb2ee05284e65129', 0, '2020-08-11 11:31:04'),
('45273daf4566bca95c67eded0b5e177f2b7b46a7ecfff013da4ebcb9e7c36382cc32e3f987eabe06', '643fec056ce4acffd6f632c2e0a6a3711ddced053a5ab180f2d3172b6e04b9db0421b76cf7019ebc', 0, '2020-08-16 10:05:05'),
('d583084bb7a7a4069a5ea7d8408d83bbe6e2e8f378874a4f04c920b65159dae036c8b769aabccc2e', 'f5d9ace480357b0020926a9e7fd92e5bd6d1265e8d5d2fdc29adb613e510c38237d48d8fa95ce386', 0, '2020-08-16 10:25:54'),
('d4979788866f22f3cbbad68d1aea4a7bb8586ba591fd192d3fd87f4029e1158bbd046ac45753edd1', 'eeb57904c2688ee6304c0ae47f608bd7d024bf56f7d742a042cb36d5614d2cc7f7b8c9571e0dea01', 0, '2020-08-16 10:51:04'),
('9d03abc7b2ee3f75aed3b402c4b47ee4efd5a9989318c881f515d36022b021b9f446efb2e71c68e5', 'f26ed1ee7bf4282e799e0cb2b3f468482179c765e9985553cf7923b4696c87e024f7b12b367a0282', 0, '2020-08-16 10:58:50'),
('1aed387ecbbf7b6d89913e739aa33a3e8862d11271164795442701161329a2c10f6d156a7be63934', 'f1ee21aa0d5209060022321b4e3ae64188fcc409d298c8c3165edd058044cb9f91164432ff20625e', 0, '2020-08-16 10:59:10'),
('f25697146700fa93a34223093f740f67e02c717ba87e28ad5a5438618f82c0bb7bf16baaa11ad360', 'fc390ab3f8fb4656a63777e6643d56286ad651c1a8b732b6e41a4f1dda26ee28c95642b2526d763f', 0, '2020-08-16 10:59:20'),
('54dd4149aadbc2175eec4e3b86e7160bdbc4b92741503ecbadf46b629b2026fc6a47b945ebb9c199', '4a7304d1d3e96a0b714450f79865ebcbfc78e76b2acd422ad83ea22b209314023a7e40d170825a91', 0, '2020-08-20 08:17:55'),
('ac566636496587178472b96cc50ac5a487ab3c7b9f24ff783ecb83ffb79edbfd91c3939a41121655', 'da3d5ab61d3bed31a177c1d15a366a20dcf0423d3ae94c9b516f37f8398fc0557b2bccc14c40d416', 0, '2020-08-20 08:18:19'),
('46140586be3697158cb2565f0311f0efd69ae48c0af476b1300517119230bd37fe09de9198bb2952', '1847dfddef527411bf89b733df096bfe58f71faa81955a11bcf1fadfd53023b5823282f0a1d3efac', 0, '2020-08-21 10:50:03'),
('1b73a95bd67d6b02d5388db7493cb3326e4f27b7569fd7f32dafee6e86b8fe4b2e5201830c002693', '8e762b42aa1ee3e72a98d30b2124cf8d137dfa7cd84e2d01db9e5b64d01ef89df828039c10a29cb7', 0, '2020-08-21 10:52:38'),
('0543faa5ef376fd84be47cbb46e06f607d0b770d34d4b80b20aede762f219dd7dd3bcf3158953ee3', 'b4344ca74427076893c3ca3e6d2d3a7801655a1e8de247bddf273c578d707bba04b70e1c3358549f', 1, '2020-08-21 11:20:05'),
('92eb8dbf027fa14c041f6bc76b079f5190a3423ce699b8d67fb026c2e6ea88ec999886afe2aed289', 'fdeb85bab31251d97215c8d21ed99dc00cb387d09b16f4e92096fb56a29d154cb73159ddfe8ee0a7', 0, '2020-08-21 11:21:36'),
('8f6576e9cfbbb096f487bc74dab2bccf26cb06490ec4102735982437685da9a5a227d858d28060e7', '1a9a1beaf1f131f97bcd5891f17d5d9576dfd285804aa865005349957799a9dd40ff29ce589ad586', 0, '2020-08-23 05:36:11'),
('f05ff640329587aafa19f4206c7182cb69aa0e8bfa28b3915df45c14cdddacf65f93a67d22fea30a', '9710570ff3435c02208b6c1d2769a1a3ea54930105ab8c304697a7c51b86ae82ba54ac05653d4497', 0, '2020-08-23 05:37:31'),
('5e9fcf06a9abb1c2af58ae733932aacdb39edf8dab2239f17524bd53d2267bf0539d42f35cf1889c', 'f6cca48f5ab4b4cc8c4f952e863a521bda524d4c9f0c40453de0d0a026bedf2a8082750b06ce4e13', 0, '2020-08-23 05:37:58'),
('1033f70fa43db9df540471c55f56f4a93217e13f6502549c1405ab86c2a16d85f59c08e7768c626a', '0036799f0c171f22b29e842074a52723cdf5fabaddbdbcabb3d419b73bb1ec5b9a1f250f696ab58c', 0, '2020-08-23 05:38:55'),
('d03474aa7bb5712bd3d73b52a7c0e41f956cab85fbd7519c33950bb081dcee99306f9bdc6649fe5d', '89f3db7ec98322c10ff010c4bf3a7864e8a9a1790f29fc1dfffb3f185f3357824ca0d4e676f27314', 0, '2020-08-23 05:39:13'),
('f4da95cf368520df409c4290cb7df82cf73f3101e76d9f6c7c4a670165f859ec40bf8df1950e8ade', '82e15aa23f8e518691dfe318801b3b27b5de38ac88a7d2d276f0426b1b9c297c6194a357b1ce4432', 0, '2020-08-23 05:39:39'),
('e770a8f8da4e9737b96353c7b73723d0732593aaa8c675bdf9003f4951312e5f601b56c69e342a33', 'd910fd10f991d3a3e98d28f3576c7cc9f2aa7f4834af53b22c5e52157441d6e458c1d9cfcb901730', 0, '2020-08-23 05:41:25'),
('7345135aedec2150946e13ef691df187b692bcbcb87724f88283e35fe8b5b879533ce964dd9b4e51', 'a5e339f5e41baf1f0b4f82686bfe003cc94cb25c49f45c5c6fd40c1b8af3c72da931dd9c5fe744a6', 0, '2020-08-23 05:41:38'),
('9d97046798d3c41a87afe47339a9dc9b1b2c450a720c479157df693788f9b57d04fd091a8ffbd0f4', 'a3ae471ba4c900847fdf8da7decefaaf67f31a21d60721d0797a3447c1f20ffd409c3bcf83a383e2', 0, '2020-08-23 09:01:11'),
('1eea3075e3f24d23027d6b85b25b3b1fde59a50cd319d67987525d2c632f7230fc900e683b5dc477', '980d2382fb3b5bbcf1d6459ec2c965e2f75c9266a2e914c50c32db89b1656ae050dcbce2c2092c83', 0, '2020-08-23 09:02:00'),
('0c8d11d856d0a1037b19ec45ea9889470d66b8a310a780d0f79852c270f697c3e93d446f582133e5', '3ff3199381f817edf75e5bb185eee3214e85ebf5b3afd1d06ca8aed5c8fd2dca6c531f490bf0c6ef', 0, '2020-08-23 09:02:05'),
('ca1771c0ec7439acb3f2e9a58cf0c603b632b8b14e7b3790123882e4bd32071f7e8007247ead5ca4', '41e59c898f77ba5e4c35b3180897deff20381e4ac00d70dcb52c990cb7593b1ed051f3ac5836218a', 0, '2020-08-23 09:02:10'),
('690b4c608ae41ab090317f9ee1a3c6d7305f2d28a4c74abf0eea18709ceff57aa737c6eb5c7332bb', '3cf1794fa75957585419e683b0ec2a12b30702fa28e8855a13f582f12cd847bbcd34452c59966c44', 0, '2020-08-26 04:39:17'),
('65cb2eb4af39d83474958449dfa1b17952f2860923b23d2c698ad7c6b6808d406f4b1f3bd43a4085', '5d8e6f657b6777684b9c385653aa7bcaeed02b6f2331e8945b00d95c00416806b3241a1844d8b9b2', 0, '2020-08-26 04:40:34'),
('f0f656432e5ae66227c908b8eb1a8aae1880b5c032207d126d25e000069130ad1d2f81010de4986e', '44a1b717218893fb475a019efa2aa404079cb3ec3eb4c0e8ac6c6209ae838e2165d487ff9350e827', 0, '2020-08-26 04:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

DROP TABLE IF EXISTS `offers`;
CREATE TABLE IF NOT EXISTS `offers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `name`, `image`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'In sint commodo tem', 'banner_offer_1.jpg', 'Dolore esse dolore a', 1, '2019-08-26 05:41:29', '2019-08-26 05:43:26', '2019-08-26 05:43:26'),
(2, 'Dolorem nostrud a na', 'banner_offer_2.jpg', 'Amet nisi labore do', 1, '2019-08-26 05:44:24', '2019-08-26 06:10:23', NULL),
(3, 'At reiciendis maiore', 'banner_offer_3.PNG', 'Suscipit excepteur u', 1, '2019-08-26 05:44:37', '2019-08-26 06:01:30', NULL),
(4, 'Quod aliquid blandit', 'banner_offer_4.PNG', 'Est dicta do molliti', 0, '2019-08-26 05:45:17', '2019-08-26 05:45:24', '2019-08-26 05:45:24'),
(5, 'Optio irure velit d', 'banner_offer_5.png', 'Quibusdam quos eu do', 1, '2019-08-26 06:13:04', '2019-08-26 06:13:04', NULL),
(6, 'U lala', 'banner_offer_6.PNG', 'fjalksdjflksajdf aslkdfjlksadf', 1, '2019-08-26 23:11:44', '2019-08-26 23:11:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `driver_id` int(10) UNSIGNED DEFAULT NULL,
  `type` smallint(6) NOT NULL COMMENT '1:Normal, 2:Urgent',
  `pick_location` smallint(6) NOT NULL COMMENT 'From User Address Table',
  `pick_date` date NOT NULL,
  `pick_timerange` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drop_location` smallint(6) NOT NULL COMMENT 'From User Address Table',
  `drop_date` date DEFAULT NULL,
  `drop_timerange` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT 'See Config',
  `VAT` int(11) DEFAULT NULL,
  `delivery_charge` int(11) DEFAULT NULL,
  `PFC` datetime DEFAULT NULL COMMENT 'Picked From Customer',
  `DAO` datetime DEFAULT NULL COMMENT 'Dropped At Office',
  `PFO` datetime DEFAULT NULL COMMENT 'Picked From Office',
  `DTC` datetime DEFAULT NULL COMMENT 'Delivered To Customer',
  `Payment_Time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `driver_id`, `type`, `pick_location`, `pick_date`, `pick_timerange`, `drop_location`, `drop_date`, `drop_timerange`, `payment`, `status`, `VAT`, `delivery_charge`, `PFC`, `DAO`, `PFO`, `DTC`, `Payment_Time`, `created_at`, `updated_at`) VALUES
(1, 27, 40, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 4, 5, 105, '2019-08-20 06:31:24', '2019-08-20 06:31:41', NULL, NULL, NULL, '2019-08-19 01:25:13', '2019-08-20 00:46:41'),
(2, 52, 51, 1, 20, '2019-12-12', '15:00-16:00', 20, NULL, NULL, 1, 0, 5, 105, NULL, NULL, NULL, NULL, NULL, '2019-08-21 05:11:14', '2019-08-23 02:24:50'),
(3, 52, 41, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:17:16', '2019-08-23 05:07:06'),
(4, 52, 50, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:22:59', '2019-08-22 03:10:09'),
(5, 52, 48, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:23:40', '2019-08-22 03:13:07'),
(6, 52, NULL, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:26:32', '2019-08-22 02:26:32'),
(7, 52, 48, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:27:00', '2019-08-22 03:04:10'),
(8, 52, NULL, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:27:22', '2019-08-22 02:27:22'),
(9, 52, NULL, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:53:08', '2019-08-22 02:53:08'),
(10, 52, NULL, 1, 2, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:53:43', '2019-08-22 02:53:43'),
(11, 52, NULL, 1, 20, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:57:36', '2019-08-22 02:57:36'),
(12, 52, NULL, 1, 20, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-22 02:58:01', '2019-08-22 02:58:01'),
(13, 52, NULL, 1, 20, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-23 04:47:43', '2019-08-23 04:47:43'),
(14, 52, NULL, 1, 20, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-23 04:48:09', '2019-08-23 04:48:09'),
(15, 52, NULL, 1, 20, '2019-12-12', '15:00-16:00', 1, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-23 04:49:11', '2019-08-23 04:49:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `service_charge` int(10) UNSIGNED NOT NULL,
  `item_charge` int(10) UNSIGNED NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `service_id`, `item_id`, `quantity`, `service_charge`, `item_charge`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 1, 5, 50, 100, 'Something in the way', '2019-08-23 02:24:46', '2019-08-23 02:24:46'),
(2, 2, 4, 2, 4, 50, 100, 'uu lalalal', '2019-08-23 02:24:46', '2019-08-23 02:24:46'),
(3, 2, 4, 1, 5, 50, 100, 'Something in the way', '2019-08-23 02:24:50', '2019-08-23 02:24:50'),
(4, 2, 4, 2, 4, 50, 100, 'uu lalalal', '2019-08-23 02:24:50', '2019-08-23 02:24:50');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_cards`
--

DROP TABLE IF EXISTS `payment_cards`;
CREATE TABLE IF NOT EXISTS `payment_cards` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` smallint(6) NOT NULL COMMENT '1: Visa, 2: Master Card',
  `card_no` int(10) UNSIGNED NOT NULL,
  `month_year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `csv` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_cards`
--

INSERT INTO `payment_cards` (`id`, `user_id`, `type`, `card_no`, `month_year`, `csv`, `created_at`, `updated_at`) VALUES
(2, 52, 1, 123, '12', 212, '2019-08-25 01:22:50', '2019-08-25 01:22:50'),
(3, 52, 1, 123, '12', 212, '2019-08-25 01:22:55', '2019-08-25 01:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'create-role', 'Create Role', 'Create New Role', '2019-08-08 23:32:26', '2019-08-08 23:32:26'),
(2, 'role-list', 'Display Role Listing', 'List All Roles', '2019-08-08 23:32:26', '2019-08-08 23:32:26');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 11),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'superAdmin', 'Super Admin', 'Super Admin Role', '2019-08-08 23:32:31', '2019-08-08 23:32:31'),
(2, 'admin', 'Admin', 'Admin Role', '2019-08-08 23:32:32', '2019-08-08 23:32:32'),
(3, 'customer', 'Customer', 'Customer Based Role', '2019-08-09 00:15:38', '2019-08-09 00:15:38'),
(11, 'driver', 'Driver', 'Driver Based Role', '2019-08-16 04:14:11', '2019-08-16 04:14:11');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 4),
(9, 3),
(10, 4),
(16, 3),
(27, 3),
(28, 3),
(29, 4),
(32, 4),
(34, 1),
(35, 4),
(36, 4),
(40, 11),
(41, 11),
(42, 3),
(46, 3),
(47, 3),
(48, 11),
(49, 11),
(50, 11),
(51, 11),
(52, 3),
(53, 3),
(54, 3),
(55, 3),
(56, 3),
(57, 11);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(10) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '0: Inactive, 1: Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `description`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Press Only', 50, NULL, 1, '2019-08-15 00:42:20', '2019-08-15 00:42:20'),
(4, 'Clean & Press', 50, NULL, 1, '2019-08-15 00:42:12', '2019-08-15 00:42:12'),
(5, 'Wash & Press', 50, NULL, 1, '2019-08-15 00:42:05', '2019-08-15 00:42:05'),
(8, 'Ivy Pierce', 137, 'Sed ut sunt officii', 1, '2019-08-26 00:53:05', '2019-08-26 00:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OTP` int(10) UNSIGNED DEFAULT NULL,
  `OTP_timestamp` datetime DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `email`, `email_verified_at`, `phone`, `OTP`, `OTP_timestamp`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', NULL, NULL, 'superadmin@admin.com', NULL, NULL, 7595, '2019-08-19 05:04:00', '$2y$10$I3HOZFo9PdNO5s4cb8zlf.CDkRcoN2ExKf/7XI7SQD.A6gHmy3HJG', 'dIKx3jNfXtYj6jACY2CQ2Q0thcryHNQGhXqA8QAaFyxQyTX1EX02wlNXe2Gw', '2019-08-08 23:32:32', '2019-08-18 23:19:00'),
(10, 'Driver One', NULL, NULL, 'driver1@test.com', NULL, NULL, 7595, '2019-08-19 05:04:00', '$2y$10$B1NjAXiMObQJTcxG1CA7WeO3tqHcO.2se36W9V6z7LcTwbsB.lVVi', NULL, '2019-08-09 00:17:03', '2019-08-18 23:19:00'),
(27, '123123123123', 'dSsd', NULL, NULL, NULL, '77787878877878', 8555, '2019-08-19 05:13:24', NULL, NULL, '2019-08-11 00:50:12', '2019-08-20 13:44:04'),
(28, 'Utsav', NULL, NULL, 'fasdfd@fasdf.cofasdf', NULL, NULL, 7595, '2019-08-19 05:04:00', '$2y$10$2b2lrmpFKG7SDokOdormb.5luM6yc0KB7ph13knZuFNNbXQDkgkGO', NULL, '2019-08-11 03:13:04', '2019-08-18 23:19:00'),
(29, 'Quentin', 'Owens', NULL, 'nomuguce@mailinator.com', NULL, '+1 (523) 536-8227', NULL, NULL, NULL, NULL, '2019-08-11 03:56:15', '2019-08-11 03:56:15'),
(30, 'Willa', 'Abbott', NULL, 'xamizykazy@mailinator.net', NULL, '+1 (493) 367-3891', NULL, NULL, NULL, NULL, '2019-08-11 03:57:39', '2019-08-11 03:57:39'),
(31, 'Dustin', 'Miranda', NULL, 'dygapuda@mailinator.com', NULL, '+1 (826) 921-1802', NULL, NULL, NULL, NULL, '2019-08-11 03:58:36', '2019-08-11 03:58:36'),
(32, 'Keith', 'Harding', NULL, 'ryhuz@mailinator.com', NULL, '+1 (414) 861-4352', NULL, NULL, NULL, NULL, '2019-08-11 04:00:20', '2019-08-11 04:00:20'),
(33, 'Utsav', 'Shrestha', NULL, 'shrestsav@gmail.com', NULL, '9808224917', NULL, NULL, NULL, NULL, '2019-08-13 01:03:27', '2019-08-13 01:03:27'),
(34, 'Arthur', 'Scott', NULL, 'dyzi@mailinator.com', NULL, '+1 (181) 319-7198', NULL, NULL, NULL, NULL, '2019-08-13 01:05:54', '2019-08-13 01:05:54'),
(35, 'Macey', 'Cochran', NULL, 'kyhuwexud@mailinator.com', NULL, '+1 (967) 666-7117', NULL, NULL, NULL, NULL, '2019-08-13 01:06:35', '2019-08-13 01:06:35'),
(36, 'Shad', 'Woodward', NULL, 'lonozyxen@mailinator.com', NULL, '+1 (458) 594-4094', NULL, NULL, NULL, NULL, '2019-08-14 05:03:41', '2019-08-14 05:03:41'),
(37, 'Driver', 'One', NULL, NULL, NULL, '+9779808554787', NULL, NULL, NULL, NULL, '2019-08-16 04:10:34', '2019-08-16 04:10:34'),
(38, 'Yeo', 'Brewer', NULL, 'jasaxyq@mailinator.com', NULL, '+1 (485) 449-1742', NULL, NULL, NULL, NULL, '2019-08-16 04:11:45', '2019-08-16 04:11:45'),
(39, 'Perry', 'Ward', NULL, 'zumygabim@mailinator.com', NULL, '+1 (865) 139-6024', NULL, NULL, NULL, NULL, '2019-08-16 04:12:48', '2019-08-16 04:12:48'),
(40, 'Driver', 'One', NULL, NULL, NULL, '+9779808224918', 2436, '2019-08-19 05:03:10', NULL, NULL, '2019-08-16 04:14:52', '2019-08-18 23:18:10'),
(41, 'Driver', 'Two', NULL, NULL, NULL, '+9779808224919', NULL, NULL, NULL, NULL, '2019-08-16 04:15:32', '2019-08-16 04:15:32'),
(43, NULL, NULL, NULL, 'kunila@mailinator.net', NULL, NULL, NULL, NULL, '$2y$10$zzaCndE25/hzb1xz08YUWudxTTb6rzFsLlPpdl/03Ej4Uam/DjGwK', NULL, '2019-08-19 01:52:30', '2019-08-19 01:52:30'),
(42, NULL, NULL, NULL, NULL, NULL, '+97798082fsdfsdf24918', 2302, '2019-08-19 05:05:36', NULL, NULL, '2019-08-18 23:20:36', '2019-08-18 23:20:36'),
(44, NULL, NULL, NULL, 'funucylelu@mailinator.com', NULL, NULL, NULL, NULL, '$2y$10$coFJyGBIYTTKhgrK0foD1.uysQqSkzRiAClLrrp482s/Xj0jGFbH2', NULL, '2019-08-19 01:53:28', '2019-08-19 01:53:28'),
(45, NULL, NULL, NULL, 'pycaxoveze@mailinator.net', NULL, NULL, NULL, NULL, '$2y$10$tE7ZbB3qGzs1izCbpUwT6.MDVb1afoqDKuaeTkUgTfbdrC9QXp52q', NULL, '2019-08-19 01:55:45', '2019-08-19 01:55:45'),
(46, 'Taraa', 'Manandhar', NULL, 'test@maill.com', NULL, '+9779810101010', 6544, '2019-08-20 08:17:39', NULL, NULL, '2019-08-20 13:17:39', '2019-08-20 14:11:54'),
(47, 'like', 'it', NULL, NULL, NULL, '+9779841111111', 1712, '2019-08-20 08:18:00', NULL, NULL, '2019-08-20 13:18:00', '2019-08-20 13:18:00'),
(48, 'Driver Three', '3', NULL, NULL, NULL, '+9779808224925', NULL, NULL, NULL, NULL, '2019-08-21 04:52:49', '2019-08-21 04:52:49'),
(49, 'Imani', 'Vincent', NULL, 'wupucag@mailinator.net', NULL, '+1 (118) 918-8344', NULL, NULL, NULL, NULL, '2019-08-21 04:58:59', '2019-08-21 04:58:59'),
(50, 'Christian', 'Knowles', NULL, 'xamuvunic@mailinator.net', NULL, '+1 (164) 562-8567', NULL, NULL, NULL, NULL, '2019-08-21 05:00:41', '2019-08-21 05:00:41'),
(51, 'Test', 'Driver', NULL, 'abcd@efgh.ijk', NULL, '+9779808224988', 7034, '2019-08-21 10:48:57', NULL, NULL, '2019-08-21 05:01:45', '2019-08-21 05:03:57'),
(52, 'Test', 'Customer', NULL, NULL, NULL, '+9779808224989', 1111, '2019-08-23 05:41:32', NULL, NULL, '2019-08-21 05:07:26', '2019-08-22 23:56:32'),
(53, NULL, NULL, NULL, NULL, NULL, '+9778951124179', 1111, '2019-08-23 05:35:08', NULL, NULL, '2019-08-21 05:34:33', '2019-08-22 23:50:08'),
(54, NULL, NULL, NULL, NULL, NULL, '+9778951sgdfgsdfg', 1111, '2019-08-23 05:35:17', NULL, NULL, '2019-08-22 23:50:17', '2019-08-22 23:50:17'),
(55, NULL, NULL, NULL, NULL, NULL, '+9779808224fasdfsadf989', 1111, '2019-08-23 05:39:04', NULL, NULL, '2019-08-22 23:54:04', '2019-08-22 23:54:04'),
(56, NULL, NULL, NULL, NULL, NULL, 'sdfsdfsdfsdf', 1111, '2019-08-23 09:01:00', NULL, NULL, '2019-08-22 23:56:56', '2019-08-23 03:16:00'),
(57, 'Aman', 'Role (Driver)', NULL, 'driver@ddd.com', NULL, '+9779851124179', 1111, '2019-08-26 04:38:37', NULL, NULL, '2019-08-25 22:53:38', '2019-08-25 22:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(280) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_id` smallint(6) DEFAULT NULL,
  `map_coordinates` varchar(480) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building_community` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` smallint(6) DEFAULT NULL COMMENT 'See Config',
  `appartment_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_default` smallint(6) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `name`, `area_id`, `map_coordinates`, `building_community`, `type`, `appartment_no`, `remarks`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Drop here', 2, NULL, 'ofasdfasdfsdf', NULL, NULL, NULL, 0, '2019-08-13 03:53:37', '2019-08-17 23:59:43'),
(2, 27, 'fsdfsdfsdf', 2, NULL, 'ofasdfasdfsdf', NULL, NULL, NULL, 0, '2019-08-13 23:25:30', '2019-08-18 23:57:09'),
(3, 27, NULL, 1, NULL, 'ofasdfasdfsdf', 1, NULL, NULL, 0, '2019-08-13 23:25:33', '2019-08-13 23:25:33'),
(4, 27, NULL, 1, NULL, 'ofasdfasdfsdf', 1, NULL, NULL, 0, '2019-08-13 23:34:00', '2019-08-13 23:34:00'),
(5, 27, NULL, 1, NULL, 'ofasdfasdfsdf', 1, NULL, NULL, 0, '2019-08-13 23:34:33', '2019-08-13 23:34:33'),
(6, 27, NULL, 1, NULL, 'ofasdfasdfsdf', 1, NULL, NULL, 0, '2019-08-13 23:35:18', '2019-08-13 23:35:18'),
(7, 27, NULL, 1, NULL, 'ofasdfasdfsdf', 2, NULL, NULL, 0, '2019-08-14 01:29:58', '2019-08-14 01:29:58'),
(8, 27, NULL, 1, NULL, 'ofasdfasdfsdftest', 2, NULL, NULL, 0, '2019-08-18 00:12:01', '2019-08-18 00:12:01'),
(9, 27, NULL, 1, 'fasdfasdf', 'ofasdfasdfsdftest', 2, NULL, NULL, 0, '2019-08-18 00:12:20', '2019-08-18 00:12:20'),
(10, 27, NULL, 1, 'fasdfasdf', 'ofasdfasdfsdftest', 2, NULL, NULL, 0, '2019-08-18 00:13:11', '2019-08-18 00:13:11'),
(11, 27, NULL, 1, 'map', 'ofasdfasdfsdftest', 2, NULL, NULL, 0, '2019-08-18 00:13:22', '2019-08-18 00:13:22'),
(12, 27, NULL, 1, NULL, '34343434', 2, NULL, NULL, 0, '2019-08-18 00:20:02', '2019-08-18 00:20:02'),
(13, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 00:20:15', '2019-08-18 00:20:15'),
(14, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 00:24:11', '2019-08-18 00:24:11'),
(15, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 00:24:34', '2019-08-18 00:24:34'),
(16, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 00:28:04', '2019-08-18 00:28:04'),
(17, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 01:19:55', '2019-08-18 01:19:55'),
(18, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 23:15:49', '2019-08-18 23:15:49'),
(19, 27, NULL, 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-18 23:15:58', '2019-08-18 23:15:58'),
(20, 52, 'Bhadrakali', 1, '{\"lattitude\":\"fdsf\",\"longitude\":\"sdfsdf\"}', '34343434', 2, NULL, NULL, 0, '2019-08-21 05:09:32', '2019-08-21 05:09:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
CREATE TABLE IF NOT EXISTS `user_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_address` smallint(6) DEFAULT NULL COMMENT 'From User Address Table',
  `area_id` smallint(6) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `joined_date` date DEFAULT NULL,
  `documents` longtext COLLATE utf8mb4_unicode_ci,
  `referral_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_details_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `gender`, `home_address`, `area_id`, `dob`, `photo`, `description`, `joined_date`, `documents`, `referral_id`, `referred_by`, `created_at`, `updated_at`) VALUES
(1, 27, NULL, NULL, 1, '2019-08-14', 'dp_user_27.png', 'Non molestias autem', '2019-08-13', NULL, 'NB425RFG', 'asdasdsad', '2019-08-14 05:03:41', '2019-08-20 14:07:53'),
(2, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-16 04:14:52', '2019-08-16 04:14:52'),
(3, 41, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-16 04:15:32', '2019-08-16 04:15:32'),
(4, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TAR167YX', NULL, '2019-08-20 13:18:44', '2019-08-20 13:18:44'),
(5, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-21 04:52:50', '2019-08-21 04:52:50'),
(6, 49, NULL, NULL, NULL, NULL, NULL, 'Temporibus et amet', NULL, NULL, NULL, NULL, '2019-08-21 04:58:59', '2019-08-21 04:58:59'),
(7, 50, NULL, NULL, 4, NULL, NULL, 'Sed aut et mollitia', NULL, NULL, NULL, NULL, '2019-08-21 05:00:41', '2019-08-21 05:00:41'),
(8, 51, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-08-21 05:01:45', '2019-08-21 05:01:45'),
(9, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TES128XL', 'asdasdsad', '2019-08-21 05:08:24', '2019-08-21 05:08:24');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
