-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.5.18-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table ebpayments.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.cookies_votes
CREATE TABLE IF NOT EXISTS `cookies_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` text NOT NULL,
  `last_ip` text NOT NULL,
  `usr` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.data_referral_codes
CREATE TABLE IF NOT EXISTS `data_referral_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referral_code` text NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.data_reward_codes
CREATE TABLE IF NOT EXISTS `data_reward_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reward_code` text NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.data_youtube_videos
CREATE TABLE IF NOT EXISTS `data_youtube_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` text NOT NULL,
  `uploader` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `unix_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.discount_codes
CREATE TABLE IF NOT EXISTS `discount_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` text NOT NULL,
  `percentage` int(11) NOT NULL,
  `uses_left` int(11) NOT NULL,
  `expires` bigint(20) NOT NULL,
  `store` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.finalized_votes
CREATE TABLE IF NOT EXISTS `finalized_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` text NOT NULL,
  `username` text NOT NULL,
  `toplist` text NOT NULL,
  `ip_address` text NOT NULL,
  `reward_amount` int(11) NOT NULL,
  `time_voted` bigint(20) NOT NULL,
  `claimed` int(11) NOT NULL DEFAULT 0,
  `vpn` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.pending_votes
CREATE TABLE IF NOT EXISTS `pending_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` text NOT NULL,
  `username` text NOT NULL,
  `ip_address` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28665 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.phone_numbers
CREATE TABLE IF NOT EXISTS `phone_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text DEFAULT NULL,
  `phone_number` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10720 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.realm_refund_wallets
CREATE TABLE IF NOT EXISTS `realm_refund_wallets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(75) NOT NULL DEFAULT '0',
  `wallet_amount` bigint(20) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE KEY` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10448 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.realm_transfer_wallets
CREATE TABLE IF NOT EXISTS `realm_transfer_wallets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(75) NOT NULL DEFAULT '0',
  `wallet_amount` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE KEY` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=891 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.request_logs
CREATE TABLE IF NOT EXISTS `request_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` text NOT NULL,
  `get_data` text NOT NULL,
  `post_data` text NOT NULL,
  `ip_address` text NOT NULL,
  `time` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62437 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.statistics_online
CREATE TABLE IF NOT EXISTS `statistics_online` (
  `id` int(11) NOT NULL DEFAULT 1,
  `online` int(11) NOT NULL,
  `wilderness` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.stores
CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` text NOT NULL,
  `logo` text NOT NULL,
  `favicon` text NOT NULL,
  `color` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.store_categories
CREATE TABLE IF NOT EXISTS `store_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store` text NOT NULL,
  `category_name` text NOT NULL,
  `category_image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.store_items
CREATE TABLE IF NOT EXISTS `store_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store` text NOT NULL,
  `item_name` text NOT NULL,
  `item_amount` int(11) NOT NULL DEFAULT 1,
  `item_id` int(11) NOT NULL DEFAULT 1,
  `item_description` text NOT NULL,
  `default_price` double NOT NULL DEFAULT 0,
  `sale_price` double NOT NULL DEFAULT 0,
  `category` text DEFAULT NULL,
  `amount_purchased` int(11) DEFAULT 0,
  `hot_deal` int(11) DEFAULT 0,
  `image` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=769 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.store_payments
CREATE TABLE IF NOT EXISTS `store_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store` text NOT NULL,
  `username` text NOT NULL,
  `product` text NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `product_price` int(11) DEFAULT NULL,
  `total_received` int(11) DEFAULT 0,
  `status` text DEFAULT NULL,
  `invoice_id` text DEFAULT NULL,
  `purchase_date` text DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `raw_data` text DEFAULT NULL,
  `paypal_fee` double DEFAULT NULL,
  `unix_time` bigint(20) DEFAULT NULL,
  `claimed_bonus` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44410 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.toplists
CREATE TABLE IF NOT EXISTS `toplists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` text NOT NULL,
  `toplist_name` text NOT NULL,
  `toplist_link` text NOT NULL,
  `toplist_image` text NOT NULL,
  `toplist_description` text NOT NULL,
  `reward_amount` int(11) NOT NULL DEFAULT 0,
  `time_needed` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.top_donators
CREATE TABLE IF NOT EXISTS `top_donators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `amount_donated` int(11) NOT NULL,
  `store` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18057 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

-- Dumping structure for table ebpayments.used_invoice_ids
CREATE TABLE IF NOT EXISTS `used_invoice_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38801 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
