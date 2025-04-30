<?php
require_once '../auth.php';
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contractor_name = $_POST['cont_name'];
    $cost = $_POST['cost'];
    $date_of_commencement = $_POST['date_of_commencement'];
    $time_limit = $_POST['time_limit'];
    $items = $_POST['items'];

    try {
        $pdo->beginTransaction();

        // Insert into work orders
        $stmt = $pdo->prepare("INSERT INTO public.tbl_work_orders (contractor_name, cost, date_of_commencement, time_limit) VALUES (?, ?, ?, ?)");
        $stmt->execute([$contractor_name, $cost, $date_of_commencement, $time_limit]);
        $work_order_id = $pdo->lastInsertId();

        // Insert items
        $stmt = $pdo->prepare("INSERT INTO public.tbl_work_order_items (work_order_id, item_name, description, quantity, unit, rate) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            $stmt->execute([
                $work_order_id,
                $item['name'],
                $item['description'] ?? '',
                $item['quantity'],
                $item['unit'],
                $item['rate']
            ]);
        }

        $pdo->commit();

        $_SESSION['success'] = "Work order created successfully.";
        header("Location: ../work_orders_issuance.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../create_work_orders.php");
        exit();
    }
}
?>
