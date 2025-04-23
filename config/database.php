<?php
// Database connection configuration
$db_host = 'localhost';
$db_name = 'construction_mgmt';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Function to execute queries and return results
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

// Function to get a single row from database
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

// Function to get multiple rows from database
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

// Function to insert data and return ID
function insertData($sql, $params = []) {
    global $pdo;
    executeQuery($sql, $params);
    return $pdo->lastInsertId();
}

// Function to update data
function updateData($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->rowCount();
}
?>