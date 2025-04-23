<div class="page-header">
    <h1 class="page-title">Work Order: <?php echo $work_order['order_number']; ?></h1>
    <div class="page-actions">
        <a href="index.php?page=work_orders" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Work Orders
        </a>
        
        <?php if(hasAnyRole(['admin', 'sdo']) && $work_order['status'] < 5): ?>
        <a href="index.php?page=work_orders&action=edit&id=<?php echo $work_order['id']; ?>" class="btn">
            <i class="fas fa-edit"></i> Edit
        </a>
        <?php endif; ?>
        
        <?php if(hasRole('contractor') && $work_order['status'] == 1 && $work_order['contractor_id'] == $_SESSION['user_id']): ?>
        <a href="index.php?page=work_orders&action=commence&id=<?php echo $work_order['id']; ?>" class="btn btn-success">
            <i class="fas fa-play"></i> Commence Work
        </a>
        <?php endif; ?>
        
        <button onclick="printElement('printableWorkOrder')" class="btn">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<div class="workflow-progress">
    <?php
    $statuses = [
        1 => ['label' => 'Issued', 'icon' => 'fa-file-contract'],
        2 => ['label' => 'Commenced', 'icon' => 'fa-play-circle'],
        3 => ['label' => 'In Progress', 'icon' => 'fa-hammer'],
        4 => ['label' => 'Completed', 'icon' => 'fa-check-circle'],
        5 => ['label' => 'Closed', 'icon' => 'fa-archive']
    ];
    
    foreach ($statuses as $status_id => $status_info) {
        $step_class = '';
        if ($work_order['status'] == $status_id) {
            $step_class = 'active';
        } elseif ($work_order['status'] > $status_id) {
            $step_class = 'completed';
        }
        
        echo '<div class="workflow-step ' . $step_class . '">';
        echo '<div class="workflow-step-icon"><i class="fas ' . $status_info['icon'] . '"></i></div>';
        echo '<div class="workflow-step-label">' . $status_info['label'] . '</div>';
        echo '</div>';
    }
    ?>
</div>

