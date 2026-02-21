<?php
require_once 'config.php';
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'View Application';
include 'header.php';

$lrn = $_GET['lrn'] ?? '';
$error = '';
$success = '';

// Fetch student details
$student = null;
$documents = null;
$history = [];

if (!empty($lrn)) {
    $sql = "
        SELECT 
            a.*,
            t.strand_course,
            t.tuition_fee,
            t.description as track_description
        FROM Application a
        JOIN Track t ON a.track_id = t.track_id
        WHERE a.lrn = ?
    ";
    $student = db_fetch($conn, $sql, [$lrn], 's');
    
    if ($student) {
        // Fetch documents
        $doc_sql = "SELECT * FROM Document WHERE lrn = ?";
        $documents = db_fetch($conn, $doc_sql, [$lrn], 's');
        
        // Fetch history
        $history_sql = "
            SELECT h.*, u.full_name as changed_by_name
            FROM Enrollment_History h
            LEFT JOIN Users u ON h.changed_by = u.user_id
            WHERE h.lrn = ?
            ORDER BY h.changed_at DESC
        ";
        $history_result = db_query($conn, $history_sql, [$lrn], 's');
        $history = mysqli_fetch_all($history_result, MYSQLI_ASSOC);
    } else {
        $error = 'Student not found.';
    }
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status']) && $student) {
    $new_status = $_POST['status'];
    $notes = trim($_POST['notes'] ?? '');
    
    // Call stored procedure to update status
    $update_sql = "CALL sp_update_application_status(?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'ssis', $lrn, $new_status, $_SESSION['user_id'], $notes);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Status updated successfully!';
        // Refresh student data
        mysqli_stmt_close($stmt);
        header("Location: view_application.php?lrn=" . urlencode($lrn));
        exit;
    } else {
        $error = 'Failed to update status.';
    }
    mysqli_stmt_close($stmt);
}
?>

<?php if (!$student): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error ?: 'No student specified.'); ?>
    </div>
    <a href="student_list.php" class="btn btn-primary">
        <i class="bi bi-arrow-left"></i> Back to Student List
    </a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <!-- Student Information -->
            <div class="card shadow mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Student Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Personal Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">LRN:</th>
                                    <td><strong><?php echo htmlspecialchars($student['lrn']); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Birthdate:</th>
                                    <td><?php echo date('F d, Y', strtotime($student['birthdate'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Age:</th>
                                    <td><?php echo $student['age']; ?> years old</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                                </tr>
                                <tr>
                                    <th>Guardian:</th>
                                    <td><?php echo htmlspecialchars($student['guardian_name_contact'] ?: 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Enrollment Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Track/Strand:</th>
                                    <td><?php echo htmlspecialchars($student['strand_course']); ?></td>
                                </tr>
                                <tr>
                                    <th>Tuition Fee:</th>
                                    <td><strong>₱<?php echo number_format($student['tuition_fee'], 2); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php
                                        $badge_class = [
                                            'Pending' => 'warning',
                                            'Under Review' => 'info',
                                            'Approved' => 'success',
                                            'Rejected' => 'danger',
                                            'Enrolled' => 'primary',
                                            'Withdrawn' => 'secondary'
                                        ];
                                        $class = $badge_class[$student['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $class; ?> fs-6">
                                            <?php echo htmlspecialchars($student['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Application Date:</th>
                                    <td><?php echo date('F d, Y h:i A', strtotime($student['application_date'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?php echo date('F d, Y h:i A', strtotime($student['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card shadow mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Submitted Documents</h5>
                </div>
                <div class="card-body">
                    <?php if ($documents): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <p>
                                    <i class="bi bi-file-earmark-pdf"></i> <strong>Birth Certificate:</strong><br>
                                    <?php if ($documents['birth_certificate_url']): ?>
                                        <span class="badge bg-success">✓ Uploaded</span> 
                                        <?php echo htmlspecialchars($documents['birth_certificate_url']); ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </p>
                                <p>
                                    <i class="bi bi-file-earmark-pdf"></i> <strong>Diploma:</strong><br>
                                    <?php if ($documents['diploma_url']): ?>
                                        <span class="badge bg-success">✓ Uploaded</span> 
                                        <?php echo htmlspecialchars($documents['diploma_url']); ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <i class="bi bi-file-earmark-pdf"></i> <strong>Good Moral:</strong><br>
                                    <?php if ($documents['good_moral_url']): ?>
                                        <span class="badge bg-success">✓ Uploaded</span> 
                                        <?php echo htmlspecialchars($documents['good_moral_url']); ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </p>
                                <p>
                                    <i class="bi bi-file-earmark-pdf"></i> <strong>Report Card:</strong><br>
                                    <?php if ($documents['report_card_url']): ?>
                                        <span class="badge bg-success">✓ Uploaded</span> 
                                        <?php echo htmlspecialchars($documents['report_card_url']); ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">
                            <i class="bi bi-exclamation-circle"></i> No documents uploaded yet.
                        </p>
                    <?php endif; ?>
                    
                    <a href="upload_documents.php?lrn=<?php echo urlencode($student['lrn']); ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-upload"></i> Upload/Update Documents
                    </a>
                </div>
            </div>

            <!-- Enrollment History -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Enrollment History</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($history)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>Action</th>
                                        <th>Status Change</th>
                                        <th>Changed By</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $record): ?>
                                        <tr>
                                            <td><small><?php echo date('M d, Y h:i A', strtotime($record['changed_at'])); ?></small></td>
                                            <td><?php echo htmlspecialchars($record['action']); ?></td>
                                            <td>
                                                <?php if ($record['old_status']): ?>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($record['old_status']); ?></span>
                                                    →
                                                <?php endif; ?>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($record['new_status']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($record['changed_by_name'] ?: 'System'); ?></td>
                                            <td><small><?php echo htmlspecialchars($record['notes'] ?: '-'); ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No history records found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Registrar'): ?>
                <div class="card shadow mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-gear"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="update_status" value="1">
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status...</option>
                                    <option value="Pending" <?php echo $student['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Under Review" <?php echo $student['status'] === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                                    <option value="Approved" <?php echo $student['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo $student['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    <option value="Enrolled" <?php echo $student['status'] === 'Enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                                    <option value="Withdrawn" <?php echo $student['status'] === 'Withdrawn' ? 'selected' : ''; ?>>Withdrawn</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-list"></i> Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="student_list.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <a href="upload_documents.php?lrn=<?php echo urlencode($student['lrn']); ?>" class="btn btn-outline-success">
                        <i class="bi bi-upload"></i> Upload Documents
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="bi bi-printer"></i> Print Application
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
