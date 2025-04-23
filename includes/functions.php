<?php
// General utility functions for the application

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to generate work order number
function generateWorkOrderNumber() {
    $prefix = 'WO';
    $year = date('Y');
    $month = date('m');
    
    $last_order = fetchOne("SELECT MAX(id) as max_id FROM work_orders");
    $next_id = $last_order ? $last_order['max_id'] + 1 : 1;
    
    return $prefix . '-' . $year . $month . '-' . sprintf('%04d', $next_id);
}

// Function to generate bill number
function generateBillNumber() {
    $prefix = 'BILL';
    $year = date('Y');
    $month = date('m');
    
    $last_bill = fetchOne("SELECT MAX(id) as max_id FROM bills");
    $next_id = $last_bill ? $last_bill['max_id'] + 1 : 1;
    
    return $prefix . '-' . $year . $month . '-' . sprintf('%04d', $next_id);
}

// Function to generate voucher number
function generateVoucherNumber() {
    $prefix = 'V';
    $year = date('Y');
    $month = date('m');
    
    $last_voucher = fetchOne("SELECT MAX(id) as max_id FROM vouchers");
    $next_id = $last_voucher ? $last_voucher['max_id'] + 1 : 1;
    
    return $prefix . '-' . $year . $month . '-' . sprintf('%04d', $next_id);
}

// Function to generate cheque number
function generateChequeNumber() {
    $prefix = 'CHQ';
    $year = date('Y');
    $month = date('m');
    
    $last_cheque = fetchOne("SELECT MAX(id) as max_id FROM cheques");
    $next_id = $last_cheque ? $last_cheque['max_id'] + 1 : 1;
    
    return $prefix . '-' . $year . $month . '-' . sprintf('%04d', $next_id);
}

// Function to format currency
function formatCurrency($amount) {
    return number_format($amount, 2);
}

// Function to format date
function formatDate($date) {
    return date('d M Y', strtotime($date));
}

// Function to get work order status text
function getWorkOrderStatusText($status) {
    $statusMap = [
        1 => 'Issued',
        2 => 'Commenced',
        3 => 'In Progress',
        4 => 'Completed',
        5 => 'Closed'
    ];
    
    return isset($statusMap[$status]) ? $statusMap[$status] : 'Unknown';
}

// Function to get bill status text
function getBillStatusText($status) {
    $statusMap = [
        1 => 'Submitted',
        2 => 'Under SDO Review',
        3 => 'SDC Processing',
        4 => 'XEN Review',
        5 => 'DAO Verification',
        6 => 'Approved',
        7 => 'Cheque Issued',
        8 => 'Payment Complete',
        9 => 'Rejected'
    ];
    
    return isset($statusMap[$status]) ? $statusMap[$status] : 'Unknown';
}

// Get user role text
function getRoleText($role) {
    $roleMap = [
        'admin' => 'Administrator',
        'sdo' => 'Sub Divisional Officer',
        'contractor' => 'Contractor',
        'subeng' => 'Sub Engineer',
        'sdc' => 'Sub Divisional Clerk',
        'xen' => 'Executive Engineer',
        'dao' => 'Divisional Accounts Officer',
        'accounts' => 'Accounts Branch',
        'treasury' => 'Treasury Officer'
    ];
    
    return isset($roleMap[$role]) ? $roleMap[$role] : 'Unknown';
}

// Get pending tasks count for user
function getPendingTasksCount() {
    if (!isLoggedIn()) return 0;
    
    $role = $_SESSION['user_role'];
    $user_id = $_SESSION['user_id'];
    
    switch($role) {
        case 'admin':
        case 'sdo':
            // Count work orders to approve and bills to review
            $sql = "SELECT 
                     (SELECT COUNT(*) FROM bills WHERE status = 2) +
                     (SELECT COUNT(*) FROM work_orders WHERE status = 1) as count";
            break;
        case 'contractor':
            // Count work orders assigned
            $sql = "SELECT COUNT(*) as count FROM work_orders WHERE contractor_id = ? AND status IN (1, 2, 3)";
            return fetchOne($sql, [$user_id])['count'];
        case 'subeng':
            // Count measurements to be done
            $sql = "SELECT COUNT(*) as count FROM work_orders WHERE status = 2 AND needs_measurement = 1";
            break;
        case 'sdc':
            // Count bills to process
            $sql = "SELECT COUNT(*) as count FROM bills WHERE status = 3";
            break;
        case 'xen':
            // Count vouchers to approve
            $sql = "SELECT COUNT(*) as count FROM vouchers WHERE status = 1";
            break;
        case 'dao':
            // Count vouchers to verify
            $sql = "SELECT COUNT(*) as count FROM vouchers WHERE status = 2";
            break;
        case 'accounts':
            // Count form2 to create
            $sql = "SELECT COUNT(*) as count FROM cheques WHERE form2_status = 0";
            break;
        case 'treasury':
            // Count cheques to process
            $sql = "SELECT COUNT(*) as count FROM cheques WHERE treasury_status = 0";
            break;
        default:
            return 0;
    }
    
    return fetchOne($sql)['count'];
}
?>