<?php
session_start();
require_once 'services/db_config.php';

// Check if user is authenticated
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if request is POST and has required parameters
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['work_order_id']) || !isset($_POST['contractor_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$work_order_id = intval($_POST['work_order_id']);
$contractor_id = intval($_POST['contractor_id']);

try {
    // Check if work order already has a contractor
    $stmt = $pdo->prepare("SELECT contractor_id FROM public.tbl_work_orders WHERE id = ?");
    $stmt->execute([$work_order_id]);
    $work_order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($work_order['contractor_id']) {
        echo json_encode(['success' => false, 'message' => 'Work order already assigned to a contractor']);
        exit;
    }

    // Assign contractor
    $stmt = $pdo->prepare("UPDATE public.tbl_work_orders SET contractor_id = ? WHERE id = ?");
    $stmt->execute([$contractor_id, $work_order_id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>