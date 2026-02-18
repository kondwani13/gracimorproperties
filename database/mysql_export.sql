-- MySQL Export from SQLite
-- Generated: 2026-02-18 06:32:17
-- Gracimor Properties Database

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ----------------------------
-- Table: apartment_images
-- ----------------------------
DROP TABLE IF EXISTS `apartment_images`;
CREATE TABLE `apartment_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `apartment_id` BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `order` INT DEFAULT 0,
  `is_main` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `apartment_images` ADD INDEX `apartment_images_apartment_id_order_index` (`apartment_id`, `order`);

-- Data for apartment_images (9 rows)
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (121, 1, 'https://i.ibb.co/qY439qPT/flat1-1.jpg', 'Flat 1', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (122, 2, 'https://i.ibb.co/ynnD5nD0/flat1-2.jpg', 'Flat 2', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (123, 3, 'https://i.ibb.co/PZfwJwxJ/flat1-3.jpg', 'Flat 3', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (124, 4, 'https://i.ibb.co/VYqfxrsv/flat1-4.jpg', 'Flat 4', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (125, 5, 'https://i.ibb.co/tkvV5f3/flat1-5.jpg', 'Flat 5', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (126, 6, 'https://i.ibb.co/F4qPCbx7/flat1-6.jpg', 'Flat 6', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (127, 7, 'https://i.ibb.co/MxYW0PcV/flat1-7.jpg', 'Flat 7', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (128, 8, 'https://i.ibb.co/j9KWnJTW/flat1-8.jpg', 'Flat 8', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');
INSERT INTO `apartment_images` (`id`, `apartment_id`, `image_path`, `caption`, `order`, `is_main`, `created_at`, `updated_at`) VALUES (129, 9, 'https://i.ibb.co/fYQnVss4/flat1-9.jpg', 'Flat 9', '0', 1, '2026-02-17 13:03:02', '2026-02-17 13:03:02');

-- ----------------------------
-- Table: apartments
-- ----------------------------
DROP TABLE IF EXISTS `apartments`;
CREATE TABLE `apartments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `state` VARCHAR(255) NOT NULL,
  `country` VARCHAR(255) NOT NULL,
  `postal_code` VARCHAR(255) NOT NULL,
  `latitude` DECIMAL(10,2) NULL,
  `longitude` DECIMAL(10,2) NULL,
  `price_per_night` DECIMAL(10,2) NOT NULL,
  `cleaning_fee` DECIMAL(10,2) DEFAULT 0,
  `service_fee` DECIMAL(10,2) DEFAULT 0,
  `bedrooms` INT NOT NULL,
  `bathrooms` INT NOT NULL,
  `max_guests` INT NOT NULL,
  `size_sqft` DECIMAL(10,2) NULL,
  `property_type` VARCHAR(255) DEFAULT 'apartment',
  `amenities` TEXT NULL,
  `house_rules` TEXT NULL,
  `main_image` VARCHAR(255) NULL,
  `minimum_stay` INT DEFAULT 1,
  `maximum_stay` INT NULL,
  `check_in_time` TIME DEFAULT '15:00:00',
  `check_out_time` TIME DEFAULT '11:00:00',
  `is_available` TINYINT(1) DEFAULT 1,
  `is_featured` TINYINT(1) DEFAULT 0,
  `rating` DECIMAL(10,2) DEFAULT 0,
  `total_reviews` INT DEFAULT 0,
  `views_count` INT DEFAULT 0,
  `booking_count` INT DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `apartments` ADD UNIQUE INDEX `apartments_slug_unique` (`slug`);
ALTER TABLE `apartments` ADD INDEX `apartments_is_featured_index` (`is_featured`);
ALTER TABLE `apartments` ADD INDEX `apartments_price_per_night_is_available_index` (`price_per_night`, `is_available`);
ALTER TABLE `apartments` ADD INDEX `apartments_city_is_available_index` (`city`, `is_available`);

