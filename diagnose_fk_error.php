<?php
/**
 * Foreign Key Error Diagnostic Tool
 * 
 * This script helps diagnose and fix foreign key constraint errors
 * in the Smart Online Enrollment System.
 * 
 * Usage: Place in your htdocs folder and access via browser
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "smartonlineenrollment";  // Change if your database name is different

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foreign Key Error Diagnostic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
        h2 { color: #764ba2; margin-top: 30px; }
        .success { 
            background: #d4edda; 
            border-left: 4px solid #28a745; 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 4px;
        }
        .error { 
            background: #f8d7da; 
            border-left: 4px solid #dc3545; 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 4px;
        }
        .warning { 
            background: #fff3cd; 
            border-left: 4px solid #ffc107; 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 4px;
        }
        .info { 
            background: #d1ecf1; 
            border-left: 4px solid #17a2b8; 
            padding: 10px; 
            margin: 10px 0; 
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border-left: 4px solid #667eea;
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
        .fix-button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .fix-button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Foreign Key Error Diagnostic Tool</h1>
        
        <?php
        // Try to connect to database
        $conn = @new mysqli($host, $username, $password, $database);
        
        if ($conn->connect_error) {
            echo "<div class='error'>";
            echo "<strong>❌ Database Connection Failed!</strong><br>";
            echo "Error: " . htmlspecialchars($conn->connect_error) . "<br><br>";
            echo "<strong>Possible causes:</strong><br>";
            echo "1. Database '$database' doesn't exist<br>";
            echo "2. MySQL server is not running<br>";
            echo "3. Wrong credentials<br><br>";
            echo "<strong>Solution:</strong><br>";
            echo "1. Start MySQL in XAMPP Control Panel<br>";
            echo "2. Create database: <code>CREATE DATABASE $database;</code><br>";
            echo "3. Import schema.sql file<br>";
            echo "</div>";
            exit();
        }
        
        echo "<div class='success'><strong>✅ Connected to database:</strong> $database</div>";
        
        // Check Track table
        echo "<h2>1. Track Table Check</h2>";
        
        $table_check = $conn->query("SHOW TABLES LIKE 'Track'");
        
        if ($table_check->num_rows == 0) {
            echo "<div class='error'>";
            echo "<strong>❌ Track table doesn't exist!</strong><br><br>";
            echo "The Track table is required for foreign key relationships.<br>";
            echo "<strong>Solution:</strong> Import the schema.sql file from the repository.";
            echo "</div>";
        } else {
            echo "<div class='success'><strong>✅ Track table exists</strong></div>";
            
            // Check if Track table has data
            $count_query = $conn->query("SELECT COUNT(*) as count FROM Track");
            $count_row = $count_query->fetch_assoc();
            $track_count = $count_row['count'];
            
            if ($track_count == 0) {
                echo "<div class='error'>";
                echo "<strong>❌ CRITICAL: Track table is EMPTY!</strong><br><br>";
                echo "This is the main cause of the foreign key constraint error.<br>";
                echo "You cannot enroll students if there are no tracks/programs available.<br><br>";
                echo "<strong>Solution:</strong> Insert tracks using the SQL below.";
                echo "</div>";
                
                echo "<div class='warning'>";
                echo "<h3>Run this SQL in phpMyAdmin:</h3>";
                echo "<pre>INSERT INTO `Track` (`strand_course`, `enrollment_fee`) VALUES
('STEM (Science, Technology, Engineering, Mathematics)', 6000.00),
('ABM (Accountancy, Business, Management)', 5500.00),
('HUMSS (Humanities and Social Sciences)', 5000.00),
('GAS (General Academic Strand)', 5000.00),
('TVL-ICT (Technical-Vocational-Livelihood)', 5500.00);</pre>";
                
                // Auto-fix button
                if (isset($_POST['auto_fix_tracks'])) {
                    $insert_sql = "INSERT INTO `Track` (`strand_course`, `enrollment_fee`) VALUES
                    ('STEM (Science, Technology, Engineering, Mathematics)', 6000.00),
                    ('ABM (Accountancy, Business, Management)', 5500.00),
                    ('HUMSS (Humanities and Social Sciences)', 5000.00),
                    ('GAS (General Academic Strand)', 5000.00),
                    ('TVL-ICT (Technical-Vocational-Livelihood)', 5500.00)";
                    
                    if ($conn->query($insert_sql)) {
                        echo "<div class='success'><strong>✅ Tracks inserted successfully!</strong> Refresh the page to see them.</div>";
                    } else {
                        echo "<div class='error'><strong>❌ Error inserting tracks:</strong> " . htmlspecialchars($conn->error) . "</div>";
                    }
                }
                
                echo "<form method='POST'>";
                echo "<button type='submit' name='auto_fix_tracks' class='fix-button'>🔧 Auto-Fix: Insert Tracks Now</button>";
                echo "</form>";
                echo "</div>";
                
            } else {
                echo "<div class='success'><strong>✅ Track table has $track_count track(s)</strong></div>";
                
                // Display available tracks
                echo "<h3>Available Tracks:</h3>";
                echo "<table>";
                echo "<tr><th>Track ID</th><th>Strand/Course</th><th>Enrollment Fee</th></tr>";
                
                $tracks_query = $conn->query("SELECT track_id, strand_course, enrollment_fee FROM Track ORDER BY track_id");
                while ($track = $tracks_query->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($track['track_id']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($track['strand_course']) . "</td>";
                    echo "<td>₱" . number_format($track['enrollment_fee'], 2) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                echo "<div class='info'>";
                echo "<strong>ℹ️ Valid track_id values for enrollment:</strong> ";
                $tracks_query->data_seek(0);
                $ids = [];
                while ($track = $tracks_query->fetch_assoc()) {
                    $ids[] = $track['track_id'];
                }
                echo implode(", ", $ids);
                echo "</div>";
            }
        }
        
        // Check Application table
        echo "<h2>2. Application Table Check</h2>";
        
        $app_check = $conn->query("SHOW TABLES LIKE 'Application'");
        
        if ($app_check->num_rows == 0) {
            echo "<div class='error'>";
            echo "<strong>❌ Application table doesn't exist!</strong><br>";
            echo "Import the schema.sql file to create it.";
            echo "</div>";
        } else {
            echo "<div class='success'><strong>✅ Application table exists</strong></div>";
            
            $app_count_query = $conn->query("SELECT COUNT(*) as count FROM Application");
            $app_count_row = $app_count_query->fetch_assoc();
            $app_count = $app_count_row['count'];
            
            echo "<div class='info'><strong>Current enrollments:</strong> $app_count student(s)</div>";
            
            if ($app_count > 0) {
                echo "<h3>Recent Enrollments:</h3>";
                echo "<table>";
                echo "<tr><th>LRN</th><th>Name</th><th>Track ID</th><th>Total Amount</th></tr>";
                
                $recent = $conn->query("SELECT lrn, first_name, last_name, track_id, total_amount FROM Application ORDER BY created_at DESC LIMIT 5");
                while ($enrollment = $recent->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($enrollment['lrn']) . "</td>";
                    echo "<td>" . htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($enrollment['track_id']) . "</td>";
                    echo "<td>₱" . number_format($enrollment['total_amount'], 2) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
        
        // Check foreign key constraints
        echo "<h2>3. Foreign Key Constraint Check</h2>";
        
        $fk_query = $conn->query("
            SELECT 
                CONSTRAINT_NAME,
                TABLE_NAME,
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM
                information_schema.KEY_COLUMN_USAGE
            WHERE
                REFERENCED_TABLE_SCHEMA = '$database'
                AND TABLE_NAME = 'Application'
                AND CONSTRAINT_NAME = 'FK_Track_Application'
        ");
        
        if ($fk_query && $fk_query->num_rows > 0) {
            $fk = $fk_query->fetch_assoc();
            echo "<div class='success'><strong>✅ Foreign key constraint exists</strong></div>";
            echo "<div class='info'>";
            echo "<strong>Constraint Details:</strong><br>";
            echo "Name: " . htmlspecialchars($fk['CONSTRAINT_NAME']) . "<br>";
            echo "Table: " . htmlspecialchars($fk['TABLE_NAME']) . "<br>";
            echo "Column: " . htmlspecialchars($fk['COLUMN_NAME']) . "<br>";
            echo "References: " . htmlspecialchars($fk['REFERENCED_TABLE_NAME']) . "." . htmlspecialchars($fk['REFERENCED_COLUMN_NAME']) . "<br>";
            echo "</div>";
        } else {
            echo "<div class='warning'><strong>⚠️ Foreign key constraint not found</strong></div>";
        }
        
        // Summary and recommendations
        echo "<h2>4. Summary & Recommendations</h2>";
        
        if ($track_count == 0) {
            echo "<div class='error'>";
            echo "<strong>❌ ACTION REQUIRED</strong><br><br>";
            echo "Your Track table is empty. This is causing the foreign key constraint error.<br>";
            echo "You MUST insert tracks before you can enroll students.<br><br>";
            echo "<strong>Next Steps:</strong><br>";
            echo "1. Click the 'Auto-Fix: Insert Tracks Now' button above, OR<br>";
            echo "2. Run the INSERT SQL in phpMyAdmin, OR<br>";
            echo "3. Re-import the complete schema.sql file<br>";
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<strong>✅ System is properly configured!</strong><br><br>";
            echo "Your database has $track_count track(s) and $app_count enrollment(s).<br>";
            echo "You can now enroll students using track_id values: " . implode(", ", $ids) . "<br><br>";
            echo "<strong>Next Steps:</strong><br>";
            echo "1. Use the enrollment form (enrollment_form.php)<br>";
            echo "2. Select a track from the dropdown<br>";
            echo "3. Fill in student information<br>";
            echo "4. Submit enrollment<br>";
            echo "</div>";
        }
        
        $conn->close();
        ?>
        
        <h2>5. Quick Links</h2>
        <a href="index.php" class="button">Go to Login</a>
        <a href="enrollment_form.php" class="button">Enrollment Form</a>
        <a href="check_system.php" class="button">System Check</a>
        <a href="http://localhost/phpmyadmin" class="button" target="_blank">phpMyAdmin</a>
        
        <div class="info" style="margin-top: 30px;">
            <strong>📖 Need more help?</strong><br>
            See <strong>FK_ERROR_FIX.md</strong> for complete troubleshooting guide.
        </div>
    </div>
</body>
</html>
