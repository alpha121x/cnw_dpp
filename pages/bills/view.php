<div class="page-header">
    <h1 class="page-title">Bill: <?php echo $bill['bill_number']; ?></h1>
    <div class="page-actions">
        <a href="index.php?page=bills" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Bills
        </a>
        
        <?php if(hasRole('sdo') && $bill['status'] == 1): ?>
        <a href="index.php?page=bills&action=review&id=<?php echo $bill['id']; ?>" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Review Bill
        </a>
        <?php endif; ?>
        
        <?php if(hasRole('sdc') && $bill['status'] == 3): ?>
        <a href="index.php?page=bills&action=process_voucher&id=<?php echo $bill['id']; ?>" class="btn btn-success">
            <i class="fas fa-receipt"></i> Process Voucher
        </a>
        <?php endif; ?>
        
        <button onclick="printElement('printableBill')" class="btn">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>

<div class="workflow-progress">
    <?php
    $bill_statuses = [
        1 => ['label' => 'Submitted', 'icon' => 'fa-file-invoice'],
        2 => ['label' => 'SDO Review', 'icon' => 'fa-user-check'],
        3 => ['label' => 'SDC Processing', 'icon' => 'fa-cogs'],
        4 => ['label' => 'XEN Review', 'icon' => 'fa-user-tie'],
        5 => ['label' => 'DAO Verification', 'icon' => 'fa-search-dollar'],
        6 => ['label' => 'Approved', 'icon' => 'fa-check-double'],
        7 => ['label' => 'Cheque Issued', 'icon' => 'fa-money-check'],
        8 => ['label' => 'Payment Complete', 'icon' => 'fa-money-bill-wave']
    ];
    
    foreach ($bill_statuses as $status_id => $status_info) {
        if ($status_id <= 8) { // Don't show Rejected in the workflow
            $step_class = '';
            if ($bill['status'] == $status_id) {
                $step_class = 'active';
            } elseif ($bill['status'] > $status_id && $bill['status'] != 9) { // If not rejected
                $step_class = 'completed';
            } elseif ($bill['status'] == 9) { // If rejected
                $step_class = $status_id <= 2 ? 'completed' : '';
            }
            
            echo '<div class="workflow-step ' . $step_class . '">';
            echo '<div class="workflow-step-icon"><i class="fas ' . $status_info['icon'] . '"></i></div>';
            echo '<div class="workflow-step-label">' . $status_info['label'] . '</div>';
            echo '</div>';
        }
    }
    ?>
</div>

