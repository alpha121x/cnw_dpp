<?php
require_once '../auth.php';
require_once 'db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category = $_POST['category'] ?? '';

  if (empty($category)) {
    echo json_encode(['success' => false, 'error' => 'Category is required']);
    exit();
  }

  try {
    $stmt = $pdo->prepare("SELECT id, item_no, category, description, unit, rate_numeric FROM public.tbl_workorder_items WHERE category = ?");
    $stmt->execute([$category]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'items' => $items]);
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => "Error fetching items: " . $e->getMessage()]);
  }
}
?>