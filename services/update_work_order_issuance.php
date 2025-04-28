<?php
session_start();
require_once 'db_config.php'; // From artifact 026eed84-6bfd-4910-9bac-46c7d61d830f

// Ensure the request is AJAX and user is authenticated
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest' || !isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit();
}

// Check if required data is provided
if (!isset($_POST['work_order_id']) || !isset($_POST['contractor_id'])) {
    echo json_encode(['success' => false, 'error' => 'Work order ID and contractor ID are required']);
    exit();
}

$work_order_id = trim($_POST['work_order_id']);
$contractor_id = trim($_POST['contractor_id']);

try {
    // Check if work order exists
    $stmt = $pdo->prepare("SELECT id FROM public.tbl_work_orders WHERE id = ?");
    $stmt->execute([$work_order_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Invalid work order ID']);
        exit();
    }

    // Check if contractor exists
    $stmt = $pdo->prepare("SELECT id FROM public.tbl_contractors WHERE id = ?");
    $stmt->execute([$contractor_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Invalid contractor ID']);
        exit();
    }

    // Update contractor_id in tbl_work_orders
    $stmt = $pdo->prepare("UPDATE public.tbl_work_orders SET contractor_id = ? WHERE id = ?");
    $stmt->execute([$contractor_id, $work_order_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>