# Foreign Key Constraint Error - Complete Solution

## 🚨 Error You're Seeing

```
Fatal error: Uncaught mysqli_sql_exception: Cannot add or update a child row: 
a foreign key constraint fails (`smartonlineenrollment`.`application`, 
CONSTRAINT `FK_Track_Application` FOREIGN KEY (`track_id`) REFERENCES `track` (`track_id`) 
ON DELETE CASCADE)
```

## 🔍 What This Error Means

This error occurs when you try to insert a student enrollment with a `track_id` that **doesn't exist** in the Track table. It's like trying to enroll a student in a program that hasn't been created yet.

---

## ✅ SOLUTION - Follow These Steps

### Step 1: Verify Database is Set Up Correctly

**Option A: Import the complete schema (RECOMMENDED)**

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Drop the existing database if it has issues:
   ```sql
   DROP DATABASE IF EXISTS smartonlineenrollment;
   ```
3. Create a fresh database:
   ```sql
   CREATE DATABASE smartonlineenrollment;
   ```
4. Select the `smartonlineenrollment` database
5. Click **Import** tab
6. Choose the `schema.sql` file from this repository
7. Click **Go**

**Option B: Run SQL manually**

Open phpMyAdmin and run this SQL:

```sql
-- Use your database
USE smartonlineenrollment;

-- 1. First, insert tracks (MUST be done before any enrollments)
INSERT INTO `Track` (`strand_course`, `enrollment_fee`) VALUES
('STEM (Science, Technology, Engineering, Mathematics)', 6000.00),
('ABM (Accountancy, Business, Management)', 5500.00),
('HUMSS (Humanities and Social Sciences)', 5000.00),
('GAS (General Academic Strand)', 5000.00),
('TVL-ICT (Technical-Vocational-Livelihood)', 5500.00);
```

### Step 2: Verify Tracks Exist

Run this query in phpMyAdmin:

```sql
SELECT * FROM Track;
```

You should see 5 tracks with IDs 1-5.

### Step 3: Update Your Database Connection

Make sure `db_connection.php` has the correct database name:

```php
$database = "smartonlineenrollment";  // Match your database name
```

---

## 🔧 Quick Diagnostic Script

Save this as `diagnose_fk_error.php` in your htdocs folder:

```php
<?php
// Diagnose Foreign Key Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$username = "root";
$password = "";
$database = "smartonlineenrollment";

echo "<h1>Foreign Key Error Diagnostic</h1>";

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("<p style='color:red;'>❌ Connection failed: " . $conn->connect_error . "</p>");
}

echo "<p style='color:green;'>✅ Connected to database: $database</p>";

// Check if Track table exists
$result = $conn->query("SHOW TABLES LIKE 'Track'");
if ($result->num_rows == 0) {
    echo "<p style='color:red;'>❌ Track table doesn't exist!</p>";
    echo "<p>Run the schema.sql file to create it.</p>";
} else {
    echo "<p style='color:green;'>✅ Track table exists</p>";
    
    // Check if Track table has data
    $count_result = $conn->query("SELECT COUNT(*) as count FROM Track");
    $count = $count_result->fetch_assoc()['count'];
    
    if ($count == 0) {
        echo "<p style='color:red;'>❌ Track table is EMPTY! This causes the foreign key error.</p>";
        echo "<p><strong>Solution:</strong> You must insert tracks before enrolling students.</p>";
        echo "<h3>Run this SQL:</h3>";
        echo "<pre>";
        echo "INSERT INTO `Track` (`strand_course`, `enrollment_fee`) VALUES\n";
        echo "('STEM (Science, Technology, Engineering, Mathematics)', 6000.00),\n";
        echo "('ABM (Accountancy, Business, Management)', 5500.00),\n";
        echo "('HUMSS (Humanities and Social Sciences)', 5000.00),\n";
        echo "('GAS (General Academic Strand)', 5000.00),\n";
        echo "('TVL-ICT (Technical-Vocational-Livelihood)', 5500.00);";
        echo "</pre>";
    } else {
        echo "<p style='color:green;'>✅ Track table has $count tracks</p>";
        
        // Show available tracks
        echo "<h3>Available Tracks:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Track/Strand</th><th>Fee</th></tr>";
        
        $tracks = $conn->query("SELECT track_id, strand_course, enrollment_fee FROM Track");
        while ($track = $tracks->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $track['track_id'] . "</td>";
            echo "<td>" . htmlspecialchars($track['strand_course']) . "</td>";
            echo "<td>₱" . number_format($track['enrollment_fee'], 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// Check Application table
echo "<h3>Application Table Check:</h3>";
$app_result = $conn->query("SHOW TABLES LIKE 'Application'");
if ($app_result->num_rows == 0) {
    echo "<p style='color:red;'>❌ Application table doesn't exist!</p>";
} else {
    echo "<p style='color:green;'>✅ Application table exists</p>";
    
    $app_count = $conn->query("SELECT COUNT(*) as count FROM Application");
    $app_total = $app_count->fetch_assoc()['count'];
    echo "<p>Current enrollments: $app_total</p>";
}

$conn->close();
?>
```

---

## 🎯 Common Causes and Solutions

### Cause 1: Track Table is Empty
**Solution:** Insert tracks using the SQL from Step 1

### Cause 2: Wrong track_id in Form
**Solution:** Make sure the track_id you're submitting exists in the Track table (1-5)

### Cause 3: Database Name Mismatch
**Solution:** Ensure your code uses the same database name as your actual database

### Cause 4: Table Names Case Sensitivity
**Solution:** 
- On Windows: MySQL is case-insensitive (Track = track)
- On Linux: MySQL is case-sensitive (Track ≠ track)
- Use consistent capitalization: `Track` and `Application` (as in schema.sql)

---

## 📝 Prevention Tips

1. **Always insert tracks BEFORE enrolling students**
2. **Use the enrollment form** (enrollment_form.php) - it validates track_id
3. **Check track availability** before showing enrollment form
4. **Use prepared statements** with proper validation

---

## 🔍 How to Test

1. Run `diagnose_fk_error.php` to check your setup
2. If Track table is empty, insert the 5 tracks
3. Try enrolling a student with track_id = 1, 2, 3, 4, or 5
4. Should work without errors!

---

## 💡 Quick Fix SQL

If you just want to fix it quickly, run this in phpMyAdmin:

```sql
-- Check if tracks exist
SELECT * FROM Track;

-- If empty, insert tracks
INSERT INTO `Track` (`strand_course`, `enrollment_fee`) VALUES
('STEM (Science, Technology, Engineering, Mathematics)', 6000.00),
('ABM (Accountancy, Business, Management)', 5500.00),
('HUMSS (Humanities and Social Sciences)', 5000.00),
('GAS (General Academic Strand)', 5000.00),
('TVL-ICT (Technical-Vocational-Livelihood)', 5500.00);

-- Verify
SELECT * FROM Track;
```

---

## ✅ Verification Checklist

- [ ] Database `smartonlineenrollment` exists
- [ ] Track table exists
- [ ] Track table has 5 records (track_id 1-5)
- [ ] Application table exists
- [ ] db_connection.php has correct database name
- [ ] You're using track_id values 1-5 in your enrollment

---

**Need more help?** Check `check_system.php` in this repository for complete system diagnostics.
