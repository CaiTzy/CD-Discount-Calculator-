<?php
require_once 'config.php';
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Upload Documents';
include 'header.php';

$error = '';
$success = '';
$lrn = $_GET['lrn'] ?? $_POST['lrn'] ?? '';

// Fetch student info
$student = null;
if (!empty($lrn)) {
    $sql = "SELECT a.*, t.strand_course FROM Application a 
            JOIN Track t ON a.track_id = t.track_id 
            WHERE a.lrn = ?";
    $student = db_fetch($conn, $sql, [$lrn], 's');
    
    if (!$student) {
        $error = 'Student not found.';
    }
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $student) {
    $lrn = $_POST['lrn'];
    $upload_dir = UPLOAD_DIR . $lrn . '/';
    
    // Create student directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $uploaded_files = [];
    $file_fields = ['birth_certificate', 'diploma', 'good_moral', 'report_card'];
    
    foreach ($file_fields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$field];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            // Validate file extension
            if (!in_array($ext, ALLOWED_EXTENSIONS)) {
                $error = "Invalid file type for $field. Allowed: " . implode(', ', ALLOWED_EXTENSIONS);
                break;
            }
            
            // Validate file size
            if ($file['size'] > MAX_FILE_SIZE) {
                $error = "File too large for $field. Maximum " . (MAX_FILE_SIZE / 1024 / 1024) . "MB";
                break;
            }
            
            // Generate unique filename
            $filename = $field . '_' . time() . '.' . $ext;
            $filepath = $upload_dir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $uploaded_files[$field . '_url'] = $filename;
            } else {
                $error = "Failed to upload $field";
                break;
            }
        }
    }
    
    if (empty($error) && !empty($uploaded_files)) {
        // Check if document record exists
        $check_sql = "SELECT document_id FROM Document WHERE lrn = ?";
        $existing = db_fetch($conn, $check_sql, [$lrn], 's');
        
        if ($existing) {
            // Update existing record
            $update_parts = [];
            $params = [];
            $types = '';
            
            foreach ($uploaded_files as $field => $value) {
                $update_parts[] = "$field = ?";
                $params[] = $value;
                $types .= 's';
            }
            
            $params[] = $lrn;
            $types .= 's';
            
            $update_sql = "UPDATE Document SET " . implode(', ', $update_parts) . " WHERE lrn = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Insert new record
            $fields = array_keys($uploaded_files);
            $fields[] = 'lrn';
            $values = array_values($uploaded_files);
            $values[] = $lrn;
            
            $placeholders = str_repeat('?,', count($values) - 1) . '?';
            $types = str_repeat('s', count($values));
            
            $insert_sql = "INSERT INTO Document (" . implode(', ', $fields) . ") VALUES ($placeholders)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($stmt, $types, ...$values);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        
        $success = 'Documents uploaded successfully!';
    }
}

// Fetch existing documents
$documents = null;
if ($student) {
    $doc_sql = "SELECT * FROM Document WHERE lrn = ?";
    $documents = db_fetch($conn, $doc_sql, [$lrn], 's');
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <?php if (!$student): ?>
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-upload"></i> Upload Documents</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label for="lrn" class="form-label">Enter Student LRN</label>
                            <input type="text" class="form-control" id="lrn" name="lrn" 
                                   placeholder="12-digit LRN" pattern="[0-9]{12}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Find Student
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>LRN:</strong> <?php echo htmlspecialchars($student['lrn']); ?></p>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Track:</strong> <?php echo htmlspecialchars($student['strand_course']); ?></p>
                            <p><strong>Status:</strong> <span class="badge bg-info"><?php echo htmlspecialchars($student['status']); ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-upload"></i> Upload Required Documents</h4>
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
                            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="lrn" value="<?php echo htmlspecialchars($lrn); ?>">
                        
                        <div class="mb-3">
                            <label for="birth_certificate" class="form-label">Birth Certificate</label>
                            <?php if ($documents && $documents['birth_certificate_url']): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> Uploaded: <?php echo htmlspecialchars($documents['birth_certificate_url']); ?>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Allowed: JPG, PNG, PDF (Max 5MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="diploma" class="form-label">Diploma</label>
                            <?php if ($documents && $documents['diploma_url']): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> Uploaded: <?php echo htmlspecialchars($documents['diploma_url']); ?>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="diploma" name="diploma" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Allowed: JPG, PNG, PDF (Max 5MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="good_moral" class="form-label">Good Moral Certificate</label>
                            <?php if ($documents && $documents['good_moral_url']): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> Uploaded: <?php echo htmlspecialchars($documents['good_moral_url']); ?>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="good_moral" name="good_moral" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Allowed: JPG, PNG, PDF (Max 5MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="report_card" class="form-label">Report Card</label>
                            <?php if ($documents && $documents['report_card_url']): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i> Uploaded: <?php echo htmlspecialchars($documents['report_card_url']); ?>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="report_card" name="report_card" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <small class="text-muted">Allowed: JPG, PNG, PDF (Max 5MB)</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload"></i> Upload Documents
                            </button>
                            <a href="student_list.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Student List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
