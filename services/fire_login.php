<?php
session_start();

require_once 'db_config.php'; // Include database configuration

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: ../login.php");
        exit();
    }

    // Prepare and execute query to fetch user data
    try {
        $stmt = $pdo->prepare("SELECT id, username, password, first_name, last_name, email, is_active, contact, picture, survey_app_password, is_android_user, department_id, designation_id, role_id, created_at FROM public.tbl_user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user exists, password matches, and account is active
        if ($user && $password === $user['password'] && $user['is_active']) {
            // Store all user data in session
            $_SESSION['loggedin'] = true;
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'is_active' => $user['is_active'],
                'contact' => $user['contact'],
                'picture' => $user['picture'],
                'survey_app_password' => $user['survey_app_password'],
                'is_android_user' => $user['is_android_user'],
                'department_id' => $user['department_id'],
                'designation_id' => $user['designation_id'],
                'role_id' => $user['role_id'],
                'created_at' => $user['created_at']
            ];

            // Redirect to dashboard
            header("Location: ../index.php");
            exit();
        } else {
            // Invalid credentials or inactive account
            $_SESSION['error'] = "Invalid username, password, or inactive account.";
            header("Location: ../login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: ../login.php");
        exit();
    }
} else {
    // If not a POST request, redirect to login page
    header("Location: ../login.php");
    exit();
}
?>