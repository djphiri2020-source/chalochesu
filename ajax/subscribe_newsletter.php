<?php
// ajax/subscribe_newsletter.php

// Set the content type to JSON for the response
header('Content-Type: application/json');

// Include database configuration
require_once '../config/database.php';

$response = ['success' => false, 'message' => 'An unexpected error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $response['message'] = 'Please provide a valid email address.';
    } else {
        try {
            // Check if the email already exists to prevent errors and provide a friendly message
            $stmt = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $response['success'] = true; // Treat as success since the user's goal is met
                $response['message'] = 'You are already subscribed. Thank you!';
            } else {
                // Insert the new subscriber
                $insert_stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
                $insert_stmt->execute([$email]);
                $response['success'] = true;
                $response['message'] = 'Thank you for subscribing!';
            }
        } catch (PDOException $e) {
            error_log("Newsletter subscription error: " . $e->getMessage());
            $response['message'] = 'A database error occurred. Please try again later.';
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);