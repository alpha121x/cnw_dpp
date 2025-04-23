<?php
// Main entry point for the application
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Route to appropriate page based on URL
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Check if user is logged in
if (!isLoggedIn() && $page != 'login') {
    header('Location: index.php?page=login');
    exit;
}

// Include header
include_once 'includes/header.php';

// Load the requested page
switch ($page) {
    case 'login':
        include 'pages/login.php';
        break;
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'work_orders':
        include 'pages/work_orders.php';
        break;
    case 'measurements':
        include 'pages/measurements.php';
        break;
    case 'bills':
        include 'pages/bills.php';
        break;
    case 'vouchers':
        include 'pages/vouchers.php';
        break;
    case 'cheques':
        include 'pages/cheques.php';
        break;
    case 'profile':
        include 'pages/profile.php';
        break;
    case 'logout':
        logout();
        header('Location: index.php?page=login');
        exit;
        break;
    default:
        include 'pages/error.php';
        break;
}

// Include footer
include_once 'includes/footer.php';
?>