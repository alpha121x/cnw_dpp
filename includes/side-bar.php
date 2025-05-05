<?php
 $role_id = $_SESSION['user']['role_id'] ?? null;
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link " href="index.php">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <?php if ($role_id == 1): // Admin ?>
      <li class="nav-item">
        <a class="nav-link" href="create_work_orders.php">
          <i class="bi bi-boxes"></i><span>Create Work Orders</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="work_orders_issuance.php">
          <i class="bi bi-boxes"></i><span>Work Orders Issuance</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($role_id == 2): // Sub Engineer ?>
      <li class="nav-item">
        <a class="nav-link" href="mb_entries_verification.php">
          <i class="bi bi-boxes"></i><span>MB Entries Verification</span>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($role_id == 4): // Contractor ?>
      <li class="nav-item">
        <a class="nav-link" href="interim_payment_bills.php">
          <i class="bi bi-boxes"></i><span>Interim Payment Bills</span>
        </a>
      </li>
    <?php endif; ?>

  </ul>

</aside><!-- End Sidebar -->
