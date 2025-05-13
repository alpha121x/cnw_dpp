<?php
// Database connection settings
$host = '172.20.82.25';
$dbname = 'db_cnw_dpp'; // Your database name
$user = 'postgres'; // Your PostgreSQL username
$password = 'diamondx'; // Your PostgreSQL password
$port = '5432'; // Default PostgreSQL port

// $host = 'localhost';
// $dbname = 'db_cnw_dpp'; // Your database name
// $user = 'postgres'; // Your PostgreSQL username
// $password = '1234'; // Your PostgreSQL password
// $port = '5433'; // Default PostgreSQL port

// DSN (Data Source Name) for PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password);
    // echo "Connected to the database successfully!";
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>