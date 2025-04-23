<h1 class="page-title">Dashboard</h1>

<div class="dashboard-widgets">
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Pending Tasks</span>
            <i class="fas fa-tasks"></i>
        </div>
        <div class="widget-value"><?php echo getPendingTasksCount(); ?></div>
        <div class="widget-footer">
            <a href="#" onclick="showPendingTasks()">View details</a>
        </div>
    </div>
    
    <?php if(hasAnyRole(['admin', 'sdo'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Active Work Orders</span>
            <i class="fas fa-file-contract"></i>
        </div>
        <div class="widget-value">
            <?php 
                $active_orders = fetchOne("SELECT COUNT(*) as count FROM work_orders WHERE status IN (1, 2, 3)");
                echo $active_orders['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=work_orders">Manage work orders</a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(hasAnyRole(['sdo', 'sdc'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Pending Bills</span>
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="widget-value">
            <?php 
                $pending_bills = fetchOne("SELECT COUNT(*) as count FROM bills WHERE status IN (1, 2, 3)");
                echo $pending_bills['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=bills">Review bills</a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(hasAnyRole(['contractor'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">My Projects</span>
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="widget-value">
            <?php 
                $contractor_projects = fetchOne("SELECT COUNT(*) as count FROM work_orders WHERE contractor_id = ? AND status IN (1, 2, 3)", [$_SESSION['user_id']]);
                echo $contractor_projects['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=work_orders">View my projects</a>
        </div>
    </div>
    
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Pending Payments</span>
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="widget-value">
            <?php 
                $pending_payments = fetchOne("SELECT COUNT(*) as count FROM bills WHERE contractor_id = ? AND status BETWEEN 1 AND 7", [$_SESSION['user_id']]);
                echo $pending_payments['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=bills">View pending payments</a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(hasAnyRole(['subeng'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Pending Measurements</span>
            <i class="fas fa-ruler"></i>
        </div>
        <div class="widget-value">
            <?php 
                $pending_measurements = fetchOne("SELECT COUNT(*) as count FROM work_orders WHERE status = 2 AND needs_measurement = 1");
                echo $pending_measurements['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=measurements">Record measurements</a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(hasAnyRole(['xen', 'dao'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Vouchers for Approval</span>
            <i class="fas fa-receipt"></i>
        </div>
        <div class="widget-value">
            <?php 
                $status = hasRole('xen') ? 1 : 2;
                $vouchers = fetchOne("SELECT COUNT(*) as count FROM vouchers WHERE status = ?", [$status]);
                echo $vouchers['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=vouchers">Review vouchers</a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(hasAnyRole(['accounts'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Forms to Process</span>
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="widget-value">
            <?php 
                $forms = fetchOne("SELECT COUNT(*) as count FROM cheques WHERE form2_status = 0");
                echo $forms['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=forms">Process forms</a>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(hasAnyRole(['treasury'])): ?>
    <div class="widget slide-in">
        <div class="widget-header">
            <span class="widget-title">Cheques to Process</span>
            <i class="fas fa-money-check-alt"></i>
        </div>
        <div class="widget-value">
            <?php 
                $cheques = fetchOne("SELECT COUNT(*) as count FROM cheques WHERE treasury_status = 0");
                echo $cheques['count'];
            ?>
        </div>
        <div class="widget-footer">
            <a href="index.php?page=cheques">Process cheques</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Activity</h2>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get recent activities based on user role
                $user_id = $_SESSION['user_id'];
                $role = $_SESSION['user_role'];
                
                // Different queries based on role
                switch($role) {
                    case 'admin':
                    case 'sdo':
                        $activities = fetchAll("SELECT a.*, w.order_number, b.bill_number 
                                              FROM activities a 
                                              LEFT JOIN work_orders w ON a.work_order_id = w.id 
                                              LEFT JOIN bills b ON a.bill_id = b.id 
                                              ORDER BY a.activity_date DESC LIMIT 10");
                        break;
                    case 'contractor':
                        $activities = fetchAll("SELECT a.*, w.order_number, b.bill_number 
                                              FROM activities a 
                                              LEFT JOIN work_orders w ON a.work_order_id = w.id 
                                              LEFT JOIN bills b ON a.bill_id = b.id 
                                              WHERE (w.contractor_id = ? OR b.contractor_id = ?)
                                              ORDER BY a.activity_date DESC LIMIT 10", 
                                              [$user_id, $user_id]);
                        break;
                    default:
                        $activities = fetchAll("SELECT a.*, w.order_number, b.bill_number 
                                              FROM activities a 
                                              LEFT JOIN work_orders w ON a.work_order_id = w.id 
                                              LEFT JOIN bills b ON a.bill_id = b.id 
                                              WHERE a.user_id = ? 
                                              ORDER BY a.activity_date DESC LIMIT 10", 
                                              [$user_id]);
                }
                
                if (count($activities) > 0) {
                    foreach ($activities as $activity) {
                        $badge_class = '';
                        switch ($activity['status']) {
                            case 'completed':
                                $badge_class = 'badge-success';
                                break;
                            case 'pending':
                                $badge_class = 'badge-warning';
                                break;
                            case 'rejected':
                                $badge_class = 'badge-danger';
                                break;
                            default:
                                $badge_class = 'badge-primary';
                        }
                        
                        echo '<tr>';
                        echo '<td>' . formatDate($activity['activity_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($activity['description']) . '</td>';
                        echo '<td><span class="badge ' . $badge_class . '">' . ucfirst($activity['status']) . '</span></td>';
                        echo '<td>';
                        
                        if (!empty($activity['work_order_id'])) {
                            echo '<a href="index.php?page=work_orders&action=view&id=' . $activity['work_order_id'] . '">
                                    Work Order: ' . $activity['order_number'] . '
                                  </a>';
                        } elseif (!empty($activity['bill_id'])) {
                            echo '<a href="index.php?page=bills&action=view&id=' . $activity['bill_id'] . '">
                                    Bill: ' . $activity['bill_number'] . '
                                  </a>';
                        }
                        
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center">No recent activities found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function showPendingTasks() {
    // Implementation will depend on the specific requirements
    // This could show a modal or redirect to a tasks page
    window.location.href = 'index.php?page=tasks';
}
</script>