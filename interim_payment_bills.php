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

  // Combine first_name and last_name to match contractor_name
  $contractorFullName = trim($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']);

  try {
    // Query to fetch work orders where contractor_name matches the logged-in user's full name
    $stmt = $pdo->prepare("SELECT id, cost, date_of_commencement, time_limit_months, created_at, contractor_id, contractor_name, ref_no, ref_date, se_ref_no, se_ref_date, amount_numeric, amount_words, is_issued, subject 
                           FROM public.tbl_workorders 
                           WHERE contractor_name = ?");
    $stmt->execute([$contractorFullName]);
    $workOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: Log the query result
    error_log("Work orders fetched for contractor: $contractorFullName, count: " . count($workOrders));
  } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $workOrders = [];
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
              <h5 class="card-title">Interim Payment Bills</h5>

              <!-- Work Orders Table -->
              <div class="table-responsive">
                <!-- Single Table -->
                <table class="table table-bordered table-striped mt-3" style="font-size: 14px">
                  <thead class="table-primary">
                    <tr>
                      <th>Sr</th>
                      <th>Work Order</th>
                      <th>Date of Commencement</th>
                      <th>Name of Contractor</th>
                      <th>View Details</th>
                      <th>Bill Status</th>
                      <th>Action</th> <!-- Added Action column to thead -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($workOrders)): ?>
                      <tr>
                        <td colspan="7" class="text-center">No work orders assigned to you.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($workOrders as $index => $workOrder): ?>
                        <tr>
                          <td><?php echo $index + 1; ?></td>
                          <td>WO-<?php echo sprintf('%03d', $workOrder['id']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['date_of_commencement']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['contractor_name']); ?></td>
                          <td>
                            <a href="#" class="badge bg-primary ms-2 text-white text-decoration-none generate-pdf" data-work-order-id="<?php echo $workOrder['id']; ?>">
                              View Pdf
                            </a>
                          </td>
                          <td>
                            <div class="form-check form-switch">
                              <input class="form-check-input issuance-switch" type="checkbox" id="issuanceSwitch-<?php echo $workOrder['id']; ?>" data-work-order-id="<?php echo $workOrder['id']; ?>" <?php echo $workOrder['is_issued'] ? 'checked' : ''; ?> <?php echo $workOrder['is_issued'] ? 'disabled' : ''; ?>>
                              <label class="form-check-label" for="issuanceSwitch-<?php echo $workOrder['id']; ?>">
                                <?php echo $workOrder['is_issued'] ? 'Applied' : 'Not Applied'; ?>
                              </label>
                            </div>
                          </td>
                          <td>
                            <a href="apply_bill_form.php" class="badge bg-success ms-2 text-white text-decoration-none generate-apple" data-work-order-id="<?php echo $workOrder['id']; ?>">
                              Apply
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
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

</html>