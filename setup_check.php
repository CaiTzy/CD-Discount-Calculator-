<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - Smart Online Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3><i class="bi bi-gear-fill"></i> System Configuration Check</h3>
            </div>
            <div class="card-body">
                <h5>Smart Online Enrollment System</h5>
                <p class="text-muted">This page checks if your system is configured correctly.</p>
                <hr>

                <?php
                $errors = [];
                $warnings = [];
                $success = [];

                // Check PHP version
                if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
                    $success[] = "PHP Version: " . PHP_VERSION . " ✓";
                } else {
                    $errors[] = "PHP Version: " . PHP_VERSION . " (Required: 7.4 or higher)";
                }

                // Check required extensions
                $required_extensions = ['mysqli', 'pdo', 'session'];
                foreach ($required_extensions as $ext) {
                    if (extension_loaded($ext)) {
                        $success[] = "PHP Extension '$ext': Loaded ✓";
                    } else {
                        $errors[] = "PHP Extension '$ext': Not loaded (Required)";
                    }
                }

                // Check config file
                if (file_exists('config.php')) {
                    $success[] = "config.php: Found ✓";
                    require_once 'config.php';
                } else {
                    $errors[] = "config.php: Not found";
                }

                // Check database connection
                if (file_exists('db_connection.php')) {
                    $success[] = "db_connection.php: Found ✓";
                    require_once 'db_connection.php';
                    
                    if (isset($conn) && $conn) {
                        $success[] = "Database Connection: Successful ✓";
                        
                        // Check tables
                        $tables = ['Track', 'Application', 'Document', 'Users', 'Enrollment_History'];
                        foreach ($tables as $table) {
                            $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                            if ($result && mysqli_num_rows($result) > 0) {
                                $success[] = "Table '$table': Exists ✓";
                            } else {
                                $errors[] = "Table '$table': Does not exist (Run schema.sql)";
                            }
                        }
                        
                        // Check admin user
                        $user_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM Users WHERE username = 'admin'");
                        if ($user_check) {
                            $user_data = mysqli_fetch_assoc($user_check);
                            if ($user_data['count'] > 0) {
                                $success[] = "Admin User: Exists ✓";
                            } else {
                                $warnings[] = "Admin User: Not found (Run schema.sql)";
                            }
                        }
                        
                        // Check sample data
                        $app_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM Application");
                        if ($app_check) {
                            $app_data = mysqli_fetch_assoc($app_check);
                            if ($app_data['count'] > 0) {
                                $success[] = "Sample Data: Found " . $app_data['count'] . " applications ✓";
                            } else {
                                $warnings[] = "Sample Data: No applications found (Optional: Run sample_data.sql)";
                            }
                        }
                        
                    } else {
                        $errors[] = "Database Connection: Failed (Check config.php credentials)";
                    }
                } else {
                    $errors[] = "db_connection.php: Not found";
                }

                // Check uploads directory
                if (is_dir('uploads')) {
                    $success[] = "uploads/ directory: Exists ✓";
                    if (is_writable('uploads')) {
                        $success[] = "uploads/ directory: Writable ✓";
                    } else {
                        $warnings[] = "uploads/ directory: Not writable (Set permissions to 755)";
                    }
                } else {
                    $warnings[] = "uploads/ directory: Does not exist (Will be created automatically)";
                }

                // Check required files
                $required_files = [
                    'index.php', 'login.php', 'logout.php', 'header.php', 'footer.php',
                    'enrollment_form.php', 'student_list.php', 'view_application.php',
                    'upload_documents.php', 'admin_dashboard.php'
                ];
                
                $missing_files = [];
                foreach ($required_files as $file) {
                    if (!file_exists($file)) {
                        $missing_files[] = $file;
                    }
                }
                
                if (empty($missing_files)) {
                    $success[] = "All PHP files: Present ✓";
                } else {
                    $errors[] = "Missing files: " . implode(', ', $missing_files);
                }

                // Display results
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">';
                    echo '<h5><i class="bi bi-x-circle"></i> Critical Errors</h5>';
                    echo '<ul class="mb-0">';
                    foreach ($errors as $error) {
                        echo '<li>' . htmlspecialchars($error) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                if (!empty($warnings)) {
                    echo '<div class="alert alert-warning">';
                    echo '<h5><i class="bi bi-exclamation-triangle"></i> Warnings</h5>';
                    echo '<ul class="mb-0">';
                    foreach ($warnings as $warning) {
                        echo '<li>' . htmlspecialchars($warning) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                if (!empty($success)) {
                    echo '<div class="alert alert-success">';
                    echo '<h5><i class="bi bi-check-circle"></i> Success</h5>';
                    echo '<ul class="mb-0">';
                    foreach ($success as $item) {
                        echo '<li>' . htmlspecialchars($item) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                // Overall status
                if (empty($errors)) {
                    echo '<div class="alert alert-info">';
                    echo '<h4><i class="bi bi-info-circle"></i> System Status</h4>';
                    if (empty($warnings)) {
                        echo '<p class="mb-0"><strong>✓ Your system is ready!</strong> All checks passed.</p>';
                        echo '<a href="login.php" class="btn btn-primary mt-3"><i class="bi bi-box-arrow-in-right"></i> Go to Login</a>';
                    } else {
                        echo '<p class="mb-0"><strong>⚠ Your system is mostly ready.</strong> Please review warnings above.</p>';
                        echo '<a href="login.php" class="btn btn-warning mt-3"><i class="bi bi-box-arrow-in-right"></i> Continue to Login</a>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-danger">';
                    echo '<h4><i class="bi bi-x-circle"></i> System Status</h4>';
                    echo '<p class="mb-0"><strong>✗ Configuration incomplete.</strong> Please fix the errors above before proceeding.</p>';
                    echo '</div>';
                }
                ?>

                <hr>
                <h6>Configuration Details</h6>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th width="30%">PHP Version</th>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <th>Server Software</th>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                    </tr>
                    <tr>
                        <th>Document Root</th>
                        <td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td>
                    </tr>
                    <tr>
                        <th>Current Directory</th>
                        <td><?php echo __DIR__; ?></td>
                    </tr>
                    <?php if (defined('DB_HOST')): ?>
                    <tr>
                        <th>Database Host</th>
                        <td><?php echo DB_HOST; ?></td>
                    </tr>
                    <tr>
                        <th>Database Name</th>
                        <td><?php echo DB_NAME; ?></td>
                    </tr>
                    <?php endif; ?>
                </table>

                <div class="mt-3">
                    <a href="README.md" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-book"></i> Read Documentation
                    </a>
                    <a href="QUICKSTART.md" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-lightning"></i> Quick Start Guide
                    </a>
                    <a href="javascript:location.reload()" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-clockwise"></i> Recheck
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
