<?php
session_start();
require_once 'services/db_config.php'; // From artifact 026eed84-6bfd-4910-9bac-46c7d61d830f

// Fetch role name if user is logged in
$role_name = 'Guest';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && isset($_SESSION['user']['role_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT name FROM public.tbl_roles WHERE id = ?");
        $stmt->execute([$_SESSION['user']['role_id']]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        $role_name = $role ? $role['name'] : 'Unknown';
    } catch (PDOException $e) {
        $role_name = 'Error';
    }
}

// Set default profile picture if none exists
$profile_picture = 'assets/img/noImg.png';

// Set full name
$full_name = isset($_SESSION['user']['first_name']) && isset($_SESSION['user']['last_name']) 
    ? htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']) 
    : 'Guest User';
$short_name = isset($_SESSION['user']['first_name']) 
    ? htmlspecialchars(substr($_SESSION['user']['first_name'], 0, 1) . '. ' . $_SESSION['user']['last_name']) 
    : 'G. User';
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="dashboard.html" class="logo d-flex align-items-center">
    <img src="assets/img/logo_cnw.png" alt="">
    <span class="d-none d-lg-block">C&W - DPP</span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
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
          <span><?php echo htmlspecialchars($role_name); ?></span>
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