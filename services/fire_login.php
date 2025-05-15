<?php
session_start();

// Initialize error logging to a file in the same directory
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log'); // Log to error.log in same directory
ini_set('display_errors', 0); // Disable displaying errors to users (for production)

// Log start of script for debugging
// error_log("Login script started at " . date('Y-m-d H:i:s'));

require_once 'db_config.php'; // Include database configuration

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password are required.";
        error_log("Validation failed: Username or password empty");
        header("Location: ../login.php");
        exit();
    }

    // Prepare and execute query to fetch user data
    try {
        $stmt = $pdo->prepare("SELECT id, username, password, first_name, last_name, email, is_active, contact, picture, survey_app_password, is_android_user, department_id, designation_id, role_id, created_at FROM public.tbl_user WHERE username = ?");
        $stmt->execute([$username]);

        // Check for query errors
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] !== '00000') {
            error_log("SQL Error: " . print_r($errorInfo, true));
            $_SESSION['error'] = "Database query error.";
            header("Location: ../login.php");
            exit();
        }

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug: Check if user was found
        if ($user === false) {
            error_log("No user found for username: $username");
            $_SESSION['error'] = "No user found with that username.";
            header("Location: ../login.php");
            exit();
        }

        // Debug: Log user data (exclude sensitive fields like password)
        // error_log("User found: " . print_r([
        //     'username' => $user['username'],
        //     'is_active' => $user['is_active'],
        //     'role_id' => $user['role_id']
        // ], true));

        // Verify password and account status with simple comparison
        if ($password === $user['password'] && $user['is_active']) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

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

            // Debug: Log successful login
            error_log("Login successful for username: $username, role_id: {$user['role_id']}");

            // Redirect based on role_id
            if ($user['role_id'] == 1) {
                header("Location: ../work_orders_issuance.php");
            } elseif ($user['role_id'] == 2) {
                header("Location: ../mb_entries_verification.php");
            } elseif ($user['role_id'] == 4) {
                header("Location: ../interim_payment_bills.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            // Debug: Log why login failed
            $reason = [];
            if ($password !== $user['password']) {
                $reason[] = "Password mismatch";
            }
            if (!$user['is_active']) {
                $reason[] = "Account inactive";
            }
            error_log("Login failed for username: $username, reasons: " . implode(", ", $reason));
            $_SESSION['error'] = "Invalid username, password, or inactive account.";
            header("Location: ../login.php");
            exit();
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: ../login.php");
        exit();
    }
} else {
    // If not a POST request, redirect to login page
    error_log("Non-POST request received");
    header("Location: ../login.php");
    exit();
}
?>