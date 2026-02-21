<?php
require_once 'config.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$lrn = $_GET['lrn'] ?? '';
$success_message = '';
$error_message = '';

// Verify LRN exists
if ($lrn) {
    $check_query = "SELECT lrn, first_name, last_name FROM Application WHERE lrn = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        header('Location: enrollment_form.php');
        exit();
    }
    
    $student = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lrn'])) {
    $lrn = sanitize_input($_POST['lrn']);
    $uploads = [];
    $upload_errors = [];
    
    // Handle file uploads
    $file_fields = [
        'birth_certificate' => 'birth_certificate_url',
        'diploma' => 'diploma_url',
        'good_moral' => 'good_moral_url',
        'report_card' => 'report_card_url',
        'photo_2x2' => 'photo_2x2_url',
        'transfer_credentials' => 'transfer_credentials_url'
    ];
    
    foreach ($file_fields as $field => $db_column) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $result = upload_file($_FILES[$field], $lrn);
            if ($result['success']) {
                $uploads[$db_column] = $result['filename'];
            } else {
                $upload_errors[] = $field . ': ' . $result['message'];
            }
        }
    }
    
    // Update document records
    if (!empty($uploads)) {
        $update_parts = [];
        $types = '';
        $values = [];
        
        foreach ($uploads as $column => $filename) {
            $update_parts[] = "$column = ?";
            $types .= 's';
            $values[] = $filename;
        }
        
        $types .= 's';
        $values[] = $lrn;
        
        $update_query = "UPDATE Document SET " . implode(', ', $update_parts) . " WHERE lrn = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param($types, ...$values);
        
        if ($stmt->execute()) {
            $success_message = "Documents uploaded successfully!";
        } else {
            $error_message = "Error updating documents: " . $stmt->error;
        }
    }
    
    if (!empty($upload_errors)) {
        $error_message .= " Errors: " . implode(', ', $upload_errors);
    }
}

// Get current documents
if ($lrn) {
    $doc_query = "SELECT * FROM Document WHERE lrn = ?";
    $stmt = $conn->prepare($doc_query);
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $documents = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        .upload-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .file-upload {
            border: 2px dashed #667eea;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .file-upload:hover {
            background-color: #f8f9ff;
            border-color: #764ba2;
        }
        .uploaded-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="upload-container">
            <h1 class="text-center mb-4" style="color: #667eea;">
                Upload Required Documents
            </h1>
            
            <?php if (isset($student)): ?>
                <div class="alert alert-info">
                    <strong>Student:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?><br>
                    <strong>LRN:</strong> <?php echo htmlspecialchars($student['lrn']); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lrn" value="<?php echo htmlspecialchars($lrn); ?>">
                
                <p class="text-muted mb-4">
                    <strong>Instructions:</strong> Please upload clear scanned copies or photos of your documents. 
                    Accepted formats: JPG, PNG, PDF. Maximum file size: 5MB per file.
                </p>
                
                <!-- Birth Certificate -->
                <div class="file-upload">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label required mb-0"><strong>Birth Certificate (PSA)</strong></label>
                            <?php if (!empty($documents['birth_certificate_url'])): ?>
                                <span class="uploaded-badge ms-2">✓ Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="file" class="form-control mt-2" name="birth_certificate" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                
                <!-- Diploma -->
                <div class="file-upload">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label required mb-0"><strong>Diploma / Certificate of Completion</strong></label>
                            <?php if (!empty($documents['diploma_url'])): ?>
                                <span class="uploaded-badge ms-2">✓ Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="file" class="form-control mt-2" name="diploma" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                
                <!-- Good Moral -->
                <div class="file-upload">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label required mb-0"><strong>Certificate of Good Moral Character</strong></label>
                            <?php if (!empty($documents['good_moral_url'])): ?>
                                <span class="uploaded-badge ms-2">✓ Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="file" class="form-control mt-2" name="good_moral" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                
                <!-- Report Card -->
                <div class="file-upload">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label required mb-0"><strong>Report Card (Form 138)</strong></label>
                            <?php if (!empty($documents['report_card_url'])): ?>
                                <span class="uploaded-badge ms-2">✓ Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="file" class="form-control mt-2" name="report_card" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                
                <!-- 2x2 Photo -->
                <div class="file-upload">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label required mb-0"><strong>2x2 ID Photo (Recent)</strong></label>
                            <?php if (!empty($documents['photo_2x2_url'])): ?>
                                <span class="uploaded-badge ms-2">✓ Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="file" class="form-control mt-2" name="photo_2x2" accept=".jpg,.jpeg,.png">
                </div>
                
                <!-- Transfer Credentials (Optional) -->
                <div class="file-upload">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label mb-0"><strong>Transfer Credentials</strong> (if transferee)</label>
                            <?php if (!empty($documents['transfer_credentials_url'])): ?>
                                <span class="uploaded-badge ms-2">✓ Uploaded</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="file" class="form-control mt-2" name="transfer_credentials" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                
                <div class="alert alert-warning mt-4">
                    <strong>Note:</strong> You can upload documents now or later. Make sure all required documents 
                    are uploaded before the enrollment deadline.
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        Upload Documents
                    </button>
                    <a href="enrollment_form.php" class="btn btn-secondary btn-lg px-5 ms-2">
                        Back to Form
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
