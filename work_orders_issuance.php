<?php
require_once 'auth.php';
require_once 'services/db_config.php'; // Database configuration

// Redirect to login if not authenticated
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
  header("Location: login.php");
  exit();
}

// Fetch work orders with contractor details
try {
  $stmt = $pdo->query("
        SELECT wo.id, wo.cost, wo.date_of_commencement, wo.time_limit, wo.contractor_id, c.name AS contractor_name
        FROM public.tbl_work_orders wo
        LEFT JOIN public.tbl_contractors c ON wo.contractor_id = c.id
    ");
  $work_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $_SESSION['error'] = "Error fetching work orders: " . $e->getMessage();
}

// Fetch contractors for dropdown
try {
  $stmt = $pdo->query("SELECT id, name FROM public.tbl_contractors");
  $contractors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $_SESSION['error'] = "Error fetching contractors: " . $e->getMessage();
}
?>

<?php
$creationSuccess = '';
if (isset($_SESSION['success'])) {
  $creationSuccess = $_SESSION['success'];
  unset($_SESSION['success']); // prevent repeat on refresh
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
              <table id="workOrderTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr</th>
                    <th>Work Order</th>
                    <th>Date of Commenecement</th>
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
                        <td>
                          <?php if ($order['contractor_id']): ?>
                            <?php echo htmlspecialchars($order['contractor_name']); ?>
                          <?php else: ?>
                            <select class="form-select form-select-sm contractor-select" data-work-order-id="<?php echo $order['id']; ?>" name="contractor_id" required>
                              <option value="" disabled selected>Select Contractor</option>
                              <?php foreach ($contractors as $contractor): ?>
                                <option value="<?php echo $contractor['id']; ?>" data-contractor-name="<?php echo htmlspecialchars($contractor['name']); ?>">
                                  <?php echo htmlspecialchars($contractor['name']); ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if ($order['contractor_id']): ?>
                            <span class="badge bg-success ms-2">Issued</span>
                          <?php else: ?>
                            <span class="badge bg-warning text-dark ms-2">Not Issued</span>
                          <?php endif; ?>
                        </td>

                        <td>
                          <a href="assets/dummy_files/files.pdf" target="_blank" class="badge bg-primary ms-2 text-white text-decoration-none">View Pdf</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5">No work orders found.</td>
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
</body>

</html>

<?php if (!empty($creationSuccess)): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toastEl = document.getElementById("successToast");
      const toastBody = document.getElementById("successToastMessage");

      toastBody.textContent = <?php echo json_encode($creationSuccess); ?>;
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    });
  </script>
<?php endif; ?>