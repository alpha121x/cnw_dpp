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
  require_once 'services/db_config.php'; // Include database configuration

  // Check if user is logged in
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
  }

  // Fetch contractors for dropdown
  try {
    $stmt = $pdo->prepare("SELECT * FROM public.tbl_contractors ORDER BY id ASC");
    $stmt->execute();
    $contractors = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    error_log("Error fetching contractors: " . $e->getMessage());
    $contractors = [];
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
              <h5 class="card-title">Interim Payment Bills Form</h5>

              <!-- Form -->
              <form action="process_interim_payment.php" method="POST">
                <div class="row">
                  <!-- Contractor Name Dropdown -->
                  <div class="col-12 col-md-6">
                    <label for="contractor_name" class="form-label">Contractor Name <span class="text-danger">*</span></label>
                    <select class="form-control" name="contractor_name" id="contractor_name" required>
                      <option value="" disabled selected>Select Contractor</option>
                      <?php foreach ($contractors as $contractor): ?>
                        <option value="<?php echo htmlspecialchars($contractor['id']); ?>">
                          <?php echo htmlspecialchars($contractor['name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Subject -->
                  <div class="col-12 col-md-6">
                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Enter Subject" required>
                  </div>

                  <!-- Date of Commencement -->
                  <div class="col-12 col-md-6">
                    <label for="date_of_commencement" class="form-label">Date of Commencement <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date_of_commencement" id="date_of_commencement" required>
                  </div>

                  <!-- Cost -->
                  <div class="col-12 col-md-6">
                    <label for="cost" class="form-label">Cost <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="cost" id="cost" step="0.01" placeholder="Enter Cost" required>
                  </div>

                  <!-- BOQ Items -->
                  <div class="col-12 mt-4">
                    <h6>BOQ Items</h6>
                    <div id="items-container">
                      <!-- Initial Row -->
                      <div class="d-flex align-items-end gap-2 mb-2 item-row">
                        <div style="flex: 1; min-width: 150px;">
                          <label class="form-label">Item No. <span class="text-danger">*</span></label>
                          <select class="form-control item-select" name="items[0][id]" required disabled>
                            <option value="" disabled selected>Select Item</option>
                          </select>
                        </div>
                        <div style="flex: 2; min-width: 200px;">
                          <label class="form-label">Description</label>
                          <input type="text" class="form-control item-description" name="items[0][description]" placeholder="Enter description" readonly>
                        </div>
                        <div style="flex: 1; min-width: 100px;">
                          <label class="form-label">Quantity <span class="text-danger">*</span></label>
                          <input type="number" class="form-control" name="items[0][quantity]" placeholder="Qty" min="0.01" step="any" required>
                        </div>
                        <div style="flex: 1; min-width: 100px;">
                          <label class="form-label">Unit <span class="text-danger">*</span></label>
                          <input type="text" class="form-control item-unit" name="items[0][unit]" placeholder="Unit" readonly required>
                        </div>
                        <div style="flex: 1; min-width: 100px;">
                          <label class="form-label">Rate <span class="text-danger">*</span></label>
                          <input type="number" class="form-control item-rate" name="items[0][rate]" step="0.01" placeholder="Rate" readonly required>
                        </div>
                        <div style="min-width: 50px;">
                          <button type="button" class="btn btn-secondary add-item-btn w-100">+</button>
                        </div>
                      </div>
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

  <!-- JavaScript for Dynamic BOQ Items -->
  <script>
    let itemCount = 1;

    document.querySelectorAll('.add-item-btn').forEach(button => {
      button.addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'd-flex align-items-end gap-2 mb-2 item-row';
        newRow.innerHTML = `
          <div style="flex: 1; min-width: 150px;">
            <label class="form-label">Item No. <span class="text-danger">*</span></label>
            <select class="form-control item-select" name="items[${itemCount}][id]" required disabled>
              <option value="" disabled selected>Select Item</option>
            </select>
          </div>
          <div style="flex: 2; min-width: 200px;">
            <label class="form-label">Description</label>
            <input type="text" class="form-control item-description" name="items[${itemCount}][description]" placeholder="Enter description" readonly>
          </div>
          <div style="flex: 1; min-width: 100px;">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="items[${itemCount}][quantity]" placeholder="Qty" min="0.01" step="any" required>
          </div>
          <div style="flex: 1; min-width: 100px;">
            <label class="form-label">Unit <span class="text-danger">*</span></label>
            <input type="text" class="form-control item-unit" name="items[${itemCount}][unit]" placeholder="Unit" readonly required>
          </div>
          <div style="flex: 1; min-width: 100px;">
            <label class="form-label">Rate <span class="text-danger">*</span></label>
            <input type="number" class="form-control item-rate" name="items[${itemCount}][rate]" step="0.01" placeholder="Rate" readonly required>
          </div>
          <div style="min-width: 50px;">
            <button type="button" class="btn btn-danger remove-item-btn w-100">-</button>
          </div>
        `;
        container.appendChild(newRow);
        itemCount++;

        // Add event listener for the new remove button
        newRow.querySelector('.remove-item-btn').addEventListener('click', function() {
          container.removeChild(newRow);
        });
      });
    });
  </script>

</body>
</html>