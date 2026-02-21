<?php
require_once 'config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Fetch available tracks
$tracks_query = "SELECT track_id, strand_course, track_code, tuition_fee, capacity 
                 FROM Track WHERE is_active = 1 ORDER BY strand_course";
$tracks_result = $conn->query($tracks_query);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $lrn = sanitize_input($_POST['lrn']);
    $first_name = sanitize_input($_POST['first_name']);
    $middle_name = sanitize_input($_POST['middle_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $suffix = sanitize_input($_POST['suffix']);
    $email = sanitize_input($_POST['email']);
    $phone_number = sanitize_input($_POST['phone_number']);
    $address = sanitize_input($_POST['address']);
    $city = sanitize_input($_POST['city']);
    $province = sanitize_input($_POST['province']);
    $zip_code = sanitize_input($_POST['zip_code']);
    $age = (int)$_POST['age'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $nationality = sanitize_input($_POST['nationality']);
    $guardian_name = sanitize_input($_POST['guardian_name']);
    $guardian_contact = sanitize_input($_POST['guardian_contact']);
    $guardian_email = sanitize_input($_POST['guardian_email']);
    $guardian_relationship = sanitize_input($_POST['guardian_relationship']);
    $track_id = (int)$_POST['track_id'];
    $previous_school = sanitize_input($_POST['previous_school']);
    $year_graduated = (int)$_POST['year_graduated'];
    $gwa = floatval($_POST['gwa']);
    
    // Check if LRN already exists
    $check_query = "SELECT lrn FROM Application WHERE lrn = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error_message = "This LRN is already registered in the system.";
    } else {
        // Insert application
        $insert_query = "INSERT INTO Application (
            lrn, first_name, middle_name, last_name, suffix, email, phone_number,
            address, city, province, zip_code, age, birthdate, gender, nationality,
            guardian_name, guardian_contact, guardian_email, guardian_relationship,
            track_id, previous_school, year_graduated, gwa, application_status, application_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', CURDATE())";
        
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssssssssssissssssisis",
            $lrn, $first_name, $middle_name, $last_name, $suffix, $email, $phone_number,
            $address, $city, $province, $zip_code, $age, $birthdate, $gender, $nationality,
            $guardian_name, $guardian_contact, $guardian_email, $guardian_relationship,
            $track_id, $previous_school, $year_graduated, $gwa
        );
        
        if ($stmt->execute()) {
            // Create document record
            $doc_insert = "INSERT INTO Document (lrn) VALUES (?)";
            $doc_stmt = $conn->prepare($doc_insert);
            $doc_stmt->bind_param("s", $lrn);
            $doc_stmt->execute();
            
            $success_message = "Application submitted successfully! Your LRN is: <strong>$lrn</strong>. Please proceed to upload your documents.";
            
            // Redirect to document upload page
            header("Location: upload_documents.php?lrn=" . urlencode($lrn));
            exit();
        } else {
            $error_message = "Error submitting application: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Form - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        .enrollment-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .section-title {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="enrollment-container">
            <h1 class="text-center mb-4" style="color: #667eea;">
                <i class="bi bi-pencil-square"></i> Student Enrollment Form
            </h1>
            <p class="text-center text-muted mb-4">Please fill out all required fields marked with *</p>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <!-- Personal Information -->
                <h4 class="section-title">Personal Information</h4>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label required">Learner Reference Number (LRN)</label>
                        <input type="text" class="form-control" name="lrn" maxlength="40" required 
                               placeholder="e.g., 123456789012">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">First Name</label>
                        <input type="text" class="form-control" name="first_name" maxlength="50" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middle_name" maxlength="50">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Last Name</label>
                        <input type="text" class="form-control" name="last_name" maxlength="50" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Suffix</label>
                        <input type="text" class="form-control" name="suffix" maxlength="10" placeholder="Jr.">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">Birthdate</label>
                        <input type="date" class="form-control" name="birthdate" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label required">Age</label>
                        <input type="number" class="form-control" name="age" min="5" max="100" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Gender</label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" name="nationality" value="Filipino" maxlength="50">
                    </div>
                </div>
                
                <!-- Contact Information -->
                <h4 class="section-title">Contact Information</h4>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="phone_number" maxlength="20" placeholder="+63">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label required">Complete Address</label>
                        <input type="text" class="form-control" name="address" maxlength="255" required 
                               placeholder="House/Unit/Block No., Street, Barangay">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">City/Municipality</label>
                        <input type="text" class="form-control" name="city" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Province</label>
                        <input type="text" class="form-control" name="province" maxlength="100">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" class="form-control" name="zip_code" maxlength="10">
                    </div>
                </div>
                
                <!-- Guardian Information -->
                <h4 class="section-title">Parent/Guardian Information</h4>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Guardian Full Name</label>
                        <input type="text" class="form-control" name="guardian_name" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Relationship</label>
                        <input type="text" class="form-control" name="guardian_relationship" maxlength="50" 
                               placeholder="e.g., Mother, Father, Guardian">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Guardian Contact Number</label>
                        <input type="tel" class="form-control" name="guardian_contact" maxlength="20">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Guardian Email</label>
                        <input type="email" class="form-control" name="guardian_email" maxlength="100">
                    </div>
                </div>
                
                <!-- Academic Information -->
                <h4 class="section-title">Academic Information</h4>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label required">Choose Your Track/Strand</label>
                        <select class="form-select" name="track_id" required>
                            <option value="">Select a track...</option>
                            <?php while ($track = $tracks_result->fetch_assoc()): ?>
                                <option value="<?php echo $track['track_id']; ?>">
                                    <?php echo htmlspecialchars($track['strand_course']); ?> 
                                    (<?php echo htmlspecialchars($track['track_code']); ?>) 
                                    - ₱<?php echo number_format($track['tuition_fee'], 2); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Previous School</label>
                        <input type="text" class="form-control" name="previous_school" maxlength="200">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year Graduated</label>
                        <input type="number" class="form-control" name="year_graduated" min="1900" max="2100" 
                               placeholder="<?php echo date('Y'); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">General Average (GWA)</label>
                        <input type="number" class="form-control" name="gwa" step="0.01" min="1.00" max="5.00" 
                               placeholder="e.g., 1.75">
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <strong>Note:</strong> After submitting this form, you will be redirected to upload your required documents 
                    (Birth Certificate, Diploma, Good Moral, Report Card, etc.).
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
