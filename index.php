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
      <h1>Welcome - Digitized Payment System</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard Modules</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <?php
    $role_id = $_SESSION['user']['role_id'] ?? null;
    ?>

    <section class="section dashboard">
        <div class="row g-3">

            <?php if ($role_id == 1): // Admin ?>
                <!-- Work Order Creation Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="card custom-info-card work-order-creation">
                        <div class="card-body">
                            <h5 class="card-title text-white">Work Order Creation</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark-plus"></i>
                                </div>
                                <div class="ps-3 text-white">
                                    <p>Create and manage work orders for projects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Work Order Issuance Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="card custom-info-card work-order-issuance">
                        <div class="card-body">
                            <h5 class="card-title text-white">Work Order Issuance</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-check"></i>
                                </div>
                                <div class="ps-3 text-white">
                                    <p>Issue work orders to contractors and teams.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($role_id == 1 || $role_id == 2): // Admin or Sub Engineer ?>
                <!-- MB Entries Verification Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="card custom-info-card mb-verification">
                        <div class="card-body" >
                            <h5 class="card-title text-white">MB Entries Verification</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <div class="ps-3 text-white">
                                    <p>Verify measurement book entries for accuracy.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($role_id == 1 || $role_id == 4): // Admin or Contractor ?>
                <!-- Interim Payment Bill Submission Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="card custom-info-card payment-bill">
                        <div class="card-body">
                            <h5 class="card-title text-white">Payment Bill Submission</h5>
                            <div class="d-flex align-items-center mb-3">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div class="ps-3">
                                    <p>Contractors submit interim payment bills for review.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </section>


  </main><!-- End #main -->

  <?php include 'includes/footer.php'; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include 'includes/footer-src-files.php'; ?>

</body>

</html>