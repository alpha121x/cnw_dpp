<?php
require_once 'auth.php';
require_once 'services/db_config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
  header("Location: login.php");
  exit();
}

// Dummy data to simulate database results
$dummy_data = [
  [
    'id' => 1,
    'name' => 'Road Construction MB-001',
    'agency' => 'Public Works Dept',
    'authority' => 'Eng. John Doe',
    'date_of_comm' => '2025-01-15',
    'date_of_comp' => '2025-04-10',
    'date_of_measurement' => '2025-03-20',
    'db_date_time' => '2025-03-20 14:30:00',
    'measurement_values' => json_encode(['length' => 100, 'width' => 5]),
    'unit_id' => 1, // m²
    'measurement_total' => 500.00
  ],
  [
    'id' => 2,
    'name' => 'Bridge Repair MB-002',
    'agency' => 'Infrastructure Agency',
    'authority' => 'Eng. Jane Smith',
    'date_of_comm' => '2025-02-01',
    'date_of_comp' => null,
    'date_of_measurement' => '2025-03-25',
    'db_date_time' => '2025-03-25 09:15:00',
    'measurement_values' => json_encode(['weight' => 2000]),
    'unit_id' => 2, // kg
    'measurement_total' => 2000.00
  ],
  [
    'id' => 3,
    'name' => 'Pipeline Installation MB-003',
    'agency' => 'Water Board',
    'authority' => 'Eng. Alex Brown',
    'date_of_comm' => '2025-03-01',
    'date_of_comp' => '2025-04-15',
    'date_of_measurement' => '2025-04-01',
    'db_date_time' => '2025-04-01 16:45:00',
    'measurement_values' => json_encode(['count' => 50]),
    'unit_id' => 3, // units
    'measurement_total' => 50.00
  ]
];

// Map unit_id to unit names (simulating tbl_units)
$units = [
  1 => 'm²',
  2 => 'kg',
  3 => 'units'
];

// In a real scenario, fetch from database:
/*
try {
    $stmt = $pdo->query("
        SELECT id, name, agency, authority, date_of_comm, date_of_comp, date_of_measurement, 
               db_date_time, measurement_values, unit_id, measurement_total
        FROM public.tb_measurement_book
    ");
    $mb_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching MB entries: " . $e->getMessage();
    $mb_entries = [];
}
*/

// For now, use dummy data
$mb_entries = $dummy_data;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>C&W - DPP</title>
  <meta content="Measurement Book Entries Verification for Digitization of Payment System" name="description">
  <meta content="measurement book, verification, payment system" name="keywords">

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
              <h5 class="card-title">Measurement Book Entries Verification</h5>
              <?php
              if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
              }
              if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                unset($_SESSION['success']);
              }
              ?>
              <table id="mbEntriesTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Agency</th>
                    <th>Date of Measurement</th>
                    <th>Total</th>
                    <th>Details</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($mb_entries)): ?>
                    <?php foreach ($mb_entries as $entry): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($entry['id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['name']); ?></td>
                        <td><?php echo htmlspecialchars($entry['agency']); ?></td>
                        <td><?php echo htmlspecialchars($entry['date_of_measurement']); ?></td>
                        <td><?php echo htmlspecialchars($entry['measurement_total']) . ' ' . htmlspecialchars($units[$entry['unit_id']]); ?></td>
                        <td>
                          <button type="button" class="badge bg-success border-0 view-details-btn" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo $entry['id']; ?>">
                            View Details
                          </button>
                        </td>
                        <td>
                          <button type="button"
                            class="badge bg-success border-0 view-report-btn"
                            data-id="<?php echo $entry['id']; ?>"
                            data-bs-toggle="modal"
                            data-bs-target="#reportsModal">
                            View 
                          </button>

                          <a href="services/generate_mb_pdf.php?id=<?php echo $entry['id']; ?>" class="text-decoration-none" target="_blank">
                            <span class="badge bg-primary me-1">Download</span>
                          </a>
                          <label class="badge bg-warning mb-0" style="cursor: pointer;">
                            Upload
                            <input type="file" name="fileUpload" style="display: none;" onchange="handleFileUpload(this)">
                          </label>
                        </td>

                      </tr>

                      <!-- Details Modal -->
                      <div class="modal fade" id="detailsModal<?php echo $entry['id']; ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?php echo $entry['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailsModalLabel<?php echo $entry['id']; ?>">MB Entry Details - <?php echo htmlspecialchars($entry['name']); ?></h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <p><strong>Authority:</strong> <?php echo htmlspecialchars($entry['authority'] ?: 'N/A'); ?></p>
                              <p><strong>Date of Commencement:</strong> <?php echo htmlspecialchars($entry['date_of_comm']); ?></p>
                              <p><strong>Date of Completion:</strong> <?php echo htmlspecialchars($entry['date_of_comp'] ?: 'Not Completed'); ?></p>
                              <p><strong>DB Entry Date/Time:</strong> <?php echo htmlspecialchars($entry['db_date_time']); ?></p>
                              <p><strong>Measurement Values:</strong> <?php echo htmlspecialchars($entry['measurement_values']); ?></p>
                              <p><strong>Unit:</strong> <?php echo htmlspecialchars($units[$entry['unit_id']]); ?></p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Shared Details Modal -->
                      <div class="modal fade" id="reportsModal" tabindex="-1" aria-labelledby="reportsModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailsModalLabel">MB Entry Report</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <p><strong>Name:</strong> <span id="modalName"></span></p>
                              <p><strong>Authority:</strong> <span id="modalAuthority"></span></p>
                              <p><strong>Commencement:</strong> <span id="modalComm"></span></p>
                              <p><strong>Completion:</strong> <span id="modalComp"></span></p>
                              <p><strong>Measurement Date:</strong> <span id="modalMeasureDate"></span></p>
                              <p><strong>DB Entry Date:</strong> <span id="modalDBDate"></span></p>
                              <p><strong>Values:</strong> <span id="modalValues"></span></p>
                              <p><strong>Total:</strong> <span id="modalTotal"></span></p>
                              <p><strong>Unit:</strong> <span id="modalUnit"></span></p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>

                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7">No measurement book entries found.</td>
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

<script>
  const mbData = <?php echo json_encode($mb_entries); ?>;
  const unitMap = <?php echo json_encode($units); ?>;

  document.querySelectorAll('.view-report-btn').forEach(button => {
    button.addEventListener('click', function() {
      const entryId = this.getAttribute('data-id');
      const entry = mbData.find(e => e.id == entryId);

      if (entry) {
        document.getElementById('modalName').textContent = entry.name || 'N/A';
        document.getElementById('modalAuthority').textContent = entry.authority || 'N/A';
        document.getElementById('modalComm').textContent = entry.date_of_comm || 'N/A';
        document.getElementById('modalComp').textContent = entry.date_of_comp || 'Not Completed';
        document.getElementById('modalMeasureDate').textContent = entry.date_of_measurement || 'N/A';
        document.getElementById('modalDBDate').textContent = entry.db_date_time || 'N/A';
        document.getElementById('modalValues').textContent = entry.measurement_values || '{}';
        document.getElementById('modalTotal').textContent = entry.measurement_total || '0';
        document.getElementById('modalUnit').textContent = unitMap[entry.unit_id] || '';
      }
    });
  });
</script>


</html>