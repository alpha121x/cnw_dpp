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
  <meta content="payment system, digitization, work order, mb entries, interim payment" name="keywords">

  <?php include 'includes/header-files.php'; ?>
</head>

<body>

  <?php include 'includes/header.php'; ?>

  <?php include 'includes/side-bar.php'; ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>C&W - Digitized Payment System</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Work Order Creation Card -->
        <div class="col-lg-3 col-md-6">
          <div class="card info-card bg-primary text-white">
            <div class="card-body">
              <h5 class="card-title">Work Order Creation</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-white text-primary">
                  <i class="bi bi-file-earmark-plus"></i>
                </div>
                <div class="ps-3">
                  <p>Create and manage work orders for projects.</p>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Work Order Creation Card -->

        <!-- Work Order Issuance Card -->
        <div class="col-lg-3 col-md-6">
          <div class="card info-card bg-success text-white">
            <div class="card-body">
              <h5 class="card-title">Work Order Issuance</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-white text-success">
                  <i class="bi bi-file-check"></i>
                </div>
                <div class="ps-3">
                  <p>Issue work orders to contractors and teams.</p>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Work Order Issuance Card -->

        <!-- MB Entries Verification Card -->
        <div class="col-lg-3 col-md-6">
          <div class="card info-card bg-warning text-white">
            <div class="card-body">
              <h5 class="card-title">MB Entries Verification</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-white text-warning">
                  <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="ps-3">
                  <p>Verify measurement book entries for accuracy.</p>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End MB Entries Verification Card -->

        <!-- Interim Payment Bill Submission Card -->
        <div class="col-lg-3 col-md-6">
          <div class="card info-card bg-info text-white">
            <div class="card-body">
              <h5 class="card-title">Payment Bill Submission</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-white text-info">
                  <i class="bi bi-receipt"></i>
                </div>
                <div class="ps-3">
                  <p>Contractors submit interim payment bills for review.</p>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End Interim Payment Bill Submission Card -->

      </div>
    </section>

  </main><!-- End #main -->

  <?php include 'includes/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include 'includes/footer-src-files.php'; ?>

</body>

</html>