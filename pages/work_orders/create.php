<div class="page-header">
    <h1 class="page-title">Create New Work Order</h1>
    <div class="page-actions">
        <a href="index.php?page=work_orders" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Work Orders
        </a>
    </div>
</div>

<div class="card">
    <form action="includes/process_work_order.php" method="post" class="form">
        <input type="hidden" name="action" value="create">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="project_name">Project Name</label>
                <input type="text" id="project_name" name="project_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="contractor_id">Contractor</label>
                <select id="contractor_id" name="contractor_id" class="form-control" required>
                    <option value="">Select Contractor</option>
                    <?php
                    $contractors = fetchAll("SELECT id, name FROM users WHERE role = 'contractor'");
                    foreach ($contractors as $contractor) {
                        echo '<option value="' . $contractor['id'] . '">' . htmlspecialchars($contractor['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="issue_date">Issue Date</label>
                <input type="date" id="issue_date" name="issue_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="completion_date">Expected Completion Date</label>
                <input type="date" id="completion_date" name="completion_date" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="contract_value">Contract Value</label>
                <input type="number" id="contract_value" name="contract_value" step="0.01" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="location">Project Location</label>
                <input type="text" id="location" name="location" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Project Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>
        
        <h3 class="section-title">Project Scope</h3>
        
        <div id="scopeItemsContainer">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Item Description</label>
                    <input type="text" name="scope_items[]" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Quantity</label>
                    <input type="number" name="quantities[]" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Unit</label>
                    <select name="units[]" class="form-control" required>
                        <option value="meters">Meters</option>
                        <option value="square_meters">Square Meters</option>
                        <option value="cubic_meters">Cubic Meters</option>
                        <option value="pieces">Pieces</option>
                        <option value="kg">Kilograms</option>
                        <option value="tons">Tons</option>
                        <option value="hours">Hours</option>
                        <option value="days">Days</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="addScopeItem()">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </div>
        
        <h3 class="section-title">Terms and Conditions</h3>
        
        <div class="form-group">
            <textarea id="terms" name="terms" class="form-control" rows="4">
1. The contractor shall complete the work as per the specifications and timeline mentioned above.
2. Any changes to the scope of work must be approved in writing.
3. Payment shall be made based on actual work done and measured.
4. Quality of work shall meet the standards specified in the contract document.
            </textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">
                <i class="fas fa-save"></i> Issue Work Order
            </button>
            <a href="index.php?page=work_orders" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function addScopeItem() {
    const container = document.getElementById('scopeItemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'form-row';
    newRow.innerHTML = `
        <div class="form-group col-md-6">
            <label>Item Description</label>
            <input type="text" name="scope_items[]" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
            <label>Quantity</label>
            <input type="number" name="quantities[]" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
            <label>Unit</label>
            <select name="units[]" class="form-control" required>
                <option value="meters">Meters</option>
                <option value="square_meters">Square Meters</option>
                <option value="cubic_meters">Cubic Meters</option>
                <option value="pieces">Pieces</option>
                <option value="kg">Kilograms</option>
                <option value="tons">Tons</option>
                <option value="hours">Hours</option>
                <option value="days">Days</option>
            </select>
            <button type="button" class="btn-icon btn-danger remove-item" onclick="this.parentNode.parentNode.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newRow);
}
</script>