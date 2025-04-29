<?php
session_start();
require_once '../auth.php';
require_once 'db_config.php'; // Database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cost = $_POST['cost'];
    $date_of_commencement = $_POST['date_of_commencement'];
    $time_limit = $_POST['time_limit'];
    $items = $_POST['items'];

    try {
        // Insert work order into database
        $stmt = $pdo->prepare("INSERT INTO public.tbl_work_orders (cost, date_of_commencement, time_limit) VALUES (?, ?, ?)");
        $stmt->execute([$cost, $date_of_commencement, $time_limit]);
        $work_order_id = $pdo->lastInsertId();

        // Insert items
        $stmt = $pdo->prepare("INSERT INTO public.tbl_work_order_items (work_order_id, item_name, quantity, rate) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute([$work_order_id, $item['name'], $item['quantity'], $item['rate']]);
        }

        $_SESSION['success'] = "Work order created successfully.";
        header("Location: ../work_orders_issuance.php");
        exit();        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error creating work order: " . $e->getMessage();
        header("Location: ../create_work_orders.php");
        exit();
    }
}
?>