<?php
// Database connection settings
$host = 'localhost';
$dbname = 'db_cnw_dpp'; // Your database name
$user = 'postgres'; // Your PostgreSQL username
$password = '1234'; // Your PostgreSQL password
$port = '5433'; // Default PostgreSQL port

// DSN (Data Source Name) for PostgreSQL
$dsn = "pgsql:host=$host;dbname=$dbname;port=$port";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connection successful!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
