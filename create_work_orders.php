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

  <main id="main" class="main" style="margin-top: 50px;">

    <section class="section dashboard">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Create Work Orders</h5>
              <form action="services/work_order_creation.php" method="POST" class="row g-2 g-md-4">
                <div class="row gy-2 gy-md-4">
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
                    <label for="time_limit" class="form-label">Time Limit (Months) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="time_limit" id="time_limit" placeholder="Enter time limit in days" required>
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="ref_no" class="form-label">Reference Number</label>
                    <input type="text" class="form-control" name="ref_no" id="ref_no" placeholder="Enter reference number">
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="ref_date" class="form-label">Reference Date</label>
                    <input type="date" class="form-control" name="ref_date" id="ref_date">
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="se_ref_no" class="form-label">Secondary Reference Number</label>
                    <input type="text" class="form-control" name="se_ref_no" id="se_ref_no" placeholder="Enter secondary reference number">
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="se_ref_date" class="form-label">Secondary Reference Date</label>
                    <input type="date" class="form-control" name="se_ref_date" id="se_ref_date">
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="amount_numeric" class="form-label">Amount (Numeric) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="amount_numeric" id="amount_numeric" placeholder="Enter amount" step="0.01" required>
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="amount_words" class="form-label">Amount (Words)</label>
                    <input type="text" class="form-control" name="amount_words" id="amount_words" placeholder="Enter amount in words">
                  </div>
                  <div class="col-12 col-md-12">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Enter subject">
                  </div>
                </div>

                <h6 class="mt-4">Items</h6>
                <div id="items-container" style="margin-top: 10px;">
                    <div class="item-row d-flex align-items-end gap-2 mb-3 flex-wrap border-top pt-3 border-bottom pb-3">
                        <div style="flex: 1; min-width: 150px;">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-control category-select" name="items[0][category]" required>
                                <option value="">Select Category</option>
                                <?php
                                include 'services/db_config.php';
                                try {
                                    $query = "SELECT DISTINCT category FROM public.tbl_workorder_items";
                                    $stmt = $pdo->query($query);
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$row['category']}'>{$row['category']}</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<option value=''>Error loading categories: " . htmlspecialchars($e->getMessage()) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <label class="form-label">Item No. <span class="text-danger">*</span></label>
                            <select class="form-control item-select" name="items[0][id]" required disabled>
                                <option value="">Select Item</option>
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

                <div class="col-12">
                  <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Create Work Order</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include 'includes/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include 'includes/footer-src-files.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Function to update item fields based on selection
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

      // Function to load items based on selected category
      function loadItemsForCategory(categorySelect, itemSelect) {
        const category = categorySelect.value;
        itemSelect.disabled = true;
        itemSelect.innerHTML = '<option value="">Select Item</option>';

        if (category) {
          fetch('services/get_items_by_category.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `category=${encodeURIComponent(category)}`
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                data.items.forEach(item => {
                  const option = document.createElement('option');
                  option.value = item.id;
                  option.textContent = `${item.item_no}`;
                  option.setAttribute('data-description', item.description);
                  option.setAttribute('data-unit', item.unit);
                  option.setAttribute('data-rate', item.rate_numeric);
                  itemSelect.appendChild(option);
                });
                itemSelect.disabled = false;
              } else {
                itemSelect.innerHTML = `<option value="">Error: ${data.error}</option>`;
              }
            })
            .catch(error => {
              itemSelect.innerHTML = `<option value="">Error loading items: ${error.message}</option>`;
            });
        }
      }

      // Attach event listeners to existing category and item selects
      document.querySelectorAll('.category-select').forEach(categorySelect => {
        const row = categorySelect.closest('.item-row');
        const itemSelect = row.querySelector('.item-select');
        categorySelect.addEventListener('change', () => loadItemsForCategory(categorySelect, itemSelect));
      });

      document.querySelectorAll('.item-select').forEach(select => {
        select.addEventListener('change', () => updateItemFields(select));
      });

      // Handle adding new item rows
      let itemIndex = 1;
      document.querySelector('.add-item-btn').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'item-row row gy-2 gy-md-3 mb-3 align-items-end';
        newRow.innerHTML = `
      <div class="col-12 col-md-2">
        <label class="form-label">Category <span class="text-danger">*</span></label>
        <select class="form-control category-select" name="items[${itemIndex}][category]" required>
          <option value="">Select Category</option>
          <?php
          try {
            $query = "SELECT DISTINCT category FROM public.tbl_workorder_items";
            $stmt = $pdo->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='{$row['category']}'>{$row['category']}</option>";
            }
          } catch (PDOException $e) {
            echo "<option value=''>Error loading categories: " . htmlspecialchars($e->getMessage()) . "</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-12 col-md-2">
        <label class="form-label">Item No. <span class="text-danger">*</span></label>
        <select class="form-control item-select" name="items[${itemIndex}][id]" required disabled>
          <option value="">Select Item</option>
        </select>
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label">Description</label>
        <input type="text" class="form-control item-description" name="items[${itemIndex}][description]" placeholder="Enter description" readonly>
      </div>
      <div class="col-12 col-md-2">
        <label class="form-label">Quantity <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="items[${itemIndex}][quantity]" placeholder="Qty" min="0.01" step="any" required>
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

        // Attach event listener to new category select
        const newCategorySelect = newRow.querySelector('.category-select');
        const newItemSelect = newRow.querySelector('.item-select');
        newCategorySelect.addEventListener('change', () => loadItemsForCategory(newCategorySelect, newItemSelect));

        // Attach event listener to new item select
        newItemSelect.addEventListener('change', () => updateItemFields(newItemSelect));

        // Attach event listener to remove button
        newRow.querySelector('.remove-item-btn').addEventListener('click', () => {
          newRow.remove();
        });

        itemIndex++;
      });

      // Handle removing item rows
      document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
          e.target.closest('.item-row').remove();
        }
      });
    });
  </script>
</body>

</html>