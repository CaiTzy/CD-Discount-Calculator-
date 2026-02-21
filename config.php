<?php
/**
 * Database Configuration File
 * Smart Online Enrollment System
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'enrollment_system');

// Application Settings
define('SITE_NAME', 'Smart Online Enrollment System');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Database Connection Class
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            // Set charset to utf8mb4
            $this->conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }
    
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    // Prevent cloning
    private function __clone() {}
}

// Helper Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_unique_filename($original_filename) {
    $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $extension;
}

function upload_file($file, $subfolder = '') {
    $target_dir = UPLOAD_DIR . $subfolder;
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 5MB.'];
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and PDF allowed.'];
    }
    
    $filename = generate_unique_filename($file['name']);
    $target_file = $target_dir . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $subfolder . '/' . $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to upload file.'];
    }
}

// Session Management
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function get_user_role() {
    return $_SESSION['role'] ?? null;
}

function is_admin() {
    return get_user_role() === 'Admin';
}
?>