-- Data for apartments (10 rows)
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 'Flat 1', 'flat-1', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/qY439qPT/flat1-1.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', 5, '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (2, 'Flat 2', 'flat-2', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/ynnD5nD0/flat1-2.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (3, 'Flat 3', 'flat-3', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/PZfwJwxJ/flat1-3.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (4, 'Flat 4', 'flat-4', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/VYqfxrsv/flat1-4.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (5, 'Flat 5', 'flat-5', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/tkvV5f3/flat1-5.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', 2, '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (6, 'Flat 6', 'flat-6', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/F4qPCbx7/flat1-6.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (7, 'Flat 7', 'flat-7', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/MxYW0PcV/flat1-7.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (8, 'Flat 8', 'flat-8', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/j9KWnJTW/flat1-8.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', 33, '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (9, 'Flat 9', 'flat-9', 'Welcome to Gracimor Hyndland Estate — your home away from home in Lusaka. This fully furnished apartment offers modern comfort with 3 spacious bedrooms, 2 living rooms, a modern kitchen, dining area, garage, and a private garden.\n\nBOOKING RATES\n\nDaily Rates:\n1 Adult — K3,000 | 2 Adults — K3,500 | 3 Adults — K4,000 | 4 Adults — K4,300\n\nMonthly Rates (30+ days):\n1 Adult — K50,000 | 2 Adults — K55,000 | 3 Adults — K60,000 | 4 Adults — K65,000\n\nWeekly Rate (7+ days):\n10% discount applied to your daily rate.\n\nAll apartments cater for a maximum of 4 adults. There are no restrictions for kids. Enjoy free Starlink WiFi, private parking, hot shower/jacuzzi, air conditioning, Netflix streaming, and a beautiful garden view.', 'Sub 4 of Sub B3 Farm 382A', 'Lusaka', 'Lusaka', 'Zambia', 10101, -15.3875, 28.3228, 3000, '0', '0', 3, 3, 4, NULL, 'apartment', '["Free WiFi (Starlink)","Free Parking","Private Parking","On-site Parking","Garden View","Hot Shower\\/Tub\\/Jacuzzi","Family Friendly","Family Rooms","Air Conditioning","Streaming Service (Netflix)","3 Bedrooms","Modern Kitchen","2 Living Rooms","Dining Area","Garage","Private Garden"]', '["Maximum 4 adults per apartment","No restrictions for kids","No smoking indoors","No pets allowed","Quiet hours: 10 PM - 6 AM"]', 'https://i.ibb.co/fYQnVss4/flat1-9.jpg', 1, NULL, '14:00', '10:00', 1, 1, 5, '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 13:39:00', NULL);
INSERT INTO `apartments` (`id`, `title`, `slug`, `description`, `address`, `city`, `state`, `country`, `postal_code`, `latitude`, `longitude`, `price_per_night`, `cleaning_fee`, `service_fee`, `bedrooms`, `bathrooms`, `max_guests`, `size_sqft`, `property_type`, `amenities`, `house_rules`, `main_image`, `minimum_stay`, `maximum_stay`, `check_in_time`, `check_out_time`, `is_available`, `is_featured`, `rating`, `total_reviews`, `views_count`, `booking_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (10, 'Minima recusandae est.', 'magni-accusantium-quia-neque-consequatur-accusamus-consequatur-veniam-harum', 'Aut illo est accusantium esse similique sunt reprehenderit. Modi rerum saepe qui eius perspiciatis. Ad quibusdam adipisci enim officiis qui.', '973 Johnston Row Apt. 460\nAdanberg, NE 30509', 'West Emerald', 'Arizona', 'United States of America', '23013-6393', NULL, NULL, 313, '0', '0', 4, 1, 3, NULL, 'apartment', NULL, NULL, NULL, 1, NULL, '15:00:00', '11:00:00', 1, 1, '0', '0', '0', '0', '2026-02-14 09:39:55', '2026-02-17 12:56:15', '2026-02-17 12:56:15');

-- ----------------------------
-- Table: bills
-- ----------------------------
DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `apartment_id` BIGINT UNSIGNED NULL,
  `type` VARCHAR(255) DEFAULT 'other',
  `description` TEXT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `due_date` DATE NOT NULL,
  `paid_date` DATE NULL,
  `status` VARCHAR(255) DEFAULT 'pending',
  `reference_number` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `bills` ADD INDEX `bills_apartment_id_index` (`apartment_id`);
ALTER TABLE `bills` ADD INDEX `bills_type_status_index` (`type`, `status`);

-- ----------------------------
-- Table: blocked_dates
-- ----------------------------
DROP TABLE IF EXISTS `blocked_dates`;
CREATE TABLE `blocked_dates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `apartment_id` BIGINT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `reason` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `blocked_dates` ADD INDEX `blocked_dates_apartment_id_date_index` (`apartment_id`, `date`);
ALTER TABLE `blocked_dates` ADD UNIQUE INDEX `blocked_dates_apartment_id_date_unique` (`apartment_id`, `date`);

-- ----------------------------
-- Table: bookings
-- ----------------------------
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `booking_number` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `apartment_id` BIGINT UNSIGNED NOT NULL,
  `check_in` DATE NOT NULL,
  `check_out` DATE NOT NULL,
  `number_of_guests` INT NOT NULL,
  `number_of_nights` INT NOT NULL,
  `price_per_night` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `cleaning_fee` DECIMAL(10,2) DEFAULT 0,
  `service_fee` DECIMAL(10,2) DEFAULT 0,
  `tax_amount` DECIMAL(10,2) DEFAULT 0,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(255) DEFAULT 'pending',
  `payment_status` VARCHAR(255) DEFAULT 'pending',
  `special_requests` TEXT NULL,
  `cancellation_reason` TEXT NULL,
  `cancelled_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `bookings` ADD UNIQUE INDEX `bookings_booking_number_unique` (`booking_number`);
ALTER TABLE `bookings` ADD INDEX `bookings_status_index` (`status`);
ALTER TABLE `bookings` ADD INDEX `bookings_booking_number_index` (`booking_number`);
ALTER TABLE `bookings` ADD INDEX `bookings_apartment_id_check_in_check_out_index` (`apartment_id`, `check_in`, `check_out`);
ALTER TABLE `bookings` ADD INDEX `bookings_user_id_status_index` (`user_id`, `status`);

-- Data for bookings (1 rows)
INSERT INTO `bookings` (`id`, `booking_number`, `user_id`, `apartment_id`, `check_in`, `check_out`, `number_of_guests`, `number_of_nights`, `price_per_night`, `subtotal`, `cleaning_fee`, `service_fee`, `tax_amount`, `total_amount`, `status`, `payment_status`, `special_requests`, `cancellation_reason`, `cancelled_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 'BK-CLGDVIR6ZU', 1, 1, '2026-02-19 00:00:00', '2026-02-20 00:00:00', 4, 1, 3000, 3000, '0', '0', 300, 3300, 'pending', 'pending', NULL, NULL, NULL, '2026-02-17 13:09:24', '2026-02-17 13:09:24', NULL);

-- ----------------------------
-- Table: cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cache` ADD INDEX `cache_expiration_index` (`expiration`);

-- ----------------------------
-- Table: cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cache_locks` ADD INDEX `cache_locks_expiration_index` (`expiration`);

-- ----------------------------
-- Table: complaints
-- ----------------------------
DROP TABLE IF EXISTS `complaints`;
CREATE TABLE `complaints` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tenant_id` BIGINT UNSIGNED NULL,
  `booking_id` BIGINT UNSIGNED NULL,
  `complainant_name` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `priority` VARCHAR(255) DEFAULT 'medium',
  `status` VARCHAR(255) DEFAULT 'open',
  `resolution_notes` TEXT NULL,
  `resolved_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `complaints` ADD INDEX `complaints_status_priority_index` (`status`, `priority`);

-- ----------------------------
-- Table: consent_forms
-- ----------------------------
DROP TABLE IF EXISTS `consent_forms`;
CREATE TABLE `consent_forms` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tenant_id` BIGINT UNSIGNED NULL,
  `booking_id` BIGINT UNSIGNED NULL,
  `client_name` VARCHAR(255) NOT NULL,
  `client_email` VARCHAR(255) NULL,
  `apartment_id` BIGINT UNSIGNED NOT NULL,
  `check_in` DATE NOT NULL,
  `check_out` DATE NOT NULL,
  `policies_text` TEXT NOT NULL,
  `is_signed` TINYINT(1) DEFAULT 0,
  `signed_at` TIMESTAMP NULL,
  `signature_ip` VARCHAR(255) NULL,
  `pdf_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `consent_forms` ADD INDEX `consent_forms_tenant_id_booking_id_index` (`tenant_id`, `booking_id`);

-- ----------------------------
-- Table: employee_leave_requests
-- ----------------------------
DROP TABLE IF EXISTS `employee_leave_requests`;
CREATE TABLE `employee_leave_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `employee_id` BIGINT UNSIGNED NOT NULL,
  `request_date` DATE NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `type` VARCHAR(255) DEFAULT 'annual',
  `status` VARCHAR(255) DEFAULT 'pending',
  `reason` TEXT NULL,
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `employee_leave_requests` ADD INDEX `employee_leave_requests_employee_id_status_index` (`employee_id`, `status`);

-- ----------------------------
-- Table: employee_salary_records
-- ----------------------------
DROP TABLE IF EXISTS `employee_salary_records`;
CREATE TABLE `employee_salary_records` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `employee_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `month` VARCHAR(255) NOT NULL,
  `payment_date` DATE NOT NULL,
  `payment_method` VARCHAR(255) DEFAULT 'cash',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `employee_salary_records` ADD UNIQUE INDEX `employee_salary_records_employee_id_month_unique` (`employee_id`, `month`);

-- ----------------------------
-- Table: employees
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(255) NULL,
  `position` VARCHAR(255) NOT NULL,
  `salary` DECIMAL(10,2) DEFAULT 0,
  `hire_date` DATE NOT NULL,
  `status` VARCHAR(255) DEFAULT 'active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `employees` ADD UNIQUE INDEX `employees_email_unique` (`email`);
ALTER TABLE `employees` ADD INDEX `employees_status_index` (`status`);

-- ----------------------------
-- Table: failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` VARCHAR(36) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` TEXT NOT NULL,
  `exception` TEXT NOT NULL,
  `failed_at` TEXT DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `failed_jobs` ADD UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid`);

-- ----------------------------
-- Table: favorites
-- ----------------------------
DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `apartment_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `favorites` ADD INDEX `favorites_user_id_index` (`user_id`);
ALTER TABLE `favorites` ADD UNIQUE INDEX `favorites_user_id_apartment_id_unique` (`user_id`, `apartment_id`);

-- ----------------------------
-- Table: job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL DEFAULT 0,
  `pending_jobs` INT NOT NULL DEFAULT 0,
  `failed_jobs` INT NOT NULL DEFAULT 0,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ----------------------------
-- Table: jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` VARCHAR(255) NOT NULL,
  `payload` TEXT NOT NULL,
  `attempts` INT NOT NULL,
  `reserved_at` INT NULL,
  `available_at` INT NOT NULL,
  `created_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `jobs` ADD INDEX `jobs_queue_index` (`queue`);

-- ----------------------------
-- Table: maintenance_costs
-- ----------------------------
DROP TABLE IF EXISTS `maintenance_costs`;
CREATE TABLE `maintenance_costs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `apartment_id` BIGINT UNSIGNED NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(255) DEFAULT 'general',
  `amount` DECIMAL(10,2) NOT NULL,
  `date` DATE NOT NULL,
  `status` VARCHAR(255) DEFAULT 'pending',
  `vendor` VARCHAR(255) NULL,
  `vendor_phone` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `maintenance_costs` ADD INDEX `maintenance_costs_status_index` (`status`);
ALTER TABLE `maintenance_costs` ADD INDEX `maintenance_costs_apartment_id_category_index` (`apartment_id`, `category`);

-- ----------------------------
-- Table: migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for migrations (20 rows)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '2024_01_01_000001_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2024_01_01_000002_create_apartments_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2024_01_01_000003_create_apartment_images_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2024_01_01_000004_create_bookings_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2024_01_01_000005_create_payments_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2024_01_01_000006_create_reviews_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2024_01_01_000007_create_favorites_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10, '2024_01_01_000008_create_blocked_dates_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11, '2026_02_10_080518_create_sessions_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12, '2026_02_12_000001_create_tenants_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13, '2026_02_12_000002_create_employees_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14, '2026_02_12_000003_create_employee_salary_records_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15, '2026_02_12_000004_create_employee_leave_requests_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16, '2026_02_12_000005_create_rent_payments_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17, '2026_02_12_000006_create_maintenance_costs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18, '2026_02_12_000007_create_bills_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19, '2026_02_12_000008_create_complaints_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20, '2026_02_12_000009_create_consent_forms_table', 1);

-- ----------------------------
-- Table: payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `booking_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `transaction_id` BIGINT UNSIGNED NOT NULL,
  `payment_method` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(255) DEFAULT 'USD',
  `status` VARCHAR(255) DEFAULT 'pending',
  `payment_details` TEXT NULL,
  `receipt_url` VARCHAR(255) NULL,
  `failure_reason` TEXT NULL,
  `paid_at` TIMESTAMP NULL,
  `refunded_at` TIMESTAMP NULL,
  `refund_amount` DECIMAL(10,2) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `payments` ADD UNIQUE INDEX `payments_transaction_id_unique` (`transaction_id`);
ALTER TABLE `payments` ADD INDEX `payments_user_id_index` (`user_id`);
ALTER TABLE `payments` ADD INDEX `payments_booking_id_status_index` (`booking_id`, `status`);
ALTER TABLE `payments` ADD INDEX `payments_transaction_id_index` (`transaction_id`);

-- ----------------------------
-- Table: rent_payments
-- ----------------------------
DROP TABLE IF EXISTS `rent_payments`;
CREATE TABLE `rent_payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `month` VARCHAR(255) NOT NULL,
  `payment_method` VARCHAR(255) DEFAULT 'cash',
  `payment_date` DATE NOT NULL,
  `receipt_number` VARCHAR(255) NULL,
  `status` VARCHAR(255) DEFAULT 'paid',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `rent_payments` ADD INDEX `rent_payments_status_index` (`status`);
ALTER TABLE `rent_payments` ADD UNIQUE INDEX `rent_payments_tenant_id_month_unique` (`tenant_id`, `month`);

-- ----------------------------
-- Table: reviews
-- ----------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `apartment_id` BIGINT UNSIGNED NOT NULL,
  `booking_id` BIGINT UNSIGNED NOT NULL,
  `rating` INT NOT NULL,
  `cleanliness_rating` INT NULL,
  `accuracy_rating` INT NULL,
  `communication_rating` INT NULL,
  `location_rating` INT NULL,
  `value_rating` INT NULL,
  `comment` TEXT NOT NULL,
  `admin_response` TEXT NULL,
  `responded_at` TIMESTAMP NULL,
  `is_approved` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `reviews` ADD INDEX `reviews_user_id_index` (`user_id`);
ALTER TABLE `reviews` ADD INDEX `reviews_apartment_id_is_approved_index` (`apartment_id`, `is_approved`);
ALTER TABLE `reviews` ADD UNIQUE INDEX `reviews_booking_id_unique` (`booking_id`);

-- ----------------------------
-- Table: sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `sessions` ADD INDEX `sessions_last_activity_index` (`last_activity`);
ALTER TABLE `sessions` ADD INDEX `sessions_user_id_index` (`user_id`);

-- ----------------------------
-- Table: tenants
-- ----------------------------
DROP TABLE IF EXISTS `tenants`;
CREATE TABLE `tenants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(255) NOT NULL,
  `id_number` VARCHAR(255) NULL,
  `emergency_contact_name` VARCHAR(255) NULL,
  `emergency_contact_phone` VARCHAR(255) NULL,
  `apartment_id` BIGINT UNSIGNED NULL,
  `lease_start` DATE NULL,
  `lease_end` DATE NULL,
  `rent_amount` DECIMAL(10,2) DEFAULT 0,
  `status` VARCHAR(255) DEFAULT 'active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `tenants` ADD UNIQUE INDEX `tenants_email_unique` (`email`);
ALTER TABLE `tenants` ADD INDEX `tenants_status_index` (`status`);
ALTER TABLE `tenants` ADD INDEX `tenants_apartment_id_index` (`apartment_id`);

-- ----------------------------
-- Table: users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NULL,
  `google_id` BIGINT UNSIGNED NULL,
  `avatar` VARCHAR(255) NULL,
  `phone` VARCHAR(255) NULL,
  `address` TEXT NULL,
  `city` VARCHAR(255) NULL,
  `state` VARCHAR(255) NULL,
  `country` VARCHAR(255) NULL,
  `postal_code` VARCHAR(255) NULL,
  `role` VARCHAR(255) DEFAULT 'user',
  `is_active` TINYINT(1) DEFAULT 1,
  `remember_token` TINYINT(1) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `users` ADD UNIQUE INDEX `users_google_id_unique` (`google_id`);
ALTER TABLE `users` ADD UNIQUE INDEX `users_email_unique` (`email`);

-- Data for users (7 rows)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 'Admin User', 'admin@example.com', '2026-02-14 09:39:54', '$2y$12$3R74ZvaYN6CRCS0/25mggurC5hKi4KGDiBeNfoM1g9zX4.Z/Ne4pq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 1, NULL, '2026-02-14 09:39:54', '2026-02-14 09:39:54', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (2, 'Demo User', 'user@example.com', '2026-02-14 09:39:55', '$2y$12$DsI20.2zhNx8svmLP637Huav3gxh6AhP.zK8UzuaaTyxlOeTip3Z2', NULL, NULL, +1234567890, '123 Main Street', 'New York', 'NY', 'USA', 10001, 'user', 1, NULL, '2026-02-14 09:39:55', '2026-02-14 09:39:55', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (3, 'Kondwani Sakala', 'sakalakhondwani1@gmail.com', NULL, '$2y$12$AB5I7JxN4aK51gRhh2F6Yu1pKjUSlABB4q.TrNkZXoyewo/ssm8H2', 116717422493738960531, 'https://lh3.googleusercontent.com/a/ACg8ocKF2T-dJ0Ci7PjbNr3YX1mTpUSYTvMKMfrjwheJyGPUKyDiajHu=s96-c', NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, '2026-02-17 06:51:19', '2026-02-17 06:51:19', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (4, 'Gogo', 'gogojt46@gmail.com', NULL, '$2y$12$1mbRoZpIooY3vvWAkx4ZJu0EQQiJezmi2jaPytq1b2HD05GgviOve', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, '2026-02-17 08:52:59', '2026-02-17 08:52:59', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (5, 'Ndwanny', 'kondwandwani@gmail.com', NULL, '$2y$12$9.36WRdrVjdZnkKrVYYJl.8FxQkDGuorbRZCBnAEzpYEv.qGRj626', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, '2026-02-17 09:03:02', '2026-02-17 09:03:02', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (6, 'G loans', 'admin@gracimorloans.com', NULL, '$2y$12$ALGwocdOBBJIkqiY9jINYOKGWOo7EUJ08/e5RN7wa.7C5v9tNg8G.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, '2026-02-17 09:09:24', '2026-02-17 09:09:24', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES (7, 'Alinaswe', 'alinasweofficial@gmail.com', NULL, '$2y$12$33jkM3PJ4.QImr8jaRpNi.i6fwHpiarR6IhSIglna/Wtn2PWGl6xu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', 1, NULL, '2026-02-17 10:25:06', '2026-02-17 10:25:06', NULL);

SET FOREIGN_KEY_CHECKS = 1;
