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
              <h5 class="card-title">Work Orders Issuance</h5>
              <table id="workOrderTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sr</th>
                    <th>Work Order</th>
                    <th>Date of Commencement</th>
                    <th>Name of Contractor</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Sample Data -->
                  <tr>
                    <td>1</td>
                    <td>WO-2025-001</td>
                    <td>2025-01-15</td>
                    <td>
                      <select class="form-select form-select-sm contractor-select">
                        <option value="" disabled selected>Select Contractor</option>
                        <option value="ABC Construction">ABC Construction</option>
                        <option value="XYZ Builders">XYZ Builders</option>
                        <option value="Contractor Group">Contractor Group</option>
                        <option value="PQR Contractors">PQR Contractors</option>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-primary btn-sm issue-btn me-1">Issue</button>
                      <a href="/pdfs/work_order_WO-2025-001.pdf" target="_blank" class="btn btn-secondary btn-sm view-pdf-btn">View PDF</a>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>WO-2025-002</td>
                    <td>2025-02-01</td>
                    <td>
                      <select class="form-select form-select-sm contractor-select">
                        <option value="" disabled selected>Select Contractor</option>
                        <option value="ABC Construction">ABC Construction</option>
                        <option value="XYZ Builders">XYZ Builders</option>
                        <option value="Contractor Group">Contractor Group</option>
                        <option value="PQR Contractors">PQR Contractors</option>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-primary btn-sm issue-btn me-1">Issue</button>
                      <a href="/pdfs/work_order_WO-2025-002.pdf" target="_blank" class="btn btn-secondary btn-sm view-pdf-btn">View PDF</a>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>WO-2025-003</td>
                    <td>2025-03-10</td>
                    <td>
                      <select class="form-select form-select-sm contractor-select">
                        <option value="" disabled selected>Select Contractor</option>
                        <option value="ABC Construction">ABC Construction</option>
                        <option value="XYZ Builders">XYZ Builders</option>
                        <option value="Contractor Group">Contractor Group</option>
                        <option value="PQR Contractors">PQR Contractors</option>
                      </select>
                    </td>
                    <td>
                      <button class="btn btn-primary btn-sm issue-btn me-1">Issue</button>
                      <a href="/pdfs/work_order_WO-2025-003.pdf" target="_blank" class="btn btn-secondary btn-sm view-pdf-btn">View PDF</a>
                    </td>
                  </tr>
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
    $(document).ready(function() {
      $('#workOrderTable').DataTable({
        "responsive": true,
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "columnDefs": [
          { "orderable": false, "targets": [3, 4] } // Disable sorting on Contractor and Action columns
        ]
      });

      // Handle Issue button click
      $('#workOrderTable').on('click', '.issue-btn', function() {
        var row = $(this).closest('tr');
        var rowData = $('#workOrderTable').DataTable().row(row).data();
        var workOrder = rowData[1];
        var contractor = row.find('.contractor-select').val();
        
        if (!contractor) {
          alert('Please select a contractor before issuing the work order.');
          return;
        }
        
        alert('Work Order ' + workOrder + ' issued to: ' + contractor);
        // Add your issue handling logic here (e.g., send data to server)
      });
    });
  </script>

</body>

</html>