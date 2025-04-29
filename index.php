<?php 
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>C&W - DPP</title>
  <meta content="Dashboard for Digitization of Payment System" name="description">
  <meta content="payment system, digitization, system monitoring, analytics" name="keywords">

  <?php include 'includes/header-files.php'; ?>
</head>

<body>

  <?php include 'includes/header.php'; ?>

  <?php include 'includes/side-bar.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Payment System Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- System Uptime Card -->
        <div class="col-lg-4 col-md-6">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">System Uptime</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-clock"></i>
                </div>
                <div class="ps-3">
                  <h6>99.98%</h6>
                  <span class="text-success small pt-1 fw-bold">Stable</span>
                  <span class="text-muted small pt-2 ps-1">Last checked: <?php echo date('Y-m-d H:i:s'); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End System Uptime Card -->

        <!-- API Response Time Card -->
        <div class="col-lg-4 col-md-6">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">API Response Time</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-speedometer2"></i>
                </div>
                <div class="ps-3">
                  <h6>250 ms</h6>
                  <span class="text-success small pt-1 fw-bold">Optimal</span>
                  <span class="text-muted small pt-2 ps-1">Average this hour</span>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End API Response Time Card -->

        <!-- Active Users Card -->
        <div class="col-lg-4 col-md-6">
          <div class="card info-card">
            <div class="card-body">
              <h5 class="card-title">Active Users</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6>342</h6>
                  <span class="text-success small pt-1 fw-bold">+5%</span>
                  <span class="text-muted small pt-2 ps-1">Currently online</span>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Active Users Card -->

        <!-- System Alerts Table -->
        <div class="col-12">
          <div class="card system-alerts">
            <div class="card-body">
              <h5 class="card-title">System Alerts</h5>
              <table class="table table-borderless datatable">
                <thead>
                  <tr>
                    <th scope="col">Alert ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Message</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#ALRT001</td>
                    <td><?php echo date('Y-m-d'); ?></td>
                    <td>Security</td>
                    <td>Potential unauthorized access detected</td>
                    <td><span class="badge bg-warning">Under Review</span></td>
                  </tr>
                  <tr>
                    <td>#ALRT002</td>
                    <td><?php echo date('Y-m-d'); ?></td>
                    <td>Performance</td>
                    <td>High API latency detected</td>
                    <td><span class="badge bg-success">Resolved</span></td>
                  </tr>
                  <tr>
                    <td>#ALRT003</td>
                    <td><?php echo date('Y-m-d'); ?></td>
                    <td>Maintenance</td>
                    <td>Scheduled downtime at 02:00 AM</td>
                    <td><span class="badge bg-info">Scheduled</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- End System Alerts Table -->

      </div>
    </section>

  </main><!-- End #main -->

  <?php include 'includes/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include 'includes/footer-src-files.php'; ?>

</body>

</html>