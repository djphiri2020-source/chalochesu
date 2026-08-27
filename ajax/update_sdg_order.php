<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

require_once '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['order']) || !is_array($input['order'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE sdgs SET display_order = ? WHERE id = ?");

    foreach ($input['order'] as $index => $id) {
        $stmt->execute([$index, $id]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order updated successfully.']);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("SDG order update failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}