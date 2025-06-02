CREATE DATABASE IF NOT EXISTS `naboo`;

USE naboo;

START TRANSACTION;

CREATE TABLE IF NOT EXISTS `users` (
    `id_user` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `role` ENUM('admin', 'user') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `forms` (
    `id_form` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `data` JSON NOT NULL,
    `anonymous` BOOLEAN DEFAULT FALSE,
    `needs_authentication` BOOLEAN DEFAULT FALSE,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `forms_ownership` (
    `user_id` INT NOT NULL,
    `form_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`form_id`) REFERENCES `forms`(`id_form`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE,
    PRIMARY KEY (`user_id`, `form_id`)
);

CREATE TABLE IF NOT EXISTS `forms_submissions` (
    `id_submission` INT AUTO_INCREMENT PRIMARY KEY,
    `form_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `data` JSON NOT NULL,
    `authentication` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`form_id`) REFERENCES `forms`(`id_form`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `shared_questions`(
    `id_question` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `data` JSON NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `sessions` (
    `session_uuid` VARCHAR(255) PRIMARY KEY,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NOT NULL,
    `notes` VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
);

COMMIT;