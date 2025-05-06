<?php
require_once 'db_config.php';
require_once'../vendor/autoload.php'; // Include Composer autoloader for DOMPDF

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

        // Generate HTML content based on the reference document
        $html = '<!DOCTYPE html>';
        $html .= '<html><head><style>';
        $html .= 'body { font-family: Arial, sans-serif; margin: 20mm; }';
        $html .= 'h1 { text-align: center; }';
        $html .= 'p { margin: 5mm 0; }';
        $html .= 'table { width: 100%; border-collapse: collapse; }';
        $html .= 'th, td { border: 1px solid black; padding: 5mm; text-align: left; }';
        $html .= 'th { background-color: #f2f2f2; }';
        $html .= '</style></head><body>';
        $html .= '<h1>WORK ORDER</h1>';
        $html .= '<p>To</p>';
        $html .= '<p>OFFICE OF THE EXECUTIVE ENGINEER,<br>Road Construction Division<br>Near Road Research New Campus<br>Canal Road Lahore.<br>Ph. # 99231727<br>Email: predivnlahore@gmail.com</p>';
        $html .= '<p>No. EE(RC)/' . htmlspecialchars($work_order_id) . '/CB/ST:<br>Dated Lahore the ' . htmlspecialchars(date('d-m-Y', strtotime($work_order['date_of_commencement']))) . '</p>';
        $html .= '<p>M/S: ' . htmlspecialchars($work_order['contractor_name']) . ',<br>Government Contractor,<br>88-E Architect Society<br>Khayaban-E-Jinnah Road Lahore.</p>';
        $html .= '<p><strong>Subject:</strong> ' . htmlspecialchars($work_order['subject'] ?? 'CONSTRUCTION OF ROAD THEATER VILLAGE IN DISTRICT LAHORE') . '</p>';
        $html .= '<p><strong>Reference:</strong> Your tender dated 15.12.2023.<br>Your tender referred above for the work cited as subject has been approved by the Superintending Engineer, Highway Circle Lahore vide No.83/G dated 11.01.2024 for Rs.' . htmlspecialchars(number_format($work_order['amount_numeric'], 2)) . '/- (' . htmlspecialchars($work_order['amount_words'] ?? 'Rupees Two Crore, Eighteen Lac, Nine Thousand Six Hundred & Fifty Five only') . ') being the lowest at the rates noted against each item. The time limit of this work is ' . htmlspecialchars($work_order['time_limit_months']) . '-months which will be reckoned from the issue of this acceptance letter as per General and Additional conditions subject to strict financial regularities and observance of all codal/legal formalities and contractual obligations.</p>';
        $html .= '<table>';
        $html .= '<tr><th>Sr. No</th><th>Item No.</th><th>Description</th><th>Qty. In Unit</th><th>Unit</th><th>Rate</th></tr>';
        foreach ($items as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['item_no']) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['description']) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['quantity']) . '</td>';
            // Fetch rate from tbl_workorder_items (assuming rate_numeric is stored)
            $rateStmt = $pdo->prepare("SELECT rate_numeric, rate_words FROM public.tbl_workorder_items WHERE item_no = ?");
            $rateStmt->execute([$item['item_no']]);
            $rate = $rateStmt->fetch(PDO::FETCH_ASSOC);
            $html .= '<td>' . htmlspecialchars($rate['rate_words'] ?? '') . '</td>';
            $html .= '<td>' . htmlspecialchars(number_format($rate['rate_numeric'] ?? 0, 2)) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '</body></html>';

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