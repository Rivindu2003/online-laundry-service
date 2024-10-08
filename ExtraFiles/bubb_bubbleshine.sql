-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 07, 2024 at 03:25 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bubb_bubbleshine`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `delivery_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tracking_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `total_amount`, `status`, `delivery_address`, `payment_status`, `tracking_number`) VALUES
(18, 40, '2024-10-07 14:36:05', 3500.00, 'Pending', 'No 56A, Sri Gunerathne Mawatha, Mount Lavinia', 'Unpaid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `review` text COLLATE utf8mb4_general_ci NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `user_name`, `review`, `review_date`) VALUES
(21, 40, 'Rivindu Rathnayake', 'Your Laundry Service is the best. ', '2024-10-07 14:36:34');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `description`, `price`) VALUES
(17, 'Express Same-Day Service', 'Get your laundry washed, dried, and folded all in one day! Perfect for when you need your clothes in a hurry.', 1500.00),
(18, 'Dry Cleaning', 'Gentle cleaning for delicate fabrics. Keep your suits and dresses looking fresh and new without the worry of damage.', 1000.00),
(19, ' Wash & Dry Service', 'Enjoy a thorough wash and quick dry for all your everyday garments. Freshness delivered to your doorstep!', 1350.00),
(20, 'Ironing & Pressing', 'Crisp and wrinkle-free clothes for that polished look. Perfect for workwear, shirts, and formal attire.', 500.00),
(21, 'Comforter Cleaning', 'Refresh your bedding with our specialized cleaning service for comforters and blankets. Sleep in comfort!', 750.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_orders`
--

DROP TABLE IF EXISTS `service_orders`;
CREATE TABLE IF NOT EXISTS `service_orders` (
  `service_order_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `service_id` int NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `service_date` datetime NOT NULL,
  `delivery_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`service_order_id`),
  KEY `order_id` (`order_id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_orders`
--

INSERT INTO `service_orders` (`service_order_id`, `order_id`, `service_id`, `order_date`, `status`, `service_date`, `delivery_address`, `payment_status`, `notes`) VALUES
(30, 18, 17, '2024-10-07 14:36:05', 'Pending', '2024-10-09 23:09:00', 'No 56A, Sri Gunerathne Mawatha, Mount Lavinia', 'Unpaid', NULL),
(31, 18, 18, '2024-10-07 14:36:05', 'Pending', '2024-10-09 23:09:00', 'No 56A, Sri Gunerathne Mawatha, Mount Lavinia', 'Unpaid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shop_managers`
--

DROP TABLE IF EXISTS `shop_managers`;
CREATE TABLE IF NOT EXISTS `shop_managers` (
  `manager_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`manager_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_managers`
--

INSERT INTO `shop_managers` (`manager_id`, `username`, `password_hash`, `created_at`, `first_name`, `last_name`, `phone_number`, `email`) VALUES
(4, 'dinuli', '$2y$10$IiqGHvSnMkv9o10ZzlpLAuvA9hirgbSdEu7xcQYLPdshmaFInEs5u', '2024-10-07 14:29:42', 'Dinuli', 'Boteju', '0745895224', 'dinuli@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `support_admin`
--

DROP TABLE IF EXISTS `support_admin`;
CREATE TABLE IF NOT EXISTS `support_admin` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone_number` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Open','In Progress','Resolved','Closed') DEFAULT 'Open',
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `support_admin`
--

INSERT INTO `support_admin` (`ticket_id`, `customer_name`, `customer_email`, `customer_phone_number`, `subject`, `message`, `submission_date`, `status`) VALUES
(4, 'oshan', 'oshan@gmail.com', '0702874500', 'Account Password Rest', 'Please Reset my account password. ', '2024-10-07 20:10:10', 'Open');

-- --------------------------------------------------------

--
-- Table structure for table `support_shop`
--

DROP TABLE IF EXISTS `support_shop`;
CREATE TABLE IF NOT EXISTS `support_shop` (
  `ticket_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone_number` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Open','In Progress','Resolved','Closed') DEFAULT 'Open',
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `address`, `phone_number`, `first_name`, `last_name`) VALUES
(40, 'rivindu', '$2y$10$KmLd8ax2QCAGWOMBg6SU6uER57lEwKqJVqfZBDtPcgTUxMHM4S87a', 'rivindurathnayake2003@gmail.com', '2024-10-07 14:27:24', 'No 56A, Sri Gunerathne Mawatha, Mount Lavinia', '0703716999', 'Rivindu', 'Rathnayake'),
(41, 'haritha', '$2y$10$58D7CNU.muGwC2e4fL7bN.HwvbKwM.gSHVkP7Cu7OVeq0S37YfLDq', 'haritha@gmail.com', '2024-10-07 14:28:49', 'No 56B, Sri Gunerathne Mawatha, Mount Lavinia', '0726105897', 'Haritha', 'Rathnayake');

-- --------------------------------------------------------

--
-- Table structure for table `website_administrators`
--

DROP TABLE IF EXISTS `website_administrators`;
CREATE TABLE IF NOT EXISTS `website_administrators` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_administrators`
--

INSERT INTO `website_administrators` (`admin_id`, `username`, `password_hash`, `created_at`, `first_name`, `last_name`, `phone_number`, `email`) VALUES
(2, 'rivindu_admin', '$2y$10$aFTNISAOlGnf/iQU79iK6eOAufX3hImTR2OtxIKrhUFtyyEn1q1rK', '2024-10-01 08:40:22', '', '', NULL, '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `service_orders`
--
ALTER TABLE `service_orders`
  ADD CONSTRAINT `service_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_orders_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
