<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/public/gop.ico" type="image/x-icon">
    <title>Construction Project Management System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <?php if(isLoggedIn()): ?>
        <header>
            <div class="logo">
                <h1>CPMS</h1>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <span class="role-badge"><?php echo getRoleText($_SESSION['user_role']); ?></span>
                <a href="index.php?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>
        
        <nav class="sidebar">
            <ul>
                <li><a href="index.php?page=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                
                <?php if(hasAnyRole(['admin', 'sdo'])): ?>
                <li><a href="index.php?page=work_orders"><i class="fas fa-file-contract"></i> Work Orders</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['contractor'])): ?>
                <li><a href="index.php?page=work_orders"><i class="fas fa-briefcase"></i> My Projects</a></li>
                <li><a href="index.php?page=bills"><i class="fas fa-file-invoice-dollar"></i> My Bills</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['subeng'])): ?>
                <li><a href="index.php?page=measurements"><i class="fas fa-ruler"></i> Measurements</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['sdo', 'sdc'])): ?>
                <li><a href="index.php?page=bills"><i class="fas fa-file-invoice"></i> Bills</a></li>
                <li><a href="index.php?page=vouchers"><i class="fas fa-receipt"></i> Vouchers</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['xen', 'dao'])): ?>
                <li><a href="index.php?page=vouchers"><i class="fas fa-receipt"></i> Vouchers</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['sdo', 'sdc'])): ?>
                <li><a href="index.php?page=cheques"><i class="fas fa-money-check"></i> Cheques</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['accounts'])): ?>
                <li><a href="index.php?page=forms"><i class="fas fa-file-alt"></i> Form Management</a></li>
                <?php endif; ?>
                
                <?php if(hasAnyRole(['treasury'])): ?>
                <li><a href="index.php?page=cheques"><i class="fas fa-money-check-alt"></i> Cheque Processing</a></li>
                <?php endif; ?>
                
                <li><a href="index.php?page=profile"><i class="fas fa-user"></i> My Profile</a></li>
            </ul>
        </nav>
        <?php endif; ?>
        
        <main class="content">
            <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert success">
                <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']);
                ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert error">
                <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']);
                ?>
            </div>
            <?php endif; ?>