<?php
// Get the action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Handle different actions
switch($action) {
    case 'create':
        // Only contractors can create bills
        if (!hasRole('contractor')) {
            $_SESSION['error_message'] = 'You do not have permission to create bills';
            header('Location: index.php?page=bills');
            exit;
        }
        
        // Check if work order ID is provided
        if (!isset($_GET['work_order_id']) || empty($_GET['work_order_id'])) {
            $_SESSION['error_message'] = 'Work order ID is required to create a bill';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        $work_order_id = $_GET['work_order_id'];
        
        // Fetch work order details
        $work_order = fetchOne("SELECT * FROM work_orders WHERE id = ? AND contractor_id = ? AND status >= 2", 
                             [$work_order_id, $_SESSION['user_id']]);
        
        if (!$work_order) {
            $_SESSION['error_message'] = 'Invalid work order or you do not have permission';
            header('Location: index.php?page=work_orders');
            exit;
        }
        
        include 'bills/create.php';
        break;
        
    case 'view':
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid bill ID';
            header('Location: index.php?page=bills');
            exit;
        }
        
        $bill_id = $_GET['id'];
        
        // Fetch bill details
        $bill = fetchOne("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                        FROM bills b 
                        JOIN work_orders wo ON b.work_order_id = wo.id 
                        JOIN users c ON b.contractor_id = c.id 
                        WHERE b.id = ?", [$bill_id]);
        
        if (!$bill) {
            $_SESSION['error_message'] = 'Bill not found';
            header('Location: index.php?page=bills');
            exit;
        }
        
        // If user is contractor, check if they have access to this bill
        if (hasRole('contractor') && $bill['contractor_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'You do not have permission to view this bill';
            header('Location: index.php?page=bills');
            exit;
        }
        
        include 'bills/view.php';
        break;
        
    case 'review':
        // Only SDO can review bills
        if (!hasRole('sdo')) {
            $_SESSION['error_message'] = 'You do not have permission to review bills';
            header('Location: index.php?page=bills');
            exit;
        }
        
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid bill ID';
            header('Location: index.php?page=bills');
            exit;
        }
        
        $bill_id = $_GET['id'];
        
        // Fetch bill details
        $bill = fetchOne("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                        FROM bills b 
                        JOIN work_orders wo ON b.work_order_id = wo.id 
                        JOIN users c ON b.contractor_id = c.id 
                        WHERE b.id = ? AND b.status = 1", [$bill_id]);
        
        if (!$bill) {
            $_SESSION['error_message'] = 'Bill not found or already reviewed';
            header('Location: index.php?page=bills');
            exit;
        }
        
        include 'bills/review.php';
        break;
        
    case 'process_voucher':
        // Only SDC can process vouchers
        if (!hasRole('sdc')) {
            $_SESSION['error_message'] = 'You do not have permission to process vouchers';
            header('Location: index.php?page=bills');
            exit;
        }
        
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid bill ID';
            header('Location: index.php?page=bills');
            exit;
        }
        
        $bill_id = $_GET['id'];
        
        // Fetch bill details
        $bill = fetchOne("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                        FROM bills b 
                        JOIN work_orders wo ON b.work_order_id = wo.id 
                        JOIN users c ON b.contractor_id = c.id 
                        WHERE b.id = ? AND b.status = 3", [$bill_id]);
        
        if (!$bill) {
            $_SESSION['error_message'] = 'Bill not found or not ready for voucher processing';
            header('Location: index.php?page=bills');
            exit;
        }
        
        include 'bills/process_voucher.php';
        break;
    
    default:
        // List view - default
        include 'bills/list.php';
        break;
}
?>