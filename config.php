<?php
/**
 * Configuration File
 * Smart Online Enrollment System
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'enrollment_system');

// Application Settings
define('SITE_NAME', 'Smart Online Enrollment System');
define('SITE_URL', 'http://localhost');
define('ADMIN_EMAIL', 'admin@enrollment.local');

// Upload Directory
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// Allowed File Extensions
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Session Settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Timezone
date_default_timezone_set('Asia/Manila');

// Create upload directory if it doesn't exist
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
