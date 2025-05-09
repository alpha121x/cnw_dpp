<?php
require_once '../auth.php';
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contractor_name = $_POST['cont_name'];
    $cost = $_POST['cost'];
    $date_of_commencement = $_POST['date_of_commencement'];
    $time_limit = $_POST['time_limit'];
    $ref_no = $_POST['ref_no'] ?? null;
    $ref_date = $_POST['ref_date'] ?? null;
    $se_ref_no = $_POST['se_ref_no'] ?? null;
    $se_ref_date = $_POST['se_ref_date'] ?? null;
    $amount_numeric = $_POST['amount_numeric'];
    $amount_words = $_POST['amount_words'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $items = $_POST['items'];

    $log_message = "[" . date('Y-m-d H:i:s') . "] " . json_encode($items) . "\n";

    try {
        $pdo->beginTransaction();

        // Insert into work orders
        $stmt = $pdo->prepare("INSERT INTO public.tbl_workorders (contractor_name, cost, date_of_commencement, time_limit_months, ref_no, ref_date, se_ref_no, se_ref_date, amount_numeric, amount_words, subject) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$contractor_name, $cost, $date_of_commencement, $time_limit, $ref_no, $ref_date, $se_ref_no, $se_ref_date, $amount_numeric, $amount_words, $subject]);
        $work_order_id = $pdo->lastInsertId();

        // Insert items
        $stmt = $pdo->prepare("INSERT INTO public.tbl_workorder_qty (workorder_id, item_id, quantity) VALUES (?, ?, ?)");

        foreach ($items as $item) {
            // Fetch item_id from tbl_workorder_items using the item id
            $itemStmt = $pdo->prepare("SELECT id FROM public.tbl_workorder_items WHERE id = ?");
            $itemStmt->execute([$item['id']]);
            $item_no = $itemStmt->fetchColumn();

            if ($item_no === false) {
                throw new PDOException("Item with ID {$item['id']} not found.");
            }

            // Insert item_no instead of id
            $stmt->execute([
                $work_order_id,
                $item_no, // Store item_no instead of id
                $item['quantity'],
            ]);
        }

        $pdo->commit();

        $_SESSION['success'] = "Work order created successfully.";
        header("Location: ../work_orders_issuance.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();

        // Log the error to error.log
        $log_message = "[" . date('Y-m-d H:i:s') . "] Error creating work order for contractor: " . htmlspecialchars($contractor_name) . ". Work Order ID: " . (isset($work_order_id) ? $work_order_id : 'N/A') . ". Error: " . $e->getMessage() . "\n";
        file_put_contents('error.log', $log_message, FILE_APPEND | LOCK_EX);

        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../create_work_orders.php");
        exit();
    }
}
?>