<div class="page-header">
    <h1 class="page-title">
        <?php echo hasRole('contractor') ? 'My Bills' : 'Bills'; ?>
    </h1>
    
    <?php if(hasRole('contractor')): ?>
    <div class="page-actions">
        <a href="index.php?page=work_orders" class="btn">
            <i class="fas fa-file-invoice-dollar"></i> Create New Bill
        </a>
        <p class="action-note">Select a work order to create a bill</p>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-filter">
            <input type="text" id="billSearch" placeholder="Search bills..." class="form-control">
        </div>
    </div>
    
    <div class="table-container">
        <table id="billsTable">
            <thead>
                <tr>
                    <th>Bill Number</th>
                    <th>Work Order</th>
                    <th>Project</th>
                    <th>Contractor</th>
                    <th>Submission Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch bills based on user role
                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['user_role'];
                
                if ($role == 'contractor') {
                    $bills = fetchAll("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                                     FROM bills b 
                                     JOIN work_orders wo ON b.work_order_id = wo.id 
                                     JOIN users c ON b.contractor_id = c.id 
                                     WHERE b.contractor_id = ? 
                                     ORDER BY b.submission_date DESC", [$user_id]);
                } elseif ($role == 'sdo') {
                    $bills = fetchAll("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                                     FROM bills b 
                                     JOIN work_orders wo ON b.work_order_id = wo.id 
                                     JOIN users c ON b.contractor_id = c.id 
                                     WHERE b.status IN (1, 2) 
                                     ORDER BY b.submission_date DESC");
                } elseif ($role == 'sdc') {
                    $bills = fetchAll("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                                     FROM bills b 
                                     JOIN work_orders wo ON b.work_order_id = wo.id 
                                     JOIN users c ON b.contractor_id = c.id 
                                     WHERE b.status IN (2, 3) 
                                     ORDER BY b.submission_date DESC");
                } else {
                    $bills = fetchAll("SELECT b.*, wo.order_number, wo.project_name, c.name as contractor_name 
                                     FROM bills b 
                                     JOIN work_orders wo ON b.work_order_id = wo.id 
                                     JOIN users c ON b.contractor_id = c.id 
                                     ORDER BY b.submission_date DESC");
                }
                
                if (count($bills) > 0) {
                    foreach ($bills as $bill) {
                        $badge_class = '';
                        switch ($bill['status']) {
                            case 1: // Submitted
                                $badge_class = 'badge-primary';
                                break;
                            case 9: // Rejected
                                $badge_class = 'badge-danger';
                                break;
                            case 8: // Payment Complete
                                $badge_class = 'badge-success';
                                break;
                            default:
                                $badge_class = 'badge-warning';
                        }
                        
                        echo '<tr>';
                        echo '<td>' . $bill['bill_number'] . '</td>';
                        echo '<td>' . $bill['order_number'] . '</td>';
                        echo '<td>' . htmlspecialchars($bill['project_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($bill['contractor_name']) . '</td>';
                        echo '<td>' . formatDate($bill['submission_date']) . '</td>';
                        echo '<td>' . formatCurrency($bill['amount']) . '</td>';
                        echo '<td><span class="badge ' . $badge_class . '">' . getBillStatusText($bill['status']) . '</span></td>';
                        echo '<td class="actions-cell">';
                        echo '<a href="index.php?page=bills&action=view&id=' . $bill['id'] . '" class="btn-icon" title="View Details"><i class="fas fa-eye"></i></a>';
                        
                        if (hasRole('sdo') && $bill['status'] == 1) {
                            echo '<a href="index.php?page=bills&action=review&id=' . $bill['id'] . '" class="btn-icon btn-success" title="Review Bill"><i class="fas fa-check-circle"></i></a>';
                        }
                        
                        if (hasRole('sdc') && $bill['status'] == 3) {
                            echo '<a href="index.php?page=bills&action=process_voucher&id=' . $bill['id'] . '" class="btn-icon btn-success" title="Process Voucher"><i class="fas fa-receipt"></i></a>';
                        }
                        
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="8" class="text-center">No bills found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
    searchTable('billSearch', 'billsTable');
});
</script>