<div id="printableBill">
    <div class="card">
        <div class="document-header">
            <h2>CONTRACTOR BILL</h2>
            <div class="document-number"><?php echo $bill['bill_number']; ?></div>
            <div class="document-date">Submission Date: <?php echo formatDate($bill['submission_date']); ?></div>
            <?php if($bill['status'] == 9): ?>
            <div class="document-status rejected">REJECTED</div>
            <?php endif; ?>
        </div>
        
        <div class="document-section">
            <div class="row">
                <div class="col-md-6">
                    <h3>Work Order Details</h3>
                    <table class="details-table">
                        <tr>
                            <th>Work Order:</th>
                            <td><?php echo $bill['order_number']; ?></td>
                        </tr>
                        <tr>
                            <th>Project:</th>
                            <td><?php echo htmlspecialchars($bill['project_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="badge"><?php echo getBillStatusText($bill['status']); ?></span></td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-6">
                    <h3>Contractor Details</h3>
                    <table class="details-table">
                        <tr>
                            <th>Name:</th>
                            <td><?php echo htmlspecialchars($bill['contractor_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Bill Period:</th>
                            <td><?php echo formatDate($bill['period_from']) . ' to ' . formatDate($bill['period_to']); ?></td>
                        </tr>
                        <tr>
                            <th>Bill Amount:</th>
                            <td><?php echo formatCurrency($bill['amount']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="document-section">
            <h3>Bill Items</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // In a real application, we would fetch bill items from the database
                    // For this example, we'll simulate with some data
                    $bill_items = [
                        ['description' => 'Excavation work', 'quantity' => 85, 'rate' => 500],
                        ['description' => 'Foundation concrete', 'quantity' => 45, 'rate' => 2000],
                        ['description' => 'Labor charges', 'quantity' => 1, 'rate' => 50000]
                    ];
                    
                    $total = 0;
                    foreach ($bill_items as $index => $item) {
                        $amount = $item['quantity'] * $item['rate'];
                        $total += $amount;
                        
                        echo '<tr>';
                        echo '<td>' . ($index + 1) . '</td>';
                        echo '<td>' . htmlspecialchars($item['description']) . '</td>';
                        echo '<td>' . $item['quantity'] . '</td>';
                        echo '<td>' . formatCurrency($item['rate']) . '</td>';
                        echo '<td>' . formatCurrency($amount) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Amount:</th>
                        <th><?php echo formatCurrency($total); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <?php if(!empty($bill['notes'])): ?>
        <div class="document-section">
            <h3>Notes</h3>
            <p><?php echo nl2br(htmlspecialchars($bill['notes'])); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="document-section">
            <h3>Approval Details</h3>
            <div class="approval-timeline">
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-file-invoice"></i></div>
                    <div class="timeline-content">
                        <h4>Bill Submitted</h4>
                        <p>Date: <?php echo formatDate($bill['submission_date']); ?></p>
                        <p>By: <?php echo htmlspecialchars($bill['contractor_name']); ?></p>
                    </div>
                </div>
                
                <?php if($bill['status'] >= 2): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-user-check"></i></div>
                    <div class="timeline-content">
                        <h4>SDO Review</h4>
                        <p>Date: <?php echo formatDate($bill['sdo_review_date'] ?? date('Y-m-d')); ?></p>
                        <p>Status: <?php echo $bill['status'] == 9 ? 'Rejected' : 'Approved'; ?></p>
                        <?php if(!empty($bill['sdo_remarks'])): ?>
                        <p>Remarks: <?php echo htmlspecialchars($bill['sdo_remarks']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($bill['status'] >= 3 && $bill['status'] != 9): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-cogs"></i></div>
                    <div class="timeline-content">
                        <h4>SDC Processing</h4>
                        <p>Date: <?php echo formatDate($bill['sdc_processing_date'] ?? date('Y-m-d')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($bill['status'] >= 4 && $bill['status'] != 9): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-user-tie"></i></div>
                    <div class="timeline-content">
                        <h4>XEN Review</h4>
                        <p>Date: <?php echo formatDate($bill['xen_review_date'] ?? date('Y-m-d')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($bill['status'] >= 5 && $bill['status'] != 9): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-search-dollar"></i></div>
                    <div class="timeline-content">
                        <h4>DAO Verification</h4>
                        <p>Date: <?php echo formatDate($bill['dao_verification_date'] ?? date('Y-m-d')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($bill['status'] >= 6 && $bill['status'] != 9): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-check-double"></i></div>
                    <div class="timeline-content">
                        <h4>Bill Approved</h4>
                        <p>Date: <?php echo formatDate($bill['approval_date'] ?? date('Y-m-d')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($bill['status'] >= 7 && $bill['status'] != 9): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-money-check"></i></div>
                    <div class="timeline-content">
                        <h4>Cheque Issued</h4>
                        <p>Date: <?php echo formatDate($bill['cheque_date'] ?? date('Y-m-d')); ?></p>
                        <p>Cheque Number: <?php echo $bill['cheque_number'] ?? 'Pending'; ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($bill['status'] == 8): ?>
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="timeline-content">
                        <h4>Payment Complete</h4>
                        <p>Date: <?php echo formatDate($bill['payment_date'] ?? date('Y-m-d')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if($bill['status'] >= 3 && $bill['status'] != 9): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Voucher Details</h2>
        </div>
        
        <div class="document-section">
            <?php
            // In a real application, we would fetch voucher details from the database
            // For this example, we'll simulate with some data
            $voucher = [
                'voucher_number' => 'V-202301-0001',
                'preparation_date' => '2023-02-05',
                'amount' => $bill['amount'],
                'status' => 'Approved'
            ];
            ?>
            
            <table class="details-table">
                <tr>
                    <th>Voucher Number:</th>
                    <td><?php echo $voucher['voucher_number']; ?></td>
                    <th>Preparation Date:</th>
                    <td><?php echo formatDate($voucher['preparation_date']); ?></td>
                </tr>
                <tr>
                    <th>Amount:</th>
                    <td><?php echo formatCurrency($voucher['amount']); ?></td>
                    <th>Status:</th>
                    <td><?php echo $voucher['status']; ?></td>
                </tr>
            </table>
            
            <div class="text-center mt-4">
                <a href="index.php?page=vouchers&action=view&id=1" class="btn">View Voucher Details</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if($bill['status'] >= 7 && $bill['status'] != 9): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Cheque Details</h2>
        </div>
        
        <div class="document-section">
            <?php
            // In a real application, we would fetch cheque details from the database
            // For this example, we'll simulate with some data
            $cheque = [
                'cheque_number' => 'CHQ-202301-0001',
                'issue_date' => '2023-02-10',
                'amount' => $bill['amount'],
                'status' => $bill['status'] == 8 ? 'Cleared' : 'Issued'
            ];
            ?>
            
            <table class="details-table">
                <tr>
                    <th>Cheque Number:</th>
                    <td><?php echo $cheque['cheque_number']; ?></td>
                    <th>Issue Date:</th>
                    <td><?php echo formatDate($cheque['issue_date']); ?></td>
                </tr>
                <tr>
                    <th>Amount:</th>
                    <td><?php echo formatCurrency($cheque['amount']); ?></td>
                    <th>Status:</th>
                    <td><?php echo $cheque['status']; ?></td>
                </tr>
            </table>
            
            <div class="text-center mt-4">
                <a href="index.php?page=cheques&action=view&id=1" class="btn">View Cheque Details</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>