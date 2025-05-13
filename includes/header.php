<?php
session_start();
require_once 'services/db_config.php'; // Database configuration

// Redirect to login if not authenticated or session data is missing
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || !isset($_SESSION['user']['role_id']) || !isset($_SESSION['user']['first_name']) || !isset($_SESSION['user']['last_name'])) {
    header('Location: login.php');
    exit;
}

// Fetch role name based on role_id
try {
    $stmt = $pdo->prepare("SELECT role_name FROM public.tbl_user_role WHERE id = ?");
    $stmt->execute([$_SESSION['user']['role_id']]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    $role_name = $role ? htmlspecialchars($role['role_name']) : 'Unknown Role';
} catch (PDOException $e) {
    $role_name = 'Error Fetching Role';
}

// Set profile picture
$profile_picture = isset($_SESSION['user']['profile_picture']) && !empty($_SESSION['user']['profile_picture']) 
    ? htmlspecialchars($_SESSION['user']['profile_picture']) 
    : 'assets/img/noImg.png';

// Set full name and short name
$full_name = htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']);
$short_name = htmlspecialchars(substr($_SESSION['user']['first_name'], 0, 1) . '. ' . $_SESSION['user']['last_name']);
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn me-2"></i>
        <a href="index.php" class="logo d-flex align-items-center">
          <img src="assets/img/cnw_dpp_logo.png" alt="">
            <!--<span class="d-none d-lg-block">C&W - DPP</span>-->
        </a>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo $profile_picture; ?>" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $short_name; ?></span>
        </a><!-- End Profile Image Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $full_name; ?></h6>
            <span><?php echo $role_name; ?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="services/logout.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->
    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->