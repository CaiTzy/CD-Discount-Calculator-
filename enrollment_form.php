<?php
require_once 'config.php';
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Student Enrollment Form';
include 'header.php';

$error = '';
$success = '';

// Fetch available tracks
$tracks_sql = "SELECT track_id, strand_course, tuition_fee FROM Track ORDER BY strand_course";
$tracks_result = mysqli_query($conn, $tracks_sql);
$tracks = mysqli_fetch_all($tracks_result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lrn = trim($_POST['lrn'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $guardian = trim($_POST['guardian'] ?? '');
    $track_id = $_POST['track_id'] ?? '';
    
    // Validate required fields
    if (empty($lrn) || empty($first_name) || empty($last_name) || empty($address) || 
        empty($birthdate) || empty($gender) || empty($track_id)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check if LRN already exists
        $check_sql = "SELECT lrn FROM Application WHERE lrn = ?";
        $check_result = db_fetch($conn, $check_sql, [$lrn], 's');
        
        if ($check_result) {
            $error = 'LRN already exists. Please use a unique LRN.';
        } else {
            // Insert new application
            $insert_sql = "INSERT INTO Application (lrn, first_name, last_name, address, birthdate, gender, guardian_name_contact, track_id, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $insert_sql);
            $status = 'Pending';
            mysqli_stmt_bind_param($stmt, 'sssssssis', $lrn, $first_name, $last_name, $address, $birthdate, $gender, $guardian, $track_id, $status);
            
            if (mysqli_stmt_execute($stmt)) {
                // Log the enrollment
                $history_sql = "INSERT INTO Enrollment_History (lrn, action, new_status, changed_by, notes) 
                               VALUES (?, 'NEW_APPLICATION', 'Pending', ?, 'Application submitted')";
                $history_stmt = mysqli_prepare($conn, $history_sql);
                mysqli_stmt_bind_param($history_stmt, 'si', $lrn, $_SESSION['user_id']);
                mysqli_stmt_execute($history_stmt);
                mysqli_stmt_close($history_stmt);
                
                $success = 'Application submitted successfully! LRN: ' . htmlspecialchars($lrn);
                
                // Clear form
                $lrn = $first_name = $last_name = $address = $birthdate = $gender = $guardian = $track_id = '';
            } else {
                $error = 'Error submitting application. Please try again.';
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> New Student Enrollment</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" id="enrollmentForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lrn" class="form-label">LRN (Learner Reference Number) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lrn" name="lrn" 
                                   value="<?php echo htmlspecialchars($lrn ?? ''); ?>" 
                                   pattern="[0-9]{12}" 
                                   title="LRN must be 12 digits"
                                   placeholder="e.g., 123456789012" required>
                            <small class="text-muted">12-digit number</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="track_id" class="form-label">Track/Strand <span class="text-danger">*</span></label>
                            <select class="form-select" id="track_id" name="track_id" required>
                                <option value="">Select Track...</option>
                                <?php foreach ($tracks as $track): ?>
                                    <option value="<?php echo $track['track_id']; ?>" 
                                            data-fee="<?php echo $track['tuition_fee']; ?>"
                                            <?php echo (isset($track_id) && $track_id == $track['track_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($track['strand_course']); ?> 
                                        (₱<?php echo number_format($track['tuition_fee'], 2); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                   value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                   value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                   value="<?php echo htmlspecialchars($birthdate ?? ''); ?>" 
                                   max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" readonly>
                            <small class="text-muted">Auto-calculated from birthdate</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender...</option>
                                <option value="Male" <?php echo (isset($gender) && $gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($gender) && $gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo (isset($gender) && $gender === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="guardian" class="form-label">Guardian Name & Contact</label>
                        <input type="text" class="form-control" id="guardian" name="guardian" 
                               value="<?php echo htmlspecialchars($guardian ?? ''); ?>"
                               placeholder="e.g., Juan Dela Cruz - 09171234567">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Submit Application
                        </button>
                        <a href="student_list.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Student List
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-calculate age from birthdate
document.getElementById('birthdate').addEventListener('change', function() {
    const birthdate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    const monthDiff = today.getMonth() - birthdate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }
    
    document.getElementById('age').value = age >= 0 ? age : '';
});

// Trigger age calculation on page load if birthdate is set
window.addEventListener('load', function() {
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput.value) {
        birthdateInput.dispatchEvent(new Event('change'));
    }
});
</script>

<?php include 'footer.php'; ?>
