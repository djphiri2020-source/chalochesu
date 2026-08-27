<?php
session_start();
header('Content-Type: application/json');

// Security check: ensure user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$orderedIds = $input['order'] ?? null;
$table = $input['table'] ?? null;

// Basic validation
if (!$orderedIds || !is_array($orderedIds) || !$table) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

// Whitelist allowed tables to prevent misuse
$allowed_tables = ['core_values', 'timeline_events', 'stakeholders', 'partners', 'clients'];
if (!in_array($table, $allowed_tables)) {
    echo json_encode(['success' => false, 'message' => 'Invalid table specified.']);
    exit;
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE `$table` SET display_order = ? WHERE id = ?");
    foreach ($orderedIds as $index => $id) {
        $stmt->execute([$index, $id]);
    }
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order saved successfully.']);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Reordering Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while saving the new order.']);
}