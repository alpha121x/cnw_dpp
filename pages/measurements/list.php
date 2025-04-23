<div class="page-header">
    <h1 class="page-title">Measurements</h1>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Work Orders Ready for Measurement</h2>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Project Name</th>
                    <th>Contractor</th>
                    <th>Commencement Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch work orders that need measurement
                $work_orders = fetchAll("SELECT wo.*, c.name as contractor_name 
                                      FROM work_orders wo 
                                      JOIN users c ON wo.contractor_id = c.id 
                                      WHERE wo.status = 2 AND wo.needs_measurement = 1 
                                      ORDER BY wo.commencement_date DESC");
                
                if (count($work_orders) > 0) {
                    foreach ($work_orders as $order) {
                        echo '<tr>';
                        echo '<td>' . $order['order_number'] . '</td>';
                        echo '<td>' . htmlspecialchars($order['project_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($order['contractor_name']) . '</td>';
                        echo '<td>' . formatDate($order['commencement_date']) . '</td>';
                        echo '<td class="actions-cell">';
                        echo '<a href="index.php?page=measurements&action=record&work_order_id=' . $order['id'] . '" class="btn btn-primary">Record Measurement</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">No work orders pending measurement</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Measurements</h2>
        <div class="search-filter">
            <input type="text" id="measurementSearch" placeholder="Search measurements..." class="form-control">
        </div>
    </div>
    
    <div class="table-container">
        <table id="measurementsTable">
            <thead>
                <tr>
                    <th>Measurement ID</th>
                    <th>Work Order</th>
                    <th>Project</th>
                    <th>Measurement Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch recent measurements
                $measurements = fetchAll("SELECT m.*, wo.order_number, wo.project_name 
                                       FROM measurements m 
                                       JOIN work_orders wo ON m.work_order_id = wo.id 
                                       ORDER BY m.measurement_date DESC 
                                       LIMIT 20");
                
                if (count($measurements) > 0) {
                    foreach ($measurements as $measurement) {
                        $status = $measurement['verified'] ? 'Verified' : 'Pending Verification';
                        $badge_class = $measurement['verified'] ? 'badge-success' : 'badge-warning';
                        
                        echo '<tr>';
                        echo '<td>' . $measurement['measurement_number'] . '</td>';
                        echo '<td>' . $measurement['order_number'] . '</td>';
                        echo '<td>' . htmlspecialchars($measurement['project_name']) . '</td>';
                        echo '<td>' . formatDate($measurement['measurement_date']) . '</td>';
                        echo '<td><span class="badge ' . $badge_class . '">' . $status . '</span></td>';
                        echo '<td class="actions-cell">';
                        echo '<a href="index.php?page=measurements&action=view&id=' . $measurement['id'] . '" class="btn-icon" title="View Details"><i class="fas fa-eye"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">No measurements found</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Initialize search functionality
document.addEventListener('DOMContentLoaded', function() {
    searchTable('measurementSearch', 'measurementsTable');
});
</script>