<div id="printableWorkOrder">
    <div class="card">
        <div class="document-header">
            <h2>WORK ORDER</h2>
            <div class="document-number"><?php echo $work_order['order_number']; ?></div>
            <div class="document-date">Issue Date: <?php echo formatDate($work_order['issue_date']); ?></div>
        </div>
        
        <div class="document-section">
            <div class="row">
                <div class="col-md-6">
                    <h3>Project Details</h3>
                    <table class="details-table">
                        <tr>
                            <th>Project Name:</th>
                            <td><?php echo htmlspecialchars($work_order['project_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td><?php echo htmlspecialchars($work_order['location']); ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="badge"><?php echo getWorkOrderStatusText($work_order['status']); ?></span></td>
                        </tr>
                        <tr>
                            <th>Expected Completion:</th>
                            <td><?php echo formatDate($work_order['completion_date']); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h3>Contractor Details</h3>
                    <table class="details-table">
                        <tr>
                            <th>Name:</th>
                            <td><?php echo htmlspecialchars($work_order['contractor_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo htmlspecialchars($work_order['contractor_email']); ?></td>
                        </tr>
                        <tr>
                            <th>Contract Value:</th>
                            <td><?php echo formatCurrency($work_order['contract_value']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="document-section">
            <h3>Project Description</h3>
            <p><?php echo nl2br(htmlspecialchars($work_order['description'])); ?></p>
        </div>
        
        <div class="document-section">
            <h3>Project Scope</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // In a real application, we would fetch scope items from the database
                    // For this example, we'll simulate with some data
                    $scope_items = [
                        ['description' => 'Excavation work', 'quantity' => 100, 'unit' => 'cubic_meters'],
                        ['description' => 'Foundation concrete', 'quantity' => 50, 'unit' => 'cubic_meters'],
                        ['description' => 'Brick masonry', 'quantity' => 500, 'unit' => 'square_meters']
                    ];
                    
                    foreach ($scope_items as $index => $item) {
                        echo '<tr>';
                        echo '<td>' . ($index + 1) . '</td>';
                        echo '<td>' . htmlspecialchars($item['description']) . '</td>';
                        echo '<td>' . $item['quantity'] . '</td>';
                        echo '<td>' . str_replace('_', ' ', ucfirst($item['unit'])) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="document-section">
            <h3>Terms and Conditions</h3>
            <p><?php echo nl2br(htmlspecialchars($work_order['terms'] ?? '
1. The contractor shall complete the work as per the specifications and timeline mentioned above.
2. Any changes to the scope of work must be approved in writing.
3. Payment shall be made based on actual work done and measured.
4. Quality of work shall meet the standards specified in the contract document.')); ?></p>
        </div>
        
        <div class="document-section">
            <h3>Approvals</h3>
            <div class="signatures">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">
                        <?php 
                        // Get the name of SDO who issued this work order
                        echo 'Sub Divisional Officer'; 
                        ?>
                    </div>
                    <div class="signature-date">Date: <?php echo formatDate($work_order['issue_date']); ?></div>
                </div>
                
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">
                        <?php echo htmlspecialchars($work_order['contractor_name']); ?>
                    </div>
                    <div class="signature-date">Date: ________________</div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($work_order['status'] >= 2): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Work Commencement</h2>
        </div>
        
        <div class="document-section">
            <p>The contractor has confirmed commencement of work on: <strong><?php echo formatDate($work_order['commencement_date']); ?></strong></p>
            
            <?php if(!empty($work_order['commencement_notes'])): ?>
            <h3>Commencement Notes</h3>
            <p><?php echo nl2br(htmlspecialchars($work_order['commencement_notes'])); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php
    // Fetch measurements for this work order
    // In a real application, we would fetch from the database
    $measurements_exist = $work_order['status'] >= 3;
    
    if($measurements_exist):
    ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Measurements</h2>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Measurement</th>
                        <th>Recorded By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Simulated measurements data
                    $measurements = [
                        ['date' => '2023-01-15', 'item' => 'Excavation work', 'measurement' => '85 cubic meters', 'recorded_by' => 'John Smith'],
                        ['date' => '2023-01-20', 'item' => 'Foundation concrete', 'measurement' => '45 cubic meters', 'recorded_by' => 'John Smith']
                    ];
                    
                    foreach ($measurements as $measurement) {
                        echo '<tr>';
                        echo '<td>' . formatDate($measurement['date']) . '</td>';
                        echo '<td>' . htmlspecialchars($measurement['item']) . '</td>';
                        echo '<td>' . htmlspecialchars($measurement['measurement']) . '</td>';
                        echo '<td>' . htmlspecialchars($measurement['recorded_by']) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    
    <?php
    // Fetch bills for this work order
    // In a real application, we would fetch from the database
    $bills_exist = $work_order['status'] >= 3;
    
    if($bills_exist):
    ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Bills</h2>
        </div>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Bill Number</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Simulated bills data
                    $bills = [
                        ['id' => 1, 'bill_number' => 'BILL-202301-0001', 'date' => '2023-01-30', 'amount' => 150000, 'status' => 6],
                        ['id' => 2, 'bill_number' => 'BILL-202302-0002', 'date' => '2023-02-28', 'amount' => 200000, 'status' => 3]
                    ];
                    
                    foreach ($bills as $bill) {
                        $badge_class = '';
                        switch ($bill['status']) {
                            case 1: // Submitted
                                $badge_class = 'badge-primary';
                                break;
                            case 6: // Approved
                                $badge_class = 'badge-success';
                                break;
                            case 8: // Payment Complete
                                $badge_class = 'badge-success';
                                break;
                            case 9: // Rejected
                                $badge_class = 'badge-danger';
                                break;
                            default:
                                $badge_class = 'badge-warning';
                        }
                        
                        echo '<tr>';
                        echo '<td>' . $bill['bill_number'] . '</td>';
                        echo '<td>' . formatDate($bill['date']) . '</td>';
                        echo '<td>' . formatCurrency($bill['amount']) . '</td>';
                        echo '<td><span class="badge ' . $badge_class . '">' . getBillStatusText($bill['status']) . '</span></td>';
                        echo '<td class="actions-cell">';
                        echo '<a href="index.php?page=bills&action=view&id=' . $bill['id'] . '" class="btn-icon" title="View Bill"><i class="fas fa-eye"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>