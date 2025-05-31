<?php

require_once 'connect.php';

try {
  // CREATE DATABASE
  $conn->query("CREATE DATABASE IF NOT EXISTS `naboo`;");
  $conn->select_db('naboo');

  // START TRANSACTION
  $conn->begin_transaction();

  // USERS TABLE
  $conn->query("CREATE TABLE IF NOT EXISTS `users` (
        `id_user` INT AUTO_INCREMENT PRIMARY KEY,
        `first_name` VARCHAR(50) NOT NULL,
        `last_name` VARCHAR(50) NOT NULL,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `role` ENUM('admin', 'user') DEFAULT 'user',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );");

  // FORMS TABLE
  $conn->query("CREATE TABLE IF NOT EXISTS `forms` (
        `id_form` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `code` VARCHAR(255) NOT NULL,
        `data` JSON NOT NULL,
        `anonymous` BOOLEAN DEFAULT FALSE,
        `needs_authentication` BOOLEAN DEFAULT FALSE,
        `is_active` BOOLEAN DEFAULT TRUE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
    );");

  // FORMS_OWNERSHIP TABLE
  $conn->query("CREATE TABLE IF NOT EXISTS `forms_ownership` (
        `user_id` INT NOT NULL,
        `form_id` INT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`form_id`) REFERENCES `forms`(`id_form`) ON DELETE CASCADE,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE,
        PRIMARY KEY (`user_id`, `form_id`)
    );");

  // FORMS_SUBMISSIONS TABLE
  $conn->query("CREATE TABLE IF NOT EXISTS `forms_submissions` (
        `id_submission` INT AUTO_INCREMENT PRIMARY KEY,
        `form_id` INT NOT NULL,
        `user_id` INT NOT NULL,
        `data` JSON NOT NULL,
        `authentication` VARCHAR(100) DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`form_id`) REFERENCES `forms`(`id_form`) ON DELETE CASCADE,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
    );");

  // SHARED_QUESTIONS TABLE
  $conn->query("CREATE TABLE IF NOT EXISTS `shared_questions`(
        `id_question` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `data` JSON NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
    );");

  // SESSIONS TABLE
  $conn->query("CREATE TABLE IF NOT EXISTS `sessions` (
        `session_uuid` VARCHAR(255) PRIMARY KEY,
        `user_id` INT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `expires_at` TIMESTAMP NOT NULL,
        `is_active` BOOLEAN DEFAULT TRUE,
        `notes` VARCHAR(255) DEFAULT NULL,
        FOREIGN KEY (`user_id`) REFERENCES `users`(`id_user`) ON DELETE CASCADE
    );");

  // COMMIT
  $conn->commit();
  echo json_encode(['success' => true, 'message' => 'Database e tabelle creati con successo']);
} catch (mysqli_sql_exception $e) {
  $conn->rollback();
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Errore nella creazione del database: ' . $e->getMessage()]);
}
