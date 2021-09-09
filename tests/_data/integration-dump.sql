-- Adminer 4.8.0 MySQL 5.7.28 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `value` text COLLATE utf8mb4_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `config` (`id`, `code`, `value`) VALUES
(1,	'braintree_merchant_id',	''),
(2,	'braintree_public_key',	''),
(3,	'braintree_private_key',	''),
(4,	'braintree_environment',	'sandbox');

DROP TABLE IF EXISTS `subscription`;
CREATE TABLE `subscription` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET ascii NOT NULL,
  `braintree_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `braintree_plan_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `braintree_customer_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `price` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `currency_iso` varchar(10) COLLATE utf8mb4_czech_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `billing_period_start_date` datetime NOT NULL,
  `billing_period_end_date` datetime NOT NULL,
  `first_billing_date` datetime NOT NULL,
  `next_billing_date` datetime DEFAULT NULL,
  `paid_through_date` datetime DEFAULT NULL,
  `merchant_account_id` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `never_expires` tinyint(1) unsigned NOT NULL,
  `next_billing_period_amount` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `payment_method_token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `subscription` (`id`, `uuid`, `braintree_id`, `braintree_plan_id`, `braintree_customer_id`, `created_at`, `updated_at`, `price`, `currency_iso`, `status`, `billing_period_start_date`, `billing_period_end_date`, `first_billing_date`, `next_billing_date`, `paid_through_date`, `merchant_account_id`, `never_expires`, `next_billing_period_amount`, `payment_method_token`) VALUES
(1,	'98da39a4-d2ad-49f9-b988-e8f8c0c9f912',	'123456',	'789',	'666',	'2021-09-09 15:53:30',	'2021-09-09 15:53:30',	'10.00',	'EUR',	'Active',	'2021-09-09 17:53:31',	'2021-09-09 17:53:31',	'2021-10-09 00:00:00',	'2021-10-09 00:00:00',	NULL,	'merchant',	1,	'10.00',	'paymentToken');

-- 2021-09-09 19:16:18
