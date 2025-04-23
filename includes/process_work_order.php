<?php
// Start session and include database connection
session_start();
require_once '../config/database.php';
require_once 'functions.php';

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Sanitize and validate input data
        $project_name = sanitize($_POST['project_name']);
        $contractor_id = filter_var($_POST['contractor_id'], FILTER_VALIDATE_INT);
        $issue_date = sanitize($_POST['issue_date']);
        $completion_date = sanitize($_POST['completion_date']);
        $contract_value = filter_var($_POST['contract_value'], FILTER_VALIDATE_FLOAT);
        $location = sanitize($_POST['location']);
        $description = sanitize($_POST['description']);
        $terms = sanitize($_POST['terms']);
        
        // Validate required fields
        if (empty($project_name) || !$contractor_id || empty($issue_date) || 
            empty($completion_date) || !$contract_value || empty($location) || 
            empty($description)) {
            throw new Exception('All required fields must be filled');
        }

        // Validate dates
        if (strtotime($completion_date) <= strtotime($issue_date)) {
            throw new Exception('Completion date must be after issue date');
        }

        // Insert work order
        $stmt = $pdo->prepare("
            INSERT INTO work_orders (
                project_name, contractor_id, issue_date, 
                completion_date, contract_value, location, 
                description, terms, created_by, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $project_name,
            $contractor_id,
            $issue_date,
            $completion_date,
            $contract_value,
            $location,
            $description,
            $terms,
            $_SESSION['user_id']
        ]);

        // Get the last inserted work order ID
        $work_order_id = $pdo->lastInsertId();

        // Process scope items
        if (!empty($_POST['scope_items']) && !empty($_POST['quantities']) && !empty($_POST['units'])) {
            $scope_items = $_POST['scope_items'];
            $quantities = $_POST['quantities'];
            $units = $_POST['units'];

            // Validate array lengths match
            if (count($scope_items) !== count($quantities) || count($quantities) !== count($units)) {
                throw new Exception('Invalid scope items data');
            }

            // Prepare statement for scope items
            $stmt = $pdo->prepare("
                INSERT INTO scope_items (
                    work_order_id, description, quantity, unit
                ) VALUES (?, ?, ?, ?)
            ");

            // Insert each scope item
            for ($i = 0; $i < count($scope_items); $i++) {
                $description = sanitize($scope_items[$i]);
                $quantity = filter_var($quantities[$i], FILTER_VALIDATE_FLOAT);
                $unit = sanitize($units[$i]);

                if (empty($description) || !$quantity || empty($unit)) {
                    throw new Exception('Invalid scope item data');
                }

                $stmt->execute([
                    $work_order_id,
                    $description,
                    $quantity,
                    $unit
                ]);
            }
        }

        // Commit transaction
        $pdo->commit();
        
        // Set success message
        $_SESSION['success_message'] = 'Work order created successfully';
        header('Location: ../index.php?page=work_orders');
        exit;

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        
        // Set error message
        $_SESSION['error_message'] = 'Error creating work order: ' . $e->getMessage();
        header('Location: ../index.php?page=work_orders');
        exit;
    }
} else {
    // Invalid request
    $_SESSION['error_message'] = 'Invalid request';
    header('Location: ../index.php?page=work_orders');
    exit;
}
?>