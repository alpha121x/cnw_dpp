<div class="page-header">
    <h1 class="page-title">
        <?php echo hasRole('contractor') ? 'My Projects' : 'Work Orders'; ?>
    </h1>
    
    <?php if(hasAnyRole(['admin', 'sdo'])): ?>
    <div class="page-actions">
        <a href="index.php?page=work_orders&action=create" class="btn">
            <i class="fas fa-plus"></i> New Work Order
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-filter">
            <input type="text" id="workOrderSearch" placeholder="Search work orders..." class="form-control">
        </div>
    </div>
    
    <div class="table-container">
        <table id="workOrdersTable">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Project Name</th>
                    <th>Contractor</th>
                    <th>Issue Date</th>
                    <th>Value</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch work orders based on user role
                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['user_role'];
                
                if ($role == 'contractor') {
                    $work_orders = fetchAll("SELECT wo.*, u.name as contractor_name 
                                          FROM work_orders wo 
                                          JOIN users u ON wo.contractor_id = u.id 
                                          WHERE wo.contractor_id = ? 
                                          ORDER BY wo.issue_date DESC", [$user_id]);
                } else {
                    $work_orders = fetchAll("SELECT wo.*, u.name as contractor_name 
                                          FROM work_orders wo 
                                          JOIN users u ON wo.contractor_id = u.id 
                                          ORDER BY wo.issue_date DESC");
                }
                
                if (count($work_orders) > 0) {
                    foreach ($work_orders as $order) {
                        $badge_class = '';
                        switch ($order['status']) {
                            case 1: // Issued
                                $badge_class = 'badge-primary';
                                break;
                            case 2: // Commenced
                                $badge_class = 'badge-info';
                                break;
                            case 3: // In Progress
                                $badge_class = 'badge-warning';
                                break;
                            case 4: // Completed
                                $badge_class = 'badge-success';
                                break;
                            case 5: // Closed
                                $badge_class = 'badge-secondary';
                                break;
                        }
                        
                        echo '<tr>';
                        echo '<td>' . $order['order_number'] . '</td>';
                        echo '<td>' . htmlspecialchars($order['project_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($order['contractor_name']) . '</td>';
                        echo '<td>' . formatDate($order['issue_date']) . '</td>';
                        echo '<td>' . formatCurrency($order['contract_value']) . '</td>';
                        echo '<td><span class="badge ' . $badge_class . '">' . getWorkOrderStatusText($order['status']) . '</span></td>';
                        echo '<td class="actions-cell">';
                        echo '<a href="index.php?page=work_orders&action=view&id=' . $order['id'] . '" class="btn-icon" title="View Details"><i class="fas fa-eye"></i></a>';
                        
                        if (hasAnyRole(['admin', 'sdo'])) {
                            echo '<a href="index.php?page=work_orders&action=edit&id=' . $order['id'] . '" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>';
                        }
                        
                        if ($role == 'contractor' && $order['status'] == 1) {
                            echo '<a href="index.php?page=work_orders&action=commence&id=' . $order['id'] . '" class="btn-icon btn-success" title="Commence Work"><i class="fas fa-play"></i></a>';
                        }
                        
                        if (hasRole('admin') && $order['status'] == 5) {
                            echo '<a href="javascript:void(0)" onclick="confirmDelete(' . $order['id'] . ')" class="btn-icon btn-danger" title="Delete"><i class="fas fa-trash"></i></a>';
                        }
                        
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="text-center">No work orders found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
    searchTable('workOrderSearch', 'workOrdersTable');
});

// Confirm delete function
function confirmDelete(id) {
    confirmAction('Are you sure you want to delete this work order? This action cannot be undone.', function() {
        window.location.href = 'index.php?page=work_orders&action=delete&id=' + id;
    });
}
</script>