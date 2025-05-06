<?php
require_once 'auth.php';
require_once 'services/db_config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
  header("Location: login.php");
  exit();
}

// Fetch work orders with contractor details
try {
  $stmt = $pdo->query("
        SELECT id, cost, date_of_commencement, time_limit_months, created_at, contractor_name, ref_no, ref_date, se_ref_no, se_ref_date, amount_numeric, amount_words, is_issued, subject
        FROM public.tbl_workorders
    ");
  $work_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $_SESSION['error'] = "Error fetching work orders: " . $e->getMessage();
}

$creationSuccess = '';
if (isset($_SESSION['success'])) {
  $creationSuccess = $_SESSION['success'];
  unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>C&W - DPP</title>
  <meta content="Work Order Issuance for Digitization of Payment System" name="description">
  <meta content="work order, issuance, contractor, payment system" name="keywords">

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
              <h5 class="card-title">Work Order Issuance</h5>
              <!-- Toast Container -->
              <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                  <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body" id="successToastMessage"></div>
                </div>
                <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                  <div class="toast-header bg-danger text-white">
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body" id="errorToastMessage"></div>
                </div>
              </div>
              <!-- Tabs -->
              <ul class="nav nav-tabs" id="workOrderTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="total-tab" data-bs-toggle="tab" data-bs-target="#total" type="button" role="tab" aria-controls="total" aria-selected="true">Total Work Orders</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="issued-tab" data-bs-toggle="tab" data-bs-target="#issued" type="button" role="tab" aria-controls="issued" aria-selected="false">Issued Work Orders</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="not-issued-tab" data-bs-toggle="tab" data-bs-target="#not-issued" type="button" role="tab" aria-controls="not-issued" aria-selected="false">Not Issued Work Orders</button>
                </li>
              </ul>
              <div class="tab-content" id="workOrderTabContent">
                <!-- Total Tab -->
                <div class="tab-pane fade show active" id="total" role="tabpanel" aria-labelledby="total-tab">
                  <table class="table table-bordered table-striped mt-3">
                    <thead>
                      <tr>
                        <th>Sr</th>
                        <th>Work Order</th>
                        <th>Date of Commencement</th>
                        <th>Name of Contractor</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($work_orders)): ?>
                        <?php foreach ($work_orders as $index => $order): ?>
                          <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>WO-<?php echo sprintf('%03d', $order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['date_of_commencement']); ?></td>
                            <td><?php echo htmlspecialchars($order['contractor_name']); ?></td>
                            <td>
                              <select class="form-select form-select-sm issuance-select" data-work-order-id="<?php echo $order['id']; ?>" <?php echo $order['is_issued'] ? 'disabled' : ''; ?>>
                                <option value="true" <?php echo $order['is_issued'] ? 'selected' : ''; ?>>Issued</option>
                                <option value="false" <?php echo !$order['is_issued'] ? 'selected' : ''; ?>>Not Issued</option>
                              </select>
                            </td>
                            <td>
                              <a href="assets/dummy_files/files.pdf" target="_blank" class="badge bg-primary ms-2 text-white text-decoration-none">View Pdf</a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6">No work orders found.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <!-- Issued Tab -->
                <div class="tab-pane fade" id="issued" role="tabpanel" aria-labelledby="issued-tab">
                  <table class="table table-bordered table-striped mt-3">
                    <thead>
                      <tr>
                        <th>Sr</th>
                        <th>Work Order</th>
                        <th>Date of Commencement</th>
                        <th>Name of Contractor</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($work_orders)): ?>
                        <?php $index = 0; ?>
                        <?php foreach ($work_orders as $order): ?>
                          <?php if ($order['is_issued']): ?>
                            <tr>
                              <td><?php echo ++$index; ?></td>
                              <td>WO-<?php echo sprintf('%03d', $order['id']); ?></td>
                              <td><?php echo htmlspecialchars($order['date_of_commencement']); ?></td>
                              <td><?php echo htmlspecialchars($order['contractor_name']); ?></td>
                              <td><span class="badge bg-success ms-2">Issued</span></td>
                              <td>
                                <a href="assets/dummy_files/files.pdf" target="_blank" class="badge bg-primary ms-2 text-white text-decoration-none">View Pdf</a>
                              </td>
                            </tr>
                          <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($index == 0): ?>
                          <tr>
                            <td colspan="6">No issued work orders found.</td>
                          </tr>
                        <?php endif; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6">No work orders found.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
                <!-- Not Issued Tab -->
                <div class="tab-pane fade" id="not-issued" role="tabpanel" aria-labelledby="not-issued-tab">
                  <table class="table table-bordered table-striped mt-3">
                    <thead>
                      <tr>
                        <th>Sr</th>
                        <th>Work Order</th>
                        <th>Date of Commencement</th>
                        <th>Name of Contractor</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($work_orders)): ?>
                        <?php $index = 0; ?>
                        <?php foreach ($work_orders as $order): ?>
                          <?php if (!$order['is_issued']): ?>
                            <tr>
                              <td><?php echo ++$index; ?></td>
                              <td>WO-<?php echo sprintf('%03d', $order['id']); ?></td>
                              <td><?php echo htmlspecialchars($order['date_of_commencement']); ?></td>
                              <td><?php echo htmlspecialchars($order['contractor_name']); ?></td>
                              <td><span class="badge bg-warning text-dark ms-2">Not Issued</span></td>
                              <td>
                                <a href="assets/dummy_files/files.pdf" target="_blank" class="badge bg-primary ms-2 text-white text-decoration-none">View Pdf</a>
                              </td>
                            </tr>
                          <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($index == 0): ?>
                          <tr>
                            <td colspan="6">No not issued work orders found.</td>
                          </tr>
                        <?php endif; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6">No work orders found.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
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

<script>
    // Show success toast if exists
    <?php if (!empty($creationSuccess)): ?>
    const toastEl = document.getElementById("successToast");
    const toastBody = document.getElementById("successToastMessage");
    toastBody.textContent = <?php echo json_encode($creationSuccess); ?>;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
  <?php endif; ?>

</script>

</html>