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
      <div class="row">
        <!-- Cards -->
        <div class="col-12">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="card" style="background-color: #6c757d; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Total Work Orders</h5>
                  <h3 class="card-text"><?php echo $total_count; ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card" style="background-color: #28a745; color: white;">
                <div class="card-body">
                  <h5 class="card-title">Issued Work Orders</h5>
                  <h3 class="card-text"><?php echo $issued_count; ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card" style="background-color: #ffc107; color: black;">
                <div class="card-body">
                  <h5 class="card-title">Not Issued Work Orders</h5>
                  <h3 class="card-text"><?php echo $not_issued_count; ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>
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
                          <div class="form-check form-switch">
                            <input class="form-check-input issuance-switch" type="checkbox" id="issuanceSwitch-<?php echo $order['id']; ?>" data-work-order-id="<?php echo $order['id']; ?>" <?php echo $order['is_issued'] ? 'checked' : ''; ?> <?php echo $order['is_issued'] ? 'disabled' : ''; ?>>
                            <label class="form-check-label" for="issuanceSwitch-<?php echo $order['id']; ?>">
                              <?php echo $order['is_issued'] ? 'Issued' : 'Not Issued'; ?>
                            </label>
                          </div>
                        </td>
                        <td>
                          <a href="#" class="badge bg-primary ms-2 text-white text-decoration-none generate-pdf" data-work-order-id="<?php echo $order['id']; ?>">View Pdf</a>
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
document.addEventListener('DOMContentLoaded', function() {
  // Show success toast if exists
  <?php if (!empty($creationSuccess)): ?>
    const toastEl = document.getElementById("successToast");
    const toastBody = document.getElementById("successToastMessage");
    toastBody.textContent = <?php echo json_encode($creationSuccess); ?>;
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
  <?php endif; ?>

  // Handle issuance status update
  document.querySelectorAll('.issuance-switch').forEach(switchElement => {
    if (!switchElement.disabled) {
      switchElement.addEventListener('change', function() {
        const workOrderId = this.getAttribute('data-work-order-id');
        const isIssued = this.checked;

        fetch('services/update_workorder_issuance.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `work_order_id=${workOrderId}&is_issued=${isIssued}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const toastEl = document.getElementById("successToast");
            const toastBody = document.getElementById("successToastMessage");
            toastBody.textContent = "Issuance status updated successfully.";
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Disable the switch after successful update
            this.disabled = true;
            const label = this.nextElementSibling;
            label.textContent = isIssued ? 'Issued' : 'Not Issued';

            setTimeout(() => location.reload(), 1500);
          } else {
            const toastEl = document.getElementById("errorToast");
            const toastBody = document.getElementById("errorToastMessage");
            toastBody.textContent = data.error || "Failed to update issuance status.";
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Revert to original value if update fails
            this.checked = !isIssued;
          }
        })
        .catch(error => {
          const toastEl = document.getElementById("errorToast");
          const toastBody = document.getElementById("errorToastMessage");
          toastBody.textContent = "Error updating issuance status: " + error.message;
          const toast = new bootstrap.Toast(toastEl);
          toast.show();

          // Revert to original value if update fails
          this.checked = !isIssued;
        });
      });
    }
  });

  // Handle PDF generation
  document.querySelectorAll('.generate-pdf').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const workOrderId = this.getAttribute('data-work-order-id');

      fetch('services/generate_workorder_pdf.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `work_order_id=${workOrderId}`
      })
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.blob();
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `work_order_${workOrderId}.pdf`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        const toastEl = document.getElementById("errorToast");
        const toastBody = document.getElementById("errorToastMessage");
        toastBody.textContent = "Error generating PDF: " + error.message;
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
      });
    });
  });
});
</script>

</body>

</html>