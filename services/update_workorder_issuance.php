<?php
require_once '../auth.php';
require_once 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $work_order_id = $_POST['work_order_id'];
  $is_issued = $_POST['is_issued'] === 'true' ? 1 : 0;

  try {
    $stmt = $pdo->prepare("UPDATE public.tbl_workorders SET is_issued = ? WHERE id = ?");
    $stmt->execute([$is_issued, $work_order_id]);
    echo json_encode(['success' => true]);
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => "Error updating issuance status: " . $e->getMessage()]);
  }
}
?>