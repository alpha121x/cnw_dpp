<?php
require_once 'db_config.php';
require_once '../vendor/autoload.php'; // Include Composer autoloader for DOMPDF

use Dompdf\Dompdf;

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="work_order_' . $_POST['work_order_id'] . '.pdf"');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['work_order_id'])) {
    $work_order_id = $_POST['work_order_id'];

    try {
        // Fetch work order details
        $stmt = $pdo->prepare("SELECT * FROM public.tbl_workorders WHERE id = ?");
        $stmt->execute([$work_order_id]);
        $work_order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$work_order) {
            throw new PDOException("Work order not found.");
        }

        // Fetch related items
        $itemStmt = $pdo->prepare("SELECT i.item_no, i.description, wq.quantity FROM public.tbl_workorder_qty wq JOIN public.tbl_workorder_items i ON wq.item_id = i.item_no WHERE wq.workorder_id = ?");
        $itemStmt->execute([$work_order_id]);
        $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate HTML content
        $html = '<h1>Work Order #' . htmlspecialchars($work_order_id) . '</h1>';
        $html .= '<p><strong>Contractor:</strong> ' . htmlspecialchars($work_order['contractor_name']) . '</p>';
        $html .= '<p><strong>Date of Commencement:</strong> ' . htmlspecialchars($work_order['date_of_commencement']) . '</p>';
        $html .= '<p><strong>Cost:</strong> ' . htmlspecialchars($work_order['cost']) . '</p>';
        $html .= '<h2>Items</h2><table border="1" style="border-collapse: collapse; width: 100%;"><tr><th>Item No.</th><th>Description</th><th>Quantity</th></tr>';
        foreach ($items as $item) {
            $html .= '<tr><td>' . htmlspecialchars($item['item_no']) . '</td><td>' . htmlspecialchars($item['description']) . '</td><td>' . htmlspecialchars($item['quantity']) . '</td></tr>';
        }
        $html .= '</table>';

        // Initialize DOMPDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the generated PDF
        $dompdf->stream('work_order_' . $work_order_id . '.pdf', ['Attachment' => true]);
        exit();

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}
?>