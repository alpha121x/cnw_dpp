<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Login - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body{
            background-image: url("./assets/img/login_bg.jpg");
            background-size: cover; /* Makes the background image cover the entire screen */
            background-position: center; /* Ensures the image is centered */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            height: 100vh; /* Ensures the body takes up at least the full height of the viewport */
        }
    </style>
</head>

<body>

  <main>
    <div class="container">

        <!-- Login 5 - Bootstrap Brain Component -->
        <section class="p-3 p-md-4 p-xl-5 mt-5">
            <div class="container">
                <div class="card border-light-subtle shadow-sm">
                    <div class="row g-0">
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="col-10 col-xl-8 pb-3 pt-3 text-center">
                                    <img class="img-fluid rounded mb-4" loading="lazy" src="./assets/img/cnw_logo.png" width="245" height="80" alt="BootstrapBrain Logo">
                                    <hr class="border-primary-subtle mb-4">
                                    <h4 class="mb-4">Digitization of Payment Procedures</h4>
                                    <p class="m-0">We write words, take photos, make videos, and interact with artificial intelligence.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card-body p-3 p-md-4 p-xl-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-5">
                                            <h3>Log in</h3>
                                        </div>
                                    </div>
                                </div>
                                <form action="#!">
                                    <div class="row gy-3 gy-md-4 overflow-hidden">
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password" value="" required>
                                        </div>
<!--                                        <div class="col-12">-->
<!--                                            <div class="form-check">-->
<!--                                                <input class="form-check-input" type="checkbox" value="" name="remember_me" id="remember_me">-->
<!--                                                <label class="form-check-label text-secondary" for="remember_me">-->
<!--                                                    Keep me logged in-->
<!--                                                </label>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button class="btn bsb-btn-xl btn-primary" type="submit">Log in now</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
<!--                                <div class="row">-->
<!--                                    <div class="col-12">-->
<!--                                        <hr class="mt-5 mb-4 border-secondary-subtle">-->
<!--                                        <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">-->
<!--                                            <a href="#!" class="link-secondary text-decoration-none">Create new account</a>-->
<!--                                            <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>