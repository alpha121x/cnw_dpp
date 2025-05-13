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

  // Calculate counts
  $total_count = count($work_orders);
  $issued_count = count(array_filter($work_orders, fn($order) => $order['is_issued']));
  $not_issued_count = $total_count - $issued_count;
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
        <div class="row g-2 mb-3">
            <!-- Total Work Orders Card -->
            <div class="col-md-4">
                <div class="custom-work-card custom-work-card-total">
                    <div class="custom-work-card-body">
                        <i class="fas fa-clipboard-list custom-work-card-icon"></i>
                        <div class="custom-work-card-content">
                            <h5 class="custom-work-card-title">Total Work Orders</h5>
                            <h3 class="custom-work-card-text">150</h3> <!-- Replace with <?php echo $total_count; ?> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Issued Work Orders Card -->
            <div class="col-md-4">
                <div class="custom-work-card custom-work-card-issued">
                    <div class="custom-work-card-body">
                        <i class="fas fa-check-circle custom-work-card-icon"></i>
                        <div class="custom-work-card-content">
                            <h5 class="custom-work-card-title">Issued Work Orders</h5>
                            <h3 class="custom-work-card-text">120</h3> <!-- Replace with <?php echo $issued_count; ?> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Not Issued Work Orders Card -->
            <div class="col-md-4">
                <div class="custom-work-card custom-work-card-not-issued">
                    <div class="custom-work-card-body">
                        <i class="fas fa-exclamation-circle custom-work-card-icon"></i>
                        <div class="custom-work-card-content">
                            <h5 class="custom-work-card-title">Not Issued Work Orders</h5>
                            <h3 class="custom-work-card-text">30</h3> <!-- Replace with <?php echo $not_issued_count; ?> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <!-- Single Table -->
                        <table class="table table-bordered table-striped mt-3" style="font-size: 14px">
                            <thead class="table-primary">
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
                                            <div class="form-check form-switch">
                                                <input class="form-check-input issuance-switch" type="checkbox" id="issuanceSwitch-<?php echo $order['id']; ?>" data-work-order-id="<?php echo $order['id']; ?>" <?php echo $order['is_issued'] ? 'checked' : ''; ?> <?php echo $order['is_issued'] ? 'disabled' : ''; ?>>
                                                <label class="form-check-label" for="issuanceSwitch-<?php echo $order['id']; ?>">
                                                    <?php echo $order['is_issued'] ? 'Issued' : 'Not Issued'; ?>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#"
                                               class="badge bg-primary ms-2 text-white text-decoration-none generate-pdf <?php echo $order['is_issued'] ? '' : 'disabled'; ?>"
                                               data-work-order-id="<?php echo $order['id']; ?>"
                                                <?php echo $order['is_issued'] ? '' : 'aria-disabled="true" style="pointer-events: none; opacity: 0.5;"'; ?>>
                                                View Pdf
                                            </a>
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
                </div>
            </div>
        </div>
    </section>

  </main><!-- End #main -->

  <?php include 'includes/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include 'includes/footer-src-files.php'; ?>

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

</body>

</html>