<?php
// config/database.php

// Include constants if they are not already defined
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/constants.php';
}

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // For a production site, you should log this error and show a generic message.
    error_log("Database Connection Error: " . $e->getMessage());
    die("Could not connect to the database. Please try again later.");
}