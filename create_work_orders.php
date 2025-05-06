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

  <?php include 'includes/header.php'; ?>

  <?php include 'includes/side-bar.php'; ?>

  <main id="main" class="main">

    <section class="section dashboard">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Create Work Orders</h5>
              <form action="services/work_order_creation.php" method="POST" class="row g-3 g-md-4">
                <div class="row gy-3 gy-md-4">
                  <div class="col-12 col-md-6">
                    <label for="cont_name" class="form-label">Contractor Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="cont_name" id="cont_name" placeholder="Enter Contractor Name" required>
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="cost" class="form-label">Total Cost <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="cost" id="cost" placeholder="Enter total cost" step="0.01" required>
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="date_of_commencement" class="form-label">Date of Commencement <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date_of_commencement" id="date_of_commencement" required>
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="time_limit" class="form-label">Time Limit (days) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="time_limit" id="time_limit" placeholder="Enter time limit in days" required>
                  </div>
                </div>

                <h6 class="mt-4">Items</h6>
                <div id="items-container">
                  <div class="item-row row gy-2 gy-md-3 mb-3 align-items-end">
                    <div class="col-12 col-md-2">
                      <label class="form-label">Item Name <span class="text-danger">*</span></label>
                      <select class="form-control item-select" name="items[0][id]" required>
                        <option value="">Select Item</option>
                        <?php
                        include 'services/db_config.php';
                        try {
                          $query = "SELECT id, item_no, category, description, unit, rate_numeric, rate_words, unit_basic FROM public.tbl_workorder_items";
                          $stmt = $pdo->query($query);
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}' data-description='{$row['description']}' data-unit='{$row['unit']}' data-rate='{$row['rate_numeric']}'>{$row['item_no']} - {$row['category']}</option>";
                          }
                        } catch (PDOException $e) {
                          echo "<option value=''>Error loading items: " . htmlspecialchars($e->getMessage()) . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-12 col-md-3">
                      <label class="form-label">Description</label>
                      <input type="text" class="form-control item-description" name="items[0][description]" placeholder="Enter description" readonly>
                    </div>
                    <div class="col-12 col-md-2">
                      <label class="form-label">Quantity <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" name="items[0][quantity]" placeholder="Qty" min="1" required>
                    </div>
                    <div class="col-12 col-md-2">
                      <label class="form-label">Unit <span class="text-danger">*</span></label>
                      <input type="text" class="form-control item-unit" name="items[0][unit]" placeholder="Unit" readonly required>
                    </div>
                    <div class="col-12 col-md-2">
                      <label class="form-label">Rate <span class="text-danger">*</span></label>
                      <input type="number" class="form-control item-rate" name="items[0][rate]" step="0.01" placeholder="Rate" readonly required>
                    </div>
                    <div class="col-12 col-md-1 d-grid">
                      <button type="button" class="btn btn-secondary btn-sm add-item-btn">+</button>
                    </div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Create Work Order</button>
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Handle item selection
      function updateItemFields(selectElement) {
        const row = selectElement.closest('.item-row');
        const descriptionInput = row.querySelector('.item-description');
        const unitInput = row.querySelector('.item-unit');
        const rateInput = row.querySelector('.item-rate');
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        descriptionInput.value = selectedOption.getAttribute('data-description') || '';
        unitInput.value = selectedOption.getAttribute('data-unit') || '';
        rateInput.value = selectedOption.getAttribute('data-rate') || '';
      }

      // Attach event listeners to existing selects
      document.querySelectorAll('.item-select').forEach(select => {
        select.addEventListener('change', () => updateItemFields(select));
      });

      // Handle adding new item rows
      let itemIndex = 1;
      document.querySelector('.add-item-btn').addEventListener('click', function () {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'item-row row gy-2 gy-md-3 mb-3 align-items-end';
        newRow.innerHTML = `
          <div class="col-12 col-md-2">
            <label class="form-label">Item Name <span class="text-danger">*</span></label>
            <select class="form-control item-select" name="items[${itemIndex}][id]" required>
              <option value="">Select Item</option>
              <?php
              try {
                $stmt = $pdo->query($query);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value='{$row['id']}' data-description='{$row['description']}' data-unit='{$row['unit']}' data-rate='{$row['rate_numeric']}'>{$row['item_no']} - {$row['category']}</option>";
                }
              } catch (PDOException $e) {
                echo "<option value=''>Error loading items: " . htmlspecialchars($e->getMessage()) . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label class="form-label">Description</label>
            <input type="text" class="form-control item-description" name="items[${itemIndex}][description]" placeholder="Enter description" readonly>
          </div>
          <div class="col-12 col-md-2">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" class="form-control" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" required>
          </div>
          <div class="col-12 col-md-2">
            <label class="form-label">Unit <span class="text-danger">*</span></label>
            <input type="text" class="form-control item-unit" name="items[${itemIndex}][unit]" placeholder="Unit" readonly required>
          </div>
          <div class="col-12 col-md-2">
            <label class="form-label">Rate <span class="text-danger">*</span></label>
            <input type="number" class="form-control item-rate" name="items[${itemIndex}][rate]" step="0.01" placeholder="Rate" readonly required>
          </div>
          <div class="col-12 col-md-1 d-grid">
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">-</button>
          </div>
        `;
        container.appendChild(newRow);

        // Attach event listener to new select
        newRow.querySelector('.item-select').addEventListener('change', () => updateItemFields(newRow.querySelector('.item-select')));

        // Attach event listener to remove button
        newRow.querySelector('.remove-item-btn').addEventListener('click', () => {
          newRow.remove();
        });

        itemIndex++;
      });

      // Handle removing item rows
      document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item-btn')) {
          e.target.closest('.item-row').remove();
        }
      });
    });
  </script>

</body>

</html>