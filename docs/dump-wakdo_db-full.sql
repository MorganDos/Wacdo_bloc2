-- Wakdo database dump
-- Generated: 2026-04-11 11:40:16
-- Database: wakdo_db
-- Data: yes
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `menu_product`;
DROP TABLE IF EXISTS `order_product`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','prep','cashier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cashier',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
('1', 'Wakdo Admin', 'admin@wakdo.test', '2026-04-11 11:38:39', '$2y$12$4VZy7DFIj8DhfAO16oDps.NylYPn8KkSI8b9ZoiFCP5EWMJc7OmPi', NULL, 'admin', '2026-04-11 11:38:39', '2026-04-11 11:38:39'),
('2', 'Wakdo Prep', 'prep@wakdo.test', '2026-04-11 11:38:39', '$2y$12$6YDYpRXsEQt4VoCev1eRkeksywD5JQMtohF6u12WHNi64c9CexWIK', NULL, 'prep', '2026-04-11 11:38:39', '2026-04-11 11:38:39'),
('3', 'Wakdo Cashier', 'cashier@wakdo.test', '2026-04-11 11:38:39', '$2y$12$S.o96ZaQWNhbjM4kUy6Oz.eskzLbzuBArithpQWn5QPwp6SnXBA8u', NULL, 'cashier', '2026-04-11 11:38:40', '2026-04-11 11:38:40');

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `availability` enum('available','out_of_stock') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `image`, `availability`, `created_at`, `updated_at`) VALUES
('1', 'Big Mac', 'Burger classique avec salade, fromage et sauce.', 'burger', '6.00', '/img/produits/burgers/BIGMAC.png', 'available', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('2', 'Mc Chicken', 'Burger au poulet pané.', 'burger', '7.30', '/img/produits/burgers/MCCHICKEN.png', 'available', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('3', 'Moyenne Frite', 'Portion moyenne de frites.', 'frites', '2.75', '/img/produits/frites/MOYENNE_FRITE.png', 'available', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('4', 'Coca Cola', 'Boisson fraîche au cola.', 'boissons', '1.90', '/img/produits/boissons/coca-cola.png', 'available', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('5', 'Brownie', 'Dessert au chocolat.', 'desserts', '2.60', '/img/produits/desserts/brownies.png', 'available', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('6', 'Mc Wrap Poulet Bacon', 'Wrap au poulet avec bacon.', 'wraps', '3.30', '/img/produits/wraps/MCWRAP-POULET-BACON.png', 'available', '2026-04-11 11:38:40', '2026-04-11 11:38:40');

CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','ready','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `delivery_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_ticket_number_unique` (`ticket_number`),
  KEY `orders_delivery_at_index` (`delivery_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `ticket_number`, `status`, `delivery_at`, `created_at`, `updated_at`) VALUES
('1', 'WKD-DEMO01', 'pending', '2026-04-11 11:53:40', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('2', 'WKD-DEMO02', 'ready', '2026-04-11 12:08:40', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('3', 'WKD-DEMO03', 'delivered', '2026-04-11 11:18:40', '2026-04-11 11:38:40', '2026-04-11 11:38:40');

CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menus` (`id`, `name`, `description`, `price`, `is_active`, `created_at`, `updated_at`) VALUES
('1', 'Classic Menu', 'Burger classique, frites et cola.', '10.90', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('2', 'Chicken Menu', 'Burger au poulet, frites et cola.', '11.40', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('3', 'Dessert Menu', 'Burger classique avec dessert et cola.', '12.50', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40');

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1', '0001_01_01_000000_create_users_table', '1'),
('2', '2026_02_11_094808_create_products_table', '1'),
('3', '2026_02_11_095223_create_orders_table', '1'),
('4', '2026_02_11_095730_create_order_product_table', '1'),
('5', '2026_03_07_100000_create_menus_table', '1'),
('6', '2026_03_07_100100_create_menu_product_table', '1'),
('7', '2026_03_10_120000_align_existing_schema_with_bloc2', '1');

CREATE TABLE `order_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_product_order_id_product_id_unique` (`order_id`,`product_id`),
  KEY `order_product_product_id_foreign` (`product_id`),
  CONSTRAINT `order_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_product` (`id`, `order_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
('1', '1', '1', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('2', '1', '5', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('3', '2', '1', '2', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('4', '2', '4', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('5', '3', '5', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('6', '3', '4', '2', '2026-04-11 11:38:40', '2026-04-11 11:38:40');

CREATE TABLE `menu_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_product_menu_id_product_id_unique` (`menu_id`,`product_id`),
  KEY `menu_product_product_id_foreign` (`product_id`),
  CONSTRAINT `menu_product_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_product` (`id`, `menu_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
('1', '1', '1', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('2', '1', '3', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('3', '1', '4', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('4', '2', '2', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('5', '2', '3', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('6', '2', '4', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('7', '3', '1', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('8', '3', '5', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40'),
('9', '3', '4', '1', '2026-04-11 11:38:40', '2026-04-11 11:38:40');

SET FOREIGN_KEY_CHECKS=1;
