<div class="login-container">
    <div class="login-header">
        <h2>Construction Project Management System</h2>
        <p>Please sign in to continue</p>
    </div>
    
    <form class="login-form" method="post" action="includes/process_login.php">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>
</div>

<?php
// If this is a login attempt, process it
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    if (loginUser($email, $password)) {
        // Redirect to dashboard on successful login
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $_SESSION['error_message'] = 'Invalid email or password';
    }
}
?>