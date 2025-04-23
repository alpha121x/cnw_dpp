<?php
// Login processing script
session_start();
require_once '../config/database.php';
require_once 'functions.php';
require_once 'auth.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no validation errors, attempt login
    if (empty($errors)) {
        if (loginUser($email, $password)) {
            // Redirect to dashboard on successful login
            header('Location: ../index.php?page=dashboard');
            exit;
        } else {
            $_SESSION['error_message'] = 'Invalid email or password';
            header('Location: ../index.php?page=login');
            exit;
        }
    } else {
        // Store errors in session and redirect back to login
        $_SESSION['error_message'] = implode('<br>', $errors);
        header('Location: ../index.php?page=login');
        exit;
    }
} else {
    // If not POST request, redirect to login page
    header('Location: ../index.php?page=login');
    exit;
}
?>