<div class="page-header">
    <h1 class="page-title">Submit New Bill</h1>
    <div class="page-actions">
        <a href="index.php?page=bills" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Bills
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
                <th>Issue Date:</th>
                <td><?php echo formatDate($work_order['issue_date']); ?></td>
                <th>Status:</th>
                <td><?php echo getWorkOrderStatusText($work_order['status']); ?></td>
            </tr>
            <tr>
                <th>Contract Value:</th>
                <td><?php echo formatCurrency($work_order['contract_value']); ?></td>
                <th>Location:</th>
                <td><?php echo htmlspecialchars($work_order['location']); ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Bill Information</h2>
    </div>
    
    <form action="includes/process_bill.php" method="post" class="form" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="work_order_id" value="<?php echo $work_order['id']; ?>">
        
        <div class="form-grid">
            <div class="form-group">
                <label for="bill_date">Bill Date</label>
                <input type="date" id="bill_date" name="bill_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="period_from">Period From</label>
                <input type="date" id="period_from" name="period_from" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="period_to">Period To</label>
                <input type="date" id="period_to" name="period_to" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="amount">Bill Amount</label>
                <input type="number" id="amount" name="amount" step="0.01" class="form-control" required>
                <div class="form-note">Maximum allowed amount: <?php echo formatCurrency($work_order['contract_value']); ?></div>
            </div>
        </div>
        
        <h3 class="section-title">Bill Items</h3>
        
        <div id="billItemsContainer">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Item Description</label>
                    <input type="text" name="bill_items[]" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Quantity</label>
                    <input type="number" name="quantities[]" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Rate</label>
                    <input type="number" name="rates[]" step="0.01" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="addBillItem()">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
        </div>
        
        <div class="form-group">
            <label for="bill_document">Upload Bill Document (PDF)</label>
            <input type="file" id="bill_document" name="bill_document" class="form-control" accept=".pdf">
            <div class="form-note">Optional: Upload a scanned copy of the bill (PDF format only, max 5MB)</div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">
                <i class="fas fa-paper-plane"></i> Submit Bill
            </button>
            <a href="index.php?page=bills" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function addBillItem() {
    const container = document.getElementById('billItemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'form-row';
    newRow.innerHTML = `
        <div class="form-group col-md-6">
            <label>Item Description</label>
            <input type="text" name="bill_items[]" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
            <label>Quantity</label>
            <input type="number" name="quantities[]" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
            <label>Rate</label>
            <input type="number" name="rates[]" step="0.01" class="form-control" required>
            <button type="button" class="btn-icon btn-danger remove-item" onclick="this.parentNode.parentNode.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newRow);
}

// Calculate total amount when bill items change
document.addEventListener('input', function(e) {
    if (e.target.name === 'quantities[]' || e.target.name === 'rates[]') {
        calculateTotal();
    }
});

function calculateTotal() {
    const quantities = document.getElementsByName('quantities[]');
    const rates = document.getElementsByName('rates[]');
    let total = 0;
    
    for (let i = 0; i < quantities.length; i++) {
        const quantity = parseFloat(quantities[i].value) || 0;
        const rate = parseFloat(rates[i].value) || 0;
        total += quantity * rate;
    }
    
    document.getElementById('amount').value = total.toFixed(2);
}
</script>