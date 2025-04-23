<?php
// Authentication functions

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Function to authenticate user
function loginUser($email, $password) {
    $user = fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    
    if ($user && $user['password'] === $password) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        
        // Log login activity
        $ip = $_SERVER['REMOTE_ADDR'];
        insertData("INSERT INTO login_logs (user_id, ip_address) VALUES (?, ?)", 
                  [$user['id'], $ip]);
        
        return true;
    }
    return false;
}


// Function to log out user
function logout() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
}

// Function to check if user has specific role
function hasRole($role) {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == $role;
}

// Function to check if user has one of multiple roles
function hasAnyRole($roles = []) {
    if (!isset($_SESSION['user_role'])) return false;
    return in_array($_SESSION['user_role'], $roles);
}

// Get current user details
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    return fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
}
?>