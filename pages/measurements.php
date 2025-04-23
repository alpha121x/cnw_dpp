<?php
// Get the action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Handle different actions
switch($action) {
    case 'record':
        // Only sub engineer can record measurements
        if (!hasRole('subeng')) {
            $_SESSION['error_message'] = 'You do not have permission to record measurements';
            header('Location: index.php?page=measurements');
            exit;
        }
        
        // Check if work order ID is provided
        if (!isset($_GET['work_order_id']) || empty($_GET['work_order_id'])) {
            $_SESSION['error_message'] = 'Work order ID is required to record measurements';
            header('Location: index.php?page=measurements');
            exit;
        }
        
        $work_order_id = $_GET['work_order_id'];
        
        // Fetch work order details
        $work_order = fetchOne("SELECT wo.*, c.name as contractor_name 
                               FROM work_orders wo 
                               JOIN users c ON wo.contractor_id = c.id 
                               WHERE wo.id = ? AND wo.status = 2", [$work_order_id]);
        
        if (!$work_order) {
            $_SESSION['error_message'] = 'Invalid work order or not ready for measurement';
            header('Location: index.php?page=measurements');
            exit;
        }
        
        include 'measurements/record.php';
        break;
        
    case 'view':
        // Check if ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            $_SESSION['error_message'] = 'Invalid measurement ID';
            header('Location: index.php?page=measurements');
            exit;
        }
        
        $measurement_id = $_GET['id'];
        
        // Fetch measurement details
        $measurement = fetchOne("SELECT m.*, wo.order_number, wo.project_name, c.name as contractor_name 
                               FROM measurements m 
                               JOIN work_orders wo ON m.work_order_id = wo.id 
                               JOIN users c ON wo.contractor_id = c.id 
                               WHERE m.id = ?", [$measurement_id]);
        
        if (!$measurement) {
            $_SESSION['error_message'] = 'Measurement not found';
            header('Location: index.php?page=measurements');
            exit;
        }
        
        include 'measurements/view.php';
        break;
    
    default:
        // List view - default
        include 'measurements/list.php';
        break;
}
?>