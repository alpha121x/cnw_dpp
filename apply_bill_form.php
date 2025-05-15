<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>C&W- DPP</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include 'includes/header-files.php'; ?>
</head>

<body>
  <?php
  session_start();
  require_once 'services/db_config.php';

  // Check if user is logged in
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
  }

  $workOrderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

  // Fetch work order details
  $workOrder = [];
  $boqItems = [];
  try {
    // Fetch from tbl_workorders
    $stmt = $pdo->prepare("SELECT id, cost, date_of_commencement, time_limit_months, created_at, contractor_id, contractor_name, ref_no, ref_date, se_ref_no, se_ref_date, amount_numeric, amount_words, is_issued, subject FROM public.tbl_workorders WHERE id = ?");
    $stmt->execute([$workOrderId]);
    $workOrder = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch BOQ items from tbl_workorder_qty
    $stmt = $pdo->prepare("SELECT id, workorder_id, item_id, quantity FROM public.tbl_workorder_qty WHERE workorder_id = ?");
    $stmt->execute([$workOrderId]);
    $boqItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Placeholder: Fetch item details (description, unit, rate) from a tbl_items table
    // This requires a join or separate query; adjust based on your schema
    // For now, I'll assume these are fetched dynamically via JavaScript or pre-loaded
  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $workOrder = [];
    $boqItems = [];
  }

  if (empty($workOrder)) {
    die("Work order not found.");
  }
  ?>

  <?php include 'includes/header.php'; ?>

  <?php include 'includes/side-bar.php'; ?>

  <main id="main" class="main">
    <section class="section dashboard">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Apply Interim Payment Bill</h5>

              <!-- Form -->
              <form action="process_apply_bill.php" method="POST">
                <input type="hidden" name="workorder_id" value="<?php echo htmlspecialchars($workOrder['id']); ?>">

                <div class="row">
                  <!-- Contractor Name (Read-only) -->
                  <div class="col-12 col-md-6">
                    <label for="contractor_name" class="form-label">Contractor Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="contractor_name" id="contractor_name" value="<?php echo htmlspecialchars($workOrder['contractor_name']); ?>" readonly required>
                  </div>

                  <!-- Subject (Read-only) -->
                  <div class="col-12 col-md-6">
                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="subject" id="subject" value="<?php echo htmlspecialchars($workOrder['subject']); ?>" readonly required>
                  </div>

                  <!-- Date of Commencement (Read-only) -->
                  <div class="col-12 col-md-6">
                    <label for="date_of_commencement" class="form-label">Date of Commencement <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date_of_commencement" id="date_of_commencement" value="<?php echo htmlspecialchars($workOrder['date_of_commencement']); ?>" readonly required>
                  </div>

                  <!-- Cost (Editable) -->
                  <div class="col-12 col-md-6">
                    <label for="cost" class="form-label">Cost <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="cost" id="cost" step="0.01" value="<?php echo htmlspecialchars($workOrder['cost'] ?? ''); ?>" placeholder="Enter Cost" required>
                  </div>

                  <!-- BOQ Items -->
                  <div class="col-12 mt-4">
                    <h6>BOQ Items</h6>
                    <div id="items-container">
                      <?php foreach ($boqItems as $index => $item): ?>
                        <div class="d-flex align-items-end gap-2 mb-2 item-row">
                          <div style="flex: 1; min-width: 150px;">
                            <label class="form-label">Item No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="items[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($item['item_id']); ?>" readonly required>
                          </div>
                          <div style="flex: 2; min-width: 200px;">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control item-description" name="items[<?php echo $index; ?>][description]" placeholder="Enter description" value="" readonly>
                          </div>
                          <div style="flex: 1; min-width: 100px;">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="items[<?php echo $index; ?>][quantity]" value="<?php echo htmlspecialchars($item['quantity']); ?>" readonly required>
                          </div>
                          <div style="flex: 1; min-width: 100px;">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control item-unit" name="items[<?php echo $index; ?>][unit]" placeholder="Unit" value="" readonly required>
                          </div>
                          <div style="flex: 1; min-width: 100px;">
                            <label class="form-label">Rate <span class="text-danger">*</span></label>
                            <input type="number" class="form-control item-rate" name="items[<?php echo $index; ?>][rate]" step="0.01" placeholder="Rate" value="" readonly required>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <?php include 'includes/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include 'includes/footer-src-files.php'; ?>

  <!-- JavaScript to Fetch Item Details (Placeholder) -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.item-row').forEach(row => {
        const itemIdInput = row.querySelector('[name^="items"][name$="[id]"]');
        const descriptionInput = row.querySelector('.item-description');
        const unitInput = row.querySelector('.item-unit');
        const rateInput = row.querySelector('.item-rate');
        const itemId = itemIdInput.value;

        if (itemId) {
          // Placeholder: Fetch item details via AJAX
          fetch(`get_item_details.php?id=${itemId}`)
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                descriptionInput.value = data.description || '';
                unitInput.value = data.unit || '';
                rateInput.value = data.rate || '';
              }
            })
            .catch(error => console.error('Error fetching item details:', error));
        }
      });
    });
  </script>
</body>
</html>