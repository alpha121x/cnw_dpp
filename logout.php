<?php
session_start();
// Unset all session variables
$_SESSION = [];
// Destroy the session
session_destroy();
// Redrect to login page
header("Location: ../login.php");
exit();
?>