-- Create database with a space in the name
CREATE DATABASE IF NOT EXISTS `rent house` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rent house`;

-- Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `mobile` VARCHAR(30) NOT NULL,
  `nid` VARCHAR(100) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `user_type` ENUM('renter','owner','admin') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Houses
CREATE TABLE IF NOT EXISTS `houses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `owner_id` INT NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `location` VARCHAR(200) NOT NULL,
  `category` VARCHAR(20) NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `image_path` VARCHAR(255) NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_houses_owner` FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- Messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sender_id` INT NOT NULL,
  `receiver_id` INT NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- Wishlist
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `house_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uniq_user_house` (`user_id`, `house_id`),
  CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_house` FOREIGN KEY (`house_id`) REFERENCES `houses`(`id`) ON DELETE CASCADE
);

-- Rent Requests
CREATE TABLE IF NOT EXISTS `rent_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `renter_id` INT NOT NULL,
  `house_id` INT NOT NULL,
  `request_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  CONSTRAINT `fk_requests_renter` FOREIGN KEY (`renter_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_requests_house` FOREIGN KEY (`house_id`) REFERENCES `houses`(`id`) ON DELETE CASCADE
);

-- Password Resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(190) NOT NULL,
  `token` VARCHAR(100) NOT NULL,
  `expiry` DATETIME NOT NULL,
  INDEX (`email`),
  INDEX (`token`)
);
