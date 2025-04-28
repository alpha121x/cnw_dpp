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
                    <div class="col-12 col-md-4">
                      <label for="item_name_0" class="form-label">Item Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="items[0][name]" id="item_name_0" placeholder="Enter item name" required>
                    </div>
                    <div class="col-12 col-md-3">
                      <label for="item_quantity_0" class="form-label">Quantity <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" name="items[0][quantity]" id="item_quantity_0" placeholder="Enter quantity" min="1" required>
                    </div>
                    <div class="col-12 col-md-3">
                      <label for="item_rate_0" class="form-label">Rate Quoted <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" name="items[0][rate]" id="item_rate_0" placeholder="Enter rate" step="0.01" required>
                    </div>
                    <div class="col-12 col-md-2">
                      <button type="button" class="btn btn-danger btn-sm remove-item-btn">Remove</button>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <button type="button" class="btn btn-secondary btn-sm" id="add-item-btn">Add Another Item</button>
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
</body>

</html>