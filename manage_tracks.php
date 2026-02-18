<?php
include("db_connection.php");

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success_message = "";
$error_message = "";

// Handle track operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Add new track
    if (isset($_POST['action']) && $_POST['action'] === 'add_track') {
        $strand_course = trim($_POST['strand_course']);
        $enrollment_fee = floatval($_POST['enrollment_fee']);
        $previous_school_records = trim($_POST['previous_school_records']);
        $student_photo_url = trim($_POST['student_photo_url']);
        
        if (!empty($strand_course) && $enrollment_fee > 0) {
            $stmt = $conn->prepare("INSERT INTO Track (strand_course, enrollment_fee, previous_school_records, student_photo_url) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $strand_course, $enrollment_fee, $previous_school_records, $student_photo_url);
            
            if ($stmt->execute()) {
                $success_message = "✅ Track added successfully! Track ID: " . $stmt->insert_id;
            } else {
                $error_message = "❌ Error adding track: " . htmlspecialchars($conn->error);
            }
            $stmt->close();
        } else {
            $error_message = "⚠️ Please fill in all required fields.";
        }
    }
    
    // Edit track
    if (isset($_POST['action']) && $_POST['action'] === 'edit_track') {
        $track_id = intval($_POST['track_id']);
        $strand_course = trim($_POST['strand_course']);
        $enrollment_fee = floatval($_POST['enrollment_fee']);
        $previous_school_records = trim($_POST['previous_school_records']);
        $student_photo_url = trim($_POST['student_photo_url']);
        
        if (!empty($strand_course) && $enrollment_fee > 0 && $track_id > 0) {
            $stmt = $conn->prepare("UPDATE Track SET strand_course = ?, enrollment_fee = ?, previous_school_records = ?, student_photo_url = ? WHERE track_id = ?");
            $stmt->bind_param("sdssi", $strand_course, $enrollment_fee, $previous_school_records, $student_photo_url, $track_id);
            
            if ($stmt->execute()) {
                $success_message = "✅ Track updated successfully!";
            } else {
                $error_message = "❌ Error updating track: " . htmlspecialchars($conn->error);
            }
            $stmt->close();
        } else {
            $error_message = "⚠️ Please fill in all required fields.";
        }
    }
    
    // Delete track
    if (isset($_POST['action']) && $_POST['action'] === 'delete_track') {
        $track_id = intval($_POST['track_id']);
        
        if ($track_id > 0) {
            // Check if track is being used by any applications
            $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM Application WHERE track_id = ?");
            $check_stmt->bind_param("i", $track_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $check_stmt->close();
            
            if ($count > 0) {
                $error_message = "⚠️ Cannot delete this track. It is being used by $count student enrollment(s). Please reassign those students first or use CASCADE delete.";
            } else {
                $stmt = $conn->prepare("DELETE FROM Track WHERE track_id = ?");
                $stmt->bind_param("i", $track_id);
                
                if ($stmt->execute()) {
                    $success_message = "✅ Track deleted successfully!";
                } else {
                    $error_message = "❌ Error deleting track: " . htmlspecialchars($conn->error);
                }
                $stmt->close();
            }
        }
    }
}

// Get all tracks
$tracks_query = "SELECT t.*, COUNT(a.lrn) as student_count 
                 FROM Track t 
                 LEFT JOIN Application a ON t.track_id = a.track_id 
                 GROUP BY t.track_id 
                 ORDER BY t.track_id";
$tracks_result = $conn->query($tracks_query);
$tracks = [];
if ($tracks_result) {
    while ($row = $tracks_result->fetch_assoc()) {
        $tracks[] = $row;
    }
}

// Get track being edited
$edit_track = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_stmt = $conn->prepare("SELECT * FROM Track WHERE track_id = ?");
    $edit_stmt->bind_param("i", $edit_id);
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    if ($edit_result->num_rows > 0) {
        $edit_track = $edit_result->fetch_assoc();
    }
    $edit_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tracks - Smart Online Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .management-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .track-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .track-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .badge-students {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🎓 Smart Online Enrollment - Track Management</span>
            <div>
                <span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="admin_dashboard.php" class="btn btn-sm btn-info me-2">Dashboard</a>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Add/Edit Track Form -->
            <div class="col-md-4">
                <div class="management-card p-4 mb-4">
                    <h3 class="mb-3"><?php echo $edit_track ? '✏️ Edit Track' : '➕ Add New Track'; ?></h3>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_track ? 'edit_track' : 'add_track'; ?>">
                        <?php if ($edit_track): ?>
                            <input type="hidden" name="track_id" value="<?php echo $edit_track['track_id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Track/Strand Name: *</label>
                            <input type="text" 
                                   name="strand_course" 
                                   class="form-control" 
                                   value="<?php echo $edit_track ? htmlspecialchars($edit_track['strand_course']) : ''; ?>"
                                   placeholder="e.g., STEM, ABM, HUMSS"
                                   required>
                            <small class="text-muted">Full name of the educational track or strand</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Enrollment Fee (₱): *</label>
                            <input type="number" 
                                   name="enrollment_fee" 
                                   class="form-control" 
                                   value="<?php echo $edit_track ? $edit_track['enrollment_fee'] : ''; ?>"
                                   placeholder="5000.00"
                                   step="0.01"
                                   min="0"
                                   required>
                            <small class="text-muted">Base enrollment fee for this track</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Required Documents:</label>
                            <input type="text" 
                                   name="previous_school_records" 
                                   class="form-control" 
                                   value="<?php echo $edit_track ? htmlspecialchars($edit_track['previous_school_records']) : ''; ?>"
                                   placeholder="e.g., Report Card, Diploma">
                            <small class="text-muted">Documents required from previous school</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Photo Requirements:</label>
                            <input type="text" 
                                   name="student_photo_url" 
                                   class="form-control" 
                                   value="<?php echo $edit_track ? htmlspecialchars($edit_track['student_photo_url']) : ''; ?>"
                                   placeholder="e.g., 2x2 ID photo">
                            <small class="text-muted">Photo specifications or requirements</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $edit_track ? '💾 Update Track' : '➕ Add Track'; ?>
                            </button>
                            <?php if ($edit_track): ?>
                                <a href="manage_tracks.php" class="btn btn-secondary">Cancel Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <!-- Quick Info -->
                <div class="management-card p-4">
                    <h5 class="mb-3">📋 Quick Info</h5>
                    <p><strong>Total Tracks:</strong> <?php echo count($tracks); ?></p>
                    <p><strong>Total Enrollments:</strong> 
                        <?php 
                        $total_enrollments = 0;
                        foreach ($tracks as $t) {
                            $total_enrollments += $t['student_count'];
                        }
                        echo $total_enrollments;
                        ?>
                    </p>
                    <hr>
                    <div class="d-grid gap-2">
                        <a href="admin_dashboard.php" class="btn btn-info">📊 Dashboard</a>
                        <a href="student_list.php" class="btn btn-secondary">👥 Students</a>
                        <a href="enrollment_form.php" class="btn btn-success">📝 Enroll</a>
                    </div>
                </div>
            </div>
            
            <!-- Track List -->
            <div class="col-md-8">
                <div class="management-card p-4">
                    <h3 class="mb-4">📚 All Tracks/Strands</h3>
                    
                    <?php if (count($tracks) == 0): ?>
                        <div class="alert alert-warning">
                            <strong>⚠️ No tracks available!</strong><br>
                            Add your first track using the form on the left to start enrolling students.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($tracks as $track): ?>
                                <div class="col-md-6">
                                    <div class="track-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-0">
                                                <span class="badge bg-primary">ID: <?php echo $track['track_id']; ?></span>
                                            </h5>
                                            <span class="badge badge-students">
                                                <?php echo $track['student_count']; ?> student(s)
                                            </span>
                                        </div>
                                        
                                        <h6 class="text-primary"><?php echo htmlspecialchars($track['strand_course']); ?></h6>
                                        
                                        <p class="mb-1">
                                            <strong>Fee:</strong> ₱<?php echo number_format($track['enrollment_fee'], 2); ?>
                                        </p>
                                        
                                        <?php if (!empty($track['previous_school_records'])): ?>
                                            <p class="mb-1 text-muted">
                                                <small><strong>Documents:</strong> <?php echo htmlspecialchars($track['previous_school_records']); ?></small>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($track['student_photo_url'])): ?>
                                            <p class="mb-2 text-muted">
                                                <small><strong>Photo:</strong> <?php echo htmlspecialchars($track['student_photo_url']); ?></small>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="btn-group btn-group-sm w-100" role="group">
                                            <a href="?edit=<?php echo $track['track_id']; ?>" 
                                               class="btn btn-outline-primary">
                                                ✏️ Edit
                                            </a>
                                            
                                            <?php if ($track['student_count'] == 0): ?>
                                                <form method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this track?');">
                                                    <input type="hidden" name="action" value="delete_track">
                                                    <input type="hidden" name="track_id" value="<?php echo $track['track_id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        🗑️ Delete
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary" disabled title="Cannot delete - has enrolled students">
                                                    🔒 In Use
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Track Statistics -->
                <div class="management-card p-4 mt-4">
                    <h4 class="mb-3">📊 Track Statistics</h4>
                    
                    <?php if (count($tracks) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Track/Strand</th>
                                        <th>Fee</th>
                                        <th>Students</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_revenue = 0;
                                    foreach ($tracks as $track): 
                                        $revenue = $track['enrollment_fee'] * $track['student_count'];
                                        $total_revenue += $revenue;
                                    ?>
                                        <tr>
                                            <td><?php echo $track['track_id']; ?></td>
                                            <td><?php echo htmlspecialchars($track['strand_course']); ?></td>
                                            <td>₱<?php echo number_format($track['enrollment_fee'], 2); ?></td>
                                            <td><?php echo $track['student_count']; ?></td>
                                            <td>₱<?php echo number_format($revenue, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-primary fw-bold">
                                        <td colspan="3">TOTAL</td>
                                        <td><?php echo $total_enrollments; ?></td>
                                        <td>₱<?php echo number_format($total_revenue, 2); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No track statistics available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
