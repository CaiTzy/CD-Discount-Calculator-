<?php
require_once 'config.php';
require_login();

$db = Database::getInstance();
$conn = $db->getConnection();

$lrn = $_GET['lrn'] ?? '';

if (!$lrn) {
    header('Location: admin_dashboard.php');
    exit();
}

// Get application details
$query = "SELECT a.*, t.strand_course, t.track_code, t.tuition_fee
          FROM Application a
          INNER JOIN Track t ON a.track_id = t.track_id
          WHERE a.lrn = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $lrn);
$stmt->execute();
$application = $stmt->get_result()->fetch_assoc();

if (!$application) {
    header('Location: admin_dashboard.php');
    exit();
}

// Get documents
$doc_query = "SELECT * FROM Document WHERE lrn = ?";
$doc_stmt = $conn->prepare($doc_query);
$doc_stmt->bind_param("s", $lrn);
$doc_stmt->execute();
$documents = $doc_stmt->get_result()->fetch_assoc();

// Get history
$history_query = "SELECT * FROM Enrollment_History WHERE lrn = ? ORDER BY changed_at DESC";
$history_stmt = $conn->prepare($history_query);
$history_stmt->bind_param("s", $lrn);
$history_stmt->execute();
$history = $history_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-badge"></i> Application Details</h2>
            <a href="admin_dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <!-- Student Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Student Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>LRN:</strong> <?php echo htmlspecialchars($application['lrn']); ?></p>
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($application['first_name'] . ' ' . ($application['middle_name'] ? $application['middle_name'] . ' ' : '') . $application['last_name'] . ($application['suffix'] ? ' ' . $application['suffix'] : '')); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($application['email'] ?? 'N/A'); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($application['phone_number'] ?? 'N/A'); ?></p>
                        <p><strong>Birthdate:</strong> <?php echo date('F d, Y', strtotime($application['birthdate'])); ?> (Age: <?php echo $application['age']; ?>)</p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($application['gender']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($application['address']); ?></p>
                        <p><strong>City:</strong> <?php echo htmlspecialchars($application['city'] ?? 'N/A'); ?></p>
                        <p><strong>Province:</strong> <?php echo htmlspecialchars($application['province'] ?? 'N/A'); ?></p>
                        <p><strong>ZIP Code:</strong> <?php echo htmlspecialchars($application['zip_code'] ?? 'N/A'); ?></p>
                        <p><strong>Nationality:</strong> <?php echo htmlspecialchars($application['nationality']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Guardian Information -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Guardian Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Guardian Name:</strong> <?php echo htmlspecialchars($application['guardian_name'] ?? 'N/A'); ?></p>
                        <p><strong>Relationship:</strong> <?php echo htmlspecialchars($application['guardian_relationship'] ?? 'N/A'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($application['guardian_contact'] ?? 'N/A'); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($application['guardian_email'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Academic Information -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Academic Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Chosen Track:</strong> <?php echo htmlspecialchars($application['strand_course']); ?> (<?php echo htmlspecialchars($application['track_code']); ?>)</p>
                <p><strong>Tuition Fee:</strong> ₱<?php echo number_format($application['tuition_fee'], 2); ?></p>
                <p><strong>Previous School:</strong> <?php echo htmlspecialchars($application['previous_school'] ?? 'N/A'); ?></p>
                <p><strong>Year Graduated:</strong> <?php echo htmlspecialchars($application['year_graduated'] ?? 'N/A'); ?></p>
                <p><strong>General Average (GWA):</strong> <?php echo $application['gwa'] ? number_format($application['gwa'], 2) : 'N/A'; ?></p>
            </div>
        </div>
        
        <!-- Application Status -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Application Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="admin_dashboard.php">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="lrn" value="<?php echo htmlspecialchars($lrn); ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Current Status:</strong> 
                                <span class="badge bg-primary"><?php echo htmlspecialchars($application['application_status']); ?></span>
                            </p>
                            <p><strong>Application Date:</strong> <?php echo date('F d, Y', strtotime($application['application_date'])); ?></p>
                            <?php if ($application['enrollment_date']): ?>
                                <p><strong>Enrollment Date:</strong> <?php echo date('F d, Y', strtotime($application['enrollment_date'])); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Update Status:</strong></label>
                            <select name="new_status" class="form-select mb-2">
                                <option value="Pending" <?php echo $application['application_status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Under Review" <?php echo $application['application_status'] === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                                <option value="Approved" <?php echo $application['application_status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?php echo $application['application_status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                <option value="Enrolled" <?php echo $application['application_status'] === 'Enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                                <option value="Withdrawn" <?php echo $application['application_status'] === 'Withdrawn' ? 'selected' : ''; ?>>Withdrawn</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Documents -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Submitted Documents</h5>
            </div>
            <div class="card-body">
                <?php if ($documents): ?>
                    <form method="POST" action="admin_dashboard.php">
                        <input type="hidden" name="action" value="update_verification">
                        <input type="hidden" name="lrn" value="<?php echo htmlspecialchars($lrn); ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <p><strong>Document Verification Status:</strong> 
                                    <span class="badge bg-<?php echo $documents['verification_status'] === 'Verified' ? 'success' : 'warning'; ?>">
                                        <?php echo htmlspecialchars($documents['verification_status']); ?>
                                    </span>
                                </p>
                                <?php if ($documents['verified_by']): ?>
                                    <p class="small text-muted">Verified by: <?php echo htmlspecialchars($documents['verified_by']); ?> 
                                    on <?php echo date('F d, Y', strtotime($documents['verified_at'])); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <select name="verification_status" class="form-select mb-2">
                                    <option value="Pending">Pending</option>
                                    <option value="Verified">Verified</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Incomplete">Incomplete</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success">Update Verification</button>
                            </div>
                        </div>
                    </form>
                    
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Birth Certificate:</strong> 
                            <?php echo $documents['birth_certificate_url'] ? '✓ Uploaded' : '✗ Not uploaded'; ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Diploma:</strong> 
                            <?php echo $documents['diploma_url'] ? '✓ Uploaded' : '✗ Not uploaded'; ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Good Moral Certificate:</strong> 
                            <?php echo $documents['good_moral_url'] ? '✓ Uploaded' : '✗ Not uploaded'; ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Report Card:</strong> 
                            <?php echo $documents['report_card_url'] ? '✓ Uploaded' : '✗ Not uploaded'; ?>
                        </li>
                        <li class="list-group-item">
                            <strong>2x2 Photo:</strong> 
                            <?php echo $documents['photo_2x2_url'] ? '✓ Uploaded' : '✗ Not uploaded'; ?>
                        </li>
                        <li class="list-group-item">
                            <strong>Transfer Credentials:</strong> 
                            <?php echo $documents['transfer_credentials_url'] ? '✓ Uploaded' : '✗ Not uploaded'; ?>
                        </li>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No documents uploaded yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- History -->
        <?php if ($history->num_rows > 0): ?>
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Status Change History</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Previous Status</th>
                                <th>New Status</th>
                                <th>Changed By</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($h = $history->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('M d, Y H:i', strtotime($h['changed_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($h['previous_status'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($h['new_status']); ?></td>
                                    <td><?php echo htmlspecialchars($h['changed_by'] ?? 'System'); ?></td>
                                    <td><?php echo htmlspecialchars($h['change_reason'] ?? ''); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
