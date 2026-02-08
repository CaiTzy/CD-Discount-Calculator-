<?php
include("db_connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success_message = "";
$error_message = "";

// Get available tracks with pricing
$tracks_query = "SELECT track_id, strand_course, enrollment_fee FROM Track ORDER BY track_id";
$tracks_result = $conn->query($tracks_query);
$tracks = [];
if ($tracks_result) {
    while ($row = $tracks_result->fetch_assoc()) {
        $tracks[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and clean data
    $lrn = trim($_POST["lrn"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $address = trim($_POST["address"]);
    $age = trim($_POST["age"]);
    $birthdate = trim($_POST["birthdate"]);
    $gender = trim($_POST["gender"]);
    $guardian_name_contact = trim($_POST["guardian_name_contact"]);
    $track_id = trim($_POST["track_id"]);
    
    // Get enrollment fee for selected track
    $track_stmt = $conn->prepare("SELECT enrollment_fee FROM Track WHERE track_id = ?");
    $track_stmt->bind_param("i", $track_id);
    $track_stmt->execute();
    $track_result = $track_stmt->get_result();
    $track_data = $track_result->fetch_assoc();
    $enrollment_fee = $track_data['enrollment_fee'] ?? 5000.00;
    $track_stmt->close();
    
    // Calculate discount based on age (early bird discount)
    $discount_percent = 0;
    if ($age < 15) {
        $discount_percent = 10; // 10% discount for young enrollees
    } elseif ($age >= 15 && $age <= 17) {
        $discount_percent = 5; // 5% discount for standard age
    }
    
    // Calculate total amount
    $discount_amount = ($enrollment_fee * $discount_percent) / 100;
    $total_amount = $enrollment_fee - $discount_amount;
    
    // Validate required fields
    if (!empty($lrn) && !empty($first_name) && !empty($last_name) && !empty($address) && !empty($track_id)) {
        
        // Ensure proper data types
        $age = (int)$age;
        $track_id = (int)$track_id;
        
        // Use prepared statements
        $stmt = $conn->prepare("INSERT INTO Application (lrn, first_name, last_name, address, age, birthdate, gender, guardian_name_contact, track_id, enrollment_fee, discount_percent, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssississdd", $lrn, $first_name, $last_name, $address, $age, $birthdate, $gender, $guardian_name_contact, $track_id, $enrollment_fee, $discount_percent, $total_amount);
        
        if ($stmt->execute()) {
            $success_message = "✅ Enrollment submitted successfully! Your total fee is ₱" . number_format($total_amount, 2) . " (Discount: " . $discount_percent . "%)";
        } else {
            // Log detailed error securely
            error_log("Enrollment error: " . $conn->error);
            $error_message = "❌ Error: Unable to submit enrollment. The LRN might already exist or there was a database issue. Please try again.";
        }
        
        $stmt->close();
    } else {
        $error_message = "⚠️ Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .enrollment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🎓 Smart Online Enrollment</span>
            <div>
                <span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="enrollment-card p-4 mb-4">
                    <h2 class="mb-4 text-center">Student Enrollment Form</h2>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success shadow-sm"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" id="enrollmentForm">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">LRN (Learner Reference Number): *</label>
                                <input type="text" name="lrn" class="form-control" placeholder="12-digit LRN" required pattern="\d{12}" maxlength="12">
                                <small class="text-muted">Enter your 12-digit Learner Reference Number</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">First Name: *</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Last Name: *</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Address: *</label>
                            <textarea name="address" class="form-control" rows="2" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Age: *</label>
                                <input type="number" name="age" id="age" class="form-control" min="12" max="100" required>
                                <small class="text-muted" id="discountInfo"></small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Birthdate:</label>
                                <input type="date" name="birthdate" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Gender:</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Guardian Name & Contact:</label>
                            <input type="text" name="guardian_name_contact" class="form-control" placeholder="e.g., John Doe - 0912-345-6789">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Track/Strand: *</label>
                            <select name="track_id" id="track_id" class="form-select" required>
                                <option value="">-- Choose Track --</option>
                                <?php foreach ($tracks as $track): ?>
                                    <option value="<?php echo $track['track_id']; ?>" data-fee="<?php echo $track['enrollment_fee']; ?>">
                                        <?php echo htmlspecialchars($track['strand_course']); ?> - ₱<?php echo number_format($track['enrollment_fee'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="alert alert-info" id="pricePreview" style="display: none;">
                            <h5>Price Summary:</h5>
                            <p class="mb-1"><strong>Base Fee:</strong> ₱<span id="baseFee">0.00</span></p>
                            <p class="mb-1"><strong>Discount:</strong> <span id="discountPercent">0</span>% (₱<span id="discountAmount">0.00</span>)</p>
                            <hr>
                            <p class="mb-0"><strong>Total Amount:</strong> ₱<span id="totalAmount">0.00</span></p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
                            <a href="student_list.php" class="btn btn-outline-secondary">View Student List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calculate and display price preview
        function updatePricePreview() {
            const age = parseInt(document.getElementById('age').value) || 0;
            const trackSelect = document.getElementById('track_id');
            const selectedOption = trackSelect.options[trackSelect.selectedIndex];
            const baseFee = parseFloat(selectedOption.getAttribute('data-fee')) || 0;
            
            if (baseFee > 0) {
                // Calculate discount
                let discountPercent = 0;
                if (age < 15) {
                    discountPercent = 10;
                    document.getElementById('discountInfo').textContent = '🎉 You qualify for a 10% early bird discount!';
                    document.getElementById('discountInfo').className = 'text-success';
                } else if (age >= 15 && age <= 17) {
                    discountPercent = 5;
                    document.getElementById('discountInfo').textContent = '🎉 You qualify for a 5% discount!';
                    document.getElementById('discountInfo').className = 'text-success';
                } else {
                    document.getElementById('discountInfo').textContent = '';
                }
                
                const discountAmount = (baseFee * discountPercent) / 100;
                const totalAmount = baseFee - discountAmount;
                
                // Update display
                document.getElementById('baseFee').textContent = baseFee.toFixed(2);
                document.getElementById('discountPercent').textContent = discountPercent;
                document.getElementById('discountAmount').textContent = discountAmount.toFixed(2);
                document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
                document.getElementById('pricePreview').style.display = 'block';
            } else {
                document.getElementById('pricePreview').style.display = 'none';
                document.getElementById('discountInfo').textContent = '';
            }
        }
        
        document.getElementById('age').addEventListener('input', updatePricePreview);
        document.getElementById('track_id').addEventListener('change', updatePricePreview);
    </script>
</body>
</html>
