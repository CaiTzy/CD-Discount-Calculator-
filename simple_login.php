<?php
$page_title = "Admin Login - Smart Enrollment System";
require_once("db_connection.php");

session_start();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT user_id, username, password_hash, full_name, role, is_active 
              FROM Users WHERE username = ? AND is_active = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password (assuming password_hash uses PHP's password_hash)
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Update last login
            $update_query = "UPDATE Users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $user['user_id']);
            $update_stmt->execute();
            
            // Redirect to student list
            header('Location: student_list.php');
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}

require_once("header.php");
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">
                        <i class="bi bi-shield-lock"></i> Admin Login
                    </h3>
                </div>
                <div class="card-body p-4">
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="bi bi-person"></i> Username
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   required autofocus placeholder="Enter your username">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-key"></i> Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   required placeholder="Enter your password">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <a href="home.php" class="btn btn-link">
                            <i class="bi bi-arrow-left"></i> Back to Home
                        </a>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <small>
                            <strong>Default Credentials:</strong><br>
                            Username: <code>admin</code><br>
                            Password: <code>admin123</code>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("footer.php"); ?>
