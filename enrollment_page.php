<?php
$page_title = "Student Enrollment Form";
require_once("db_connection.php");

$message = "";
$message_type = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lrn = sanitize_input($_POST["lrn"]);
    $first_name = sanitize_input($_POST["first_name"]);
    $last_name = sanitize_input($_POST["last_name"]);
    $address = sanitize_input($_POST["address"]);
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $guardian_name_contact = sanitize_input($_POST["guardian_name_contact"]);
    $track_id = isset($_POST["track_id"]) ? (int)$_POST["track_id"] : 0;

    // Calculate age automatically
    $today = new DateTime();
    $birth = new DateTime($birthdate);
    $age = $today->diff($birth)->y;

    if (!empty($lrn) && $track_id > 0) {
        try {
            // Check if LRN already exists
            $check_stmt = $conn->prepare("SELECT lrn FROM Application WHERE lrn = ?");
            $check_stmt->bind_param("s", $lrn);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $message = "Error: This LRN ($lrn) is already registered in the system.";
                $message_type = "danger";
            } else {
                // Insert application
                $stmt = $conn->prepare("INSERT INTO Application (lrn, first_name, last_name, address, age, birthdate, gender, guardian_name, guardian_contact, track_id, application_status, application_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', CURDATE())");
                
                // Extract guardian name and contact separately
                $guardian_parts = explode('-', $guardian_name_contact);
                $guardian_name = trim($guardian_parts[0] ?? '');
                $guardian_contact = trim($guardian_parts[1] ?? '');
                
                $stmt->bind_param("ssssissssi", $lrn, $first_name, $last_name, $address, $age, $birthdate, $gender, $guardian_name, $guardian_contact, $track_id);

                if ($stmt->execute()) {
                    // Create document record
                    $doc_stmt = $conn->prepare("INSERT INTO Document (lrn) VALUES (?)");
                    $doc_stmt->bind_param("s", $lrn);
                    $doc_stmt->execute();
                    
                    $message = "Success! Your application has been submitted. LRN: <strong>$lrn</strong>";
                    $message_type = "success";
                    
                    // Clear form
                    $_POST = array();
                }
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1452) {
                $message = "Error: Invalid Track ID. Please select a valid track.";
                $message_type = "danger";
            } elseif ($e->getCode() == 1062) {
                $message = "Error: This LRN is already registered.";
                $message_type = "danger";
            } else {
                $message = "Error: " . $e->getMessage();
                $message_type = "danger";
            }
        }
    } else {
        $message = "Error: Please fill in all required fields (LRN and Track must be selected).";
        $message_type = "warning";
    }
}

// Fetch available tracks
$tracks_query = "SELECT track_id, strand_course, track_code FROM Track WHERE is_active = 1 ORDER BY strand_course";
$tracks_result = $conn->query($tracks_query);
if (!$tracks_result) {
    die("Error fetching tracks: " . $conn->error);
}

require_once("header.php");
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Student Enrollment Form
                    </h3>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <!-- Personal Information Section -->
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <i class="bi bi-person-fill"></i> Personal Information
                        </h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lrn" class="form-label">
                                    Learner Reference Number (LRN) <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="lrn" name="lrn" 
                                       value="<?php echo isset($_POST['lrn']) ? htmlspecialchars($_POST['lrn']) : ''; ?>"
                                       required maxlength="40" placeholder="e.g., 123456789012">
                                <small class="text-muted">12-digit unique identifier</small>
                            </div>
                            <div class="col-md-6">
                                <label for="birthdate" class="form-label">
                                    Birthdate <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                       value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>"
                                       required max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                                       required maxlength="50">
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                                       required maxlength="50">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="address" class="form-label">
                                    Complete Address <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>"
                                       required maxlength="255" placeholder="House No., Street, Barangay, City">
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label">
                                    Gender <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select...</option>
                                    <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Guardian Information Section -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4 text-primary">
                            <i class="bi bi-people-fill"></i> Guardian Information
                        </h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="guardian_name_contact" class="form-label">
                                    Guardian Name and Contact
                                </label>
                                <input type="text" class="form-control" id="guardian_name_contact" name="guardian_name_contact" 
                                       value="<?php echo isset($_POST['guardian_name_contact']) ? htmlspecialchars($_POST['guardian_name_contact']) : ''; ?>"
                                       maxlength="150" placeholder="e.g., Maria Santos - 09171234567">
                                <small class="text-muted">Format: Name - Contact Number</small>
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4 text-primary">
                            <i class="bi bi-book-fill"></i> Academic Track Selection
                        </h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="track_id" class="form-label">
                                    Choose Your Track/Strand <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="track_id" name="track_id" required>
                                    <option value="">Select a track...</option>
                                    <?php while ($track = $tracks_result->fetch_assoc()): ?>
                                        <option value="<?php echo $track['track_id']; ?>" 
                                                <?php echo (isset($_POST['track_id']) && $_POST['track_id'] == $track['track_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($track['strand_course']); ?> 
                                            (<?php echo htmlspecialchars($track['track_code']); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note:</strong> All fields marked with <span class="text-danger">*</span> are required. 
                            Please ensure all information is accurate before submitting.
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-fill"></i> Submit Enrollment Application
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("footer.php"); ?>
