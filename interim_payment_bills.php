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
                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Cost</th>
                      <th>Date of Commencement</th>
                      <th>Time Limit (Months)</th>
                      <th>Created At</th>
                      <th>Contractor Name</th>
                      <th>Reference No</th>
                      <th>Reference Date</th>
                      <th>SE Reference No</th>
                      <th>SE Reference Date</th>
                      <th>Amount (Numeric)</th>
                      <th>Amount (Words)</th>
                      <th>Is Issued</th>
                      <th>Subject</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($workOrders)): ?>
                      <tr>
                        <td colspan="14" class="text-center">No work orders assigned to you.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($workOrders as $workOrder): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($workOrder['id']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['cost']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['date_of_commencement']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['time_limit_months']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['created_at']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['contractor_name']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['ref_no']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['ref_date']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['se_ref_no']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['se_ref_date']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['amount_numeric']); ?></td>
                          <td><?php echo htmlspecialchars($workOrder['amount_words']); ?></td>
                          <td><?php echo $workOrder['is_issued'] ? 'Yes' : 'No'; ?></td>
                          <td><?php echo htmlspecialchars($workOrder['subject']); ?></td>
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