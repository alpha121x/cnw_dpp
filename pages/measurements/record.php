<div class="page-header">
    <h1 class="page-title">Record Measurement</h1>
    <div class="page-actions">
        <a href="index.php?page=measurements" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Measurements
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Work Order Details</h2>
    </div>
    
    <div class="work-order-summary">
        <table class="details-table">
            <tr>
                <th>Work Order:</th>
                <td><?php echo $work_order['order_number']; ?></td>
                <th>Project:</th>
                <td><?php echo htmlspecialchars($work_order['project_name']); ?></td>
            </tr>
            <tr>
                <th>Contractor:</th>
                <td><?php echo htmlspecialchars($work_order['contractor_name']); ?></td>
                <th>Commencement Date:</th>
                <td><?php echo formatDate($work_order['commencement_date']); ?></td>
            </tr>
            <tr>
                <th>Location:</th>
                <td><?php echo htmlspecialchars($work_order['location']); ?></td>
                <th>Status:</th>
                <td><?php echo getWorkOrderStatusText($work_order['status']); ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Measurement Book Entry</h2>
    </div>
    
    <form action="includes/process_measurement.php" method="post" class="form">
        <input type="hidden" name="action" value="record">
        <input type="hidden" name="work_order_id" value="<?php echo $work_order['id']; ?>">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="measurement_date">Measurement Date</label>
                <input type="date" id="measurement_date" name="measurement_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="location">Measurement Location</label>
                <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($work_order['location']); ?>" required>
            </div>
        </div>
        
        <h3 class="section-title">Item Measurements</h3>
        
        <div id="measurementItemsContainer">
            <?php
            // In a real application, we would fetch scope items from the database
            // For this example, we'll simulate with some data
            $scope_items = [
                ['id' => 1, 'description' => 'Excavation work', 'quantity' => 100, 'unit' => 'cubic_meters'],
                ['id' => 2, 'description' => 'Foundation concrete', 'quantity' => 50, 'unit' => 'cubic_meters'],
                ['id' => 3, 'description' => 'Brick masonry', 'quantity' => 500, 'unit' => 'square_meters']
            ];
            
            foreach ($scope_items as $index => $item) {
                echo '<div class="form-row">';
                echo '<div class="form-group col-md-6">';
                echo '<label>Item Description</label>';
                echo '<input type="text" name="item_descriptions[]" class="form-control" value="' . htmlspecialchars($item['description']) . '" readonly>';
                echo '<input type="hidden" name="item_ids[]" value="' . $item['id'] . '">';
                echo '</div>';
                
                echo '<div class="form-group col-md-2">';
                echo '<label>Allocated Quantity</label>';
                echo '<input type="text" class="form-control" value="' . $item['quantity'] . ' ' . str_replace('_', ' ', $item['unit']) . '" readonly>';
                echo '</div>';
                
                echo '<div class="form-group col-md-4">';
                echo '<label>Measured Quantity</label>';
                echo '<div class="input-group">';
                echo '<input type="number" name="quantities[]" class="form-control" step="0.01" required>';
                echo '<span class="input-group-text">' . str_replace('_', ' ', $item['unit']) . '</span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="form-row">';
                echo '<div class="form-group col-md-12">';
                echo '<label>Measurement Notes</label>';
                echo '<textarea name="notes[]" class="form-control" rows="2"></textarea>';
                echo '</div>';
                echo '</div>';
                
                if ($index < count($scope_items) - 1) {
                    echo '<hr>';
                }
            }
            ?>
        </div>
        
        <div class="form-group">
            <label for="remarks">Overall Remarks</label>
            <textarea id="remarks" name="remarks" class="form-control" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" id="confirmation" name="confirmation" class="form-check-input" required>
                <label for="confirmation" class="form-check-label">
                    I confirm that all measurements are accurate and have been recorded as per the actual work executed at the site.
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">
                <i class="fas fa-save"></i> Record in Measurement Book
            </button>
            <a href="index.php?page=measurements" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>