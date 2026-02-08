<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - Smart Online Enrollment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { color: #667eea; }
        .check-item {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .info {
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .button:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 System Verification Check</h1>
        <p>This page helps diagnose common issues with the Smart Online Enrollment System.</p>

        <?php
        $checks = [];
        $hasErrors = false;
        $hasWarnings = false;

        // Check 1: PHP Version
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '7.4.0', '>=')) {
            $checks[] = ['success', '✅', "PHP Version: $phpVersion (OK)"];
        } else {
            $checks[] = ['error', '❌', "PHP Version: $phpVersion (Requires PHP 7.4+)"];
            $hasErrors = true;
        }

        // Check 2: Required PHP Extensions
        $requiredExtensions = ['mysqli', 'session'];
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                $checks[] = ['success', '✅', "PHP Extension '$ext': Loaded"];
            } else {
                $checks[] = ['error', '❌', "PHP Extension '$ext': NOT loaded"];
                $hasErrors = true;
            }
        }

        // Check 3: Check if files exist
        $requiredFiles = [
            'index.php' => 'Entry point',
            'login.php' => 'Login page',
            'db_connection.php' => 'Database connection',
            'enrollment_form.php' => 'Enrollment form',
            'schema.sql' => 'Database schema'
        ];

        foreach ($requiredFiles as $file => $description) {
            if (file_exists(__DIR__ . '/' . $file)) {
                $checks[] = ['success', '✅', "File '$file' ($description): Found"];
            } else {
                $checks[] = ['error', '❌', "File '$file' ($description): NOT found"];
                $hasErrors = true;
            }
        }

        // Check 4: .htaccess file
        if (file_exists(__DIR__ . '/.htaccess')) {
            $checks[] = ['success', '✅', ".htaccess file: Found"];
        } else {
            $checks[] = ['warning', '⚠️', ".htaccess file: NOT found (may cause issues)"];
            $hasWarnings = true;
        }

        // Check 5: Database connection (suppress errors)
        $dbOK = false;
        $dbMessage = "";
        
        if (file_exists(__DIR__ . '/db_connection.php')) {
            // Try to connect
            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "enrollment_system";
            
            @$conn = new mysqli($host, $username, $password);
            
            if ($conn->connect_error) {
                $checks[] = ['error', '❌', "MySQL Connection: Failed - " . $conn->connect_error];
                $hasErrors = true;
            } else {
                $checks[] = ['success', '✅', "MySQL Server: Connected"];
                
                // Check if database exists
                $result = $conn->query("SHOW DATABASES LIKE '$database'");
                if ($result && $result->num_rows > 0) {
                    $checks[] = ['success', '✅', "Database '$database': Found"];
                    $dbOK = true;
                } else {
                    $checks[] = ['warning', '⚠️', "Database '$database': NOT found (run schema.sql)"];
                    $hasWarnings = true;
                }
                $conn->close();
            }
        }

        // Check 6: File permissions (on Unix-like systems)
        if (function_exists('posix_getpwuid')) {
            $checks[] = ['success', '✅', "Running on Unix-like system (check file permissions)"];
        } else {
            $checks[] = ['success', '✅', "Running on Windows (no permission issues expected)"];
        }

        // Check 7: Session support
        if (session_status() === PHP_SESSION_DISABLED) {
            $checks[] = ['error', '❌', "PHP Sessions: DISABLED"];
            $hasErrors = true;
        } else {
            $checks[] = ['success', '✅', "PHP Sessions: Available"];
        }

        // Display all checks
        foreach ($checks as $check) {
            echo "<div class='check-item {$check[0]}'>";
            echo "<span class='icon'>{$check[1]}</span>";
            echo "<span>{$check[2]}</span>";
            echo "</div>";
        }
        ?>

        <div class="info">
            <h3>📊 Summary</h3>
            <?php
            if ($hasErrors) {
                echo "<p><strong>⚠️ Issues Found:</strong> Please fix the errors above before proceeding.</p>";
            } elseif ($hasWarnings) {
                echo "<p><strong>⚠️ Warnings:</strong> The system might work but some features may be limited.</p>";
            } else {
                echo "<p><strong>✅ All Checks Passed!</strong> Your system is properly configured.</p>";
            }
            ?>

            <h3>📍 Current Location</h3>
            <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
            <p><strong>Script Path:</strong> <?php echo __DIR__; ?></p>
            <p><strong>Access URL:</strong> <?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?></p>
        </div>

        <h3>🔗 Quick Actions</h3>
        <a href="index.php" class="button">Go to Home</a>
        <a href="login.php" class="button">Go to Login</a>
        <?php if (file_exists(__DIR__ . '/APACHE_SETUP.md')): ?>
        <a href="APACHE_SETUP.md" class="button">Setup Guide</a>
        <?php endif; ?>

        <div class="info">
            <h3>💡 Common Solutions</h3>
            <ul>
                <li><strong>404 Error:</strong> Make sure files are in Apache's htdocs directory (e.g., C:\xampp\htdocs\enrollment\)</li>
                <li><strong>Database Error:</strong> Import schema.sql via phpMyAdmin</li>
                <li><strong>Connection Error:</strong> Start MySQL in XAMPP Control Panel</li>
                <li><strong>Permission Error:</strong> Run XAMPP as Administrator (Windows)</li>
            </ul>
            
            <h4>📖 Documentation:</h4>
            <ul>
                <li><a href="APACHE_SETUP.md" target="_blank">Apache Setup Guide</a> - Fix 404 errors</li>
                <li><a href="SETUP.md" target="_blank">Installation Guide</a> - Complete setup</li>
                <li><a href="README.md" target="_blank">README</a> - System overview</li>
            </ul>
        </div>
    </div>
</body>
</html>
