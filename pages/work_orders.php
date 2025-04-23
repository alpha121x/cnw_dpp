<?php
// Get the action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Handle different actions
switch($action) {
    case 'create':
        // Only admin and SDO can create work orders
        if (!hasAnyRole(['admin', 'sdo'])) {
            $_SESSION['error_message'] = 'You do not have permission to create work orders';
            header('Location: index.php?page=work_orders');
            exit;
        }
        include 'work_orders/create.php';
        break;
        
    case 'view':
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid work order ID';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        $work_order_id = $_GET['id'];
        
        // Fetch work order details
        $work_order = fetchOne("SELECT wo.*, c.name as contractor_name, c.email as contractor_email 
                               FROM work_orders wo 
                               LEFT JOIN users c ON wo.contractor_id = c.id 
                               WHERE wo.id = ?", [$work_order_id]);
        
        if (!$work_order) {
            $_SESSION['error_message'] = 'Work order not found';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        // If user is contractor, check if they have access to this work order
        if (hasRole('contractor') && $work_order['contractor_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'You do not have permission to view this work order';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        include 'work_orders/view.php';
        break;
        
    case 'edit':
        // Only admin and SDO can edit work orders
        if (!hasAnyRole(['admin', 'sdo'])) {
            $_SESSION['error_message'] = 'You do not have permission to edit work orders';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid work order ID';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        $work_order_id = $_GET['id'];
        
        // Fetch work order details
        $work_order = fetchOne("SELECT * FROM work_orders WHERE id = ?", [$work_order_id]);
        
        if (!$work_order) {
            $_SESSION['error_message'] = 'Work order not found';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        include 'work_orders/edit.php';
        break;
        
    case 'commence':
        // Only contractor can commence work
        if (!hasRole('contractor')) {
            $_SESSION['error_message'] = 'You do not have permission to commence work';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid work order ID';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        $work_order_id = $_GET['id'];
        
        // Fetch work order details
        $work_order = fetchOne("SELECT * FROM work_orders WHERE id = ? AND contractor_id = ?", 
                             [$work_order_id, $_SESSION['user_id']]);
        
        if (!$work_order) {
            $_SESSION['error_message'] = 'Work order not found or you do not have permission';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        include 'work_orders/commence.php';
        break;
        
    case 'delete':
        // Only admin can delete work orders
        if (!hasRole('admin')) {
            $_SESSION['error_message'] = 'You do not have permission to delete work orders';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid work order ID';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        $work_order_id = $_GET['id'];
        
        // Delete work order - In a real application, you might want to soft delete
        updateData("DELETE FROM work_orders WHERE id = ?", [$work_order_id]);
        
        $_SESSION['success_message'] = 'Work order deleted successfully';
        header('Location: index.php?page=work_orders');
        exit;
        break;
        
    default:
        // List view - default
        include 'work_orders/list.php';
        break;
}
?>