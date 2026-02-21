<?php
require_once 'config.php';
require_login();

$db = Database::getInstance();
$conn = $db->getConnection();

// Get statistics
$stats = [];

// Total applications
$query = "SELECT COUNT(*) as total FROM Application";
$result = $conn->query($query);
$stats['total_applications'] = $result->fetch_assoc()['total'];

// Pending applications
$query = "SELECT COUNT(*) as total FROM Application WHERE application_status = 'Pending'";
$result = $conn->query($query);
$stats['pending'] = $result->fetch_assoc()['total'];

// Approved applications
$query = "SELECT COUNT(*) as total FROM Application WHERE application_status = 'Approved'";
$result = $conn->query($query);
$stats['approved'] = $result->fetch_assoc()['total'];

// Enrolled students
$query = "SELECT COUNT(*) as total FROM Application WHERE application_status = 'Enrolled'";
$result = $conn->query($query);
$stats['enrolled'] = $result->fetch_assoc()['total'];

// Recent applications
$recent_query = "SELECT a.lrn, a.first_name, a.middle_name, a.last_name, a.email, 
                 t.strand_course, a.application_status, a.application_date,
                 d.verification_status
                 FROM Application a
                 INNER JOIN Track t ON a.track_id = t.track_id
                 LEFT JOIN Document d ON a.lrn = d.lrn
                 ORDER BY a.created_at DESC
                 LIMIT 20";
$recent_applications = $conn->query($recent_query);

// Track statistics
$track_stats_query = "SELECT * FROM vw_track_statistics ORDER BY strand_course";
$track_stats = $conn->query($track_stats_query);

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $lrn = sanitize_input($_POST['lrn']);
        $new_status = sanitize_input($_POST['new_status']);
        $changed_by = $_SESSION['full_name'];
        
        $update_query = "UPDATE Application SET application_status = ? WHERE lrn = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $new_status, $lrn);
        
        if ($stmt->execute()) {
            // Log to history
            $history_query = "INSERT INTO Enrollment_History (lrn, new_status, changed_by) VALUES (?, ?, ?)";
            $history_stmt = $conn->prepare($history_query);
            $history_stmt->bind_param("sss", $lrn, $new_status, $changed_by);
            $history_stmt->execute();
            
            header('Location: admin_dashboard.php?success=1');
            exit();
        }
    }
    
    if ($_POST['action'] === 'update_verification') {
        $lrn = sanitize_input($_POST['lrn']);
        $verification_status = sanitize_input($_POST['verification_status']);
        $verified_by = $_SESSION['full_name'];
        
        $update_query = "UPDATE Document SET verification_status = ?, verified_by = ?, verified_at = CURRENT_TIMESTAMP WHERE lrn = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sss", $verification_status, $verified_by, $lrn);
        
        if ($stmt->execute()) {
            header('Location: admin_dashboard.php?success=1');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-4">
                    <h4 class="mb-4">🎓 Enrollment</h4>
                    <div class="mb-4">
                        <small class="text-white-50">Logged in as:</small><br>
                        <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong><br>
                        <small class="badge bg-light text-dark"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
                    </div>
                </div>
                
                <nav class="nav flex-column px-3">
                    <a class="nav-link active" href="admin_dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="manage_applications.php">
                        <i class="bi bi-file-earmark-text"></i> Applications
                    </a>
                    <a class="nav-link" href="manage_tracks.php">
                        <i class="bi bi-book"></i> Tracks/Strands
                    </a>
                    <a class="nav-link" href="reports.php">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                    <hr class="text-white">
                    <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2 class="mb-4">Dashboard</h2>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Operation completed successfully!</div>
                <?php endif; ?>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left-color: #007bff;">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Total Applications</h6>
                                <h2 class="mb-0"><?php echo $stats['total_applications']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left-color: #ffc107;">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Pending Review</h6>
                                <h2 class="mb-0"><?php echo $stats['pending']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left-color: #28a745;">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Approved</h6>
                                <h2 class="mb-0"><?php echo $stats['approved']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card" style="border-left-color: #17a2b8;">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Enrolled</h6>
                                <h2 class="mb-0"><?php echo $stats['enrolled']; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Track Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Track Enrollment Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Track/Strand</th>
                                        <th>Code</th>
                                        <th>Capacity</th>
                                        <th>Enrolled</th>
                                        <th>Available</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($track = $track_stats->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($track['strand_course']); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($track['track_code']); ?></span></td>
                                            <td><?php echo $track['capacity']; ?></td>
                                            <td><?php echo $track['enrolled_students']; ?></td>
                                            <td><?php echo $track['available_slots']; ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar <?php echo $track['enrollment_percentage'] >= 80 ? 'bg-danger' : ($track['enrollment_percentage'] >= 50 ? 'bg-warning' : 'bg-success'); ?>" 
                                                         style="width: <?php echo $track['enrollment_percentage']; ?>%">
                                                        <?php echo number_format($track['enrollment_percentage'], 1); ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Applications -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Applications</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>LRN</th>
                                        <th>Student Name</th>
                                        <th>Track</th>
                                        <th>Application Status</th>
                                        <th>Document Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($app = $recent_applications->fetch_assoc()): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($app['lrn']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($app['first_name'] . ' ' . ($app['middle_name'] ? $app['middle_name'] . ' ' : '') . $app['last_name']); ?></td>
                                            <td><small><?php echo htmlspecialchars($app['strand_course']); ?></small></td>
                                            <td>
                                                <?php
                                                $status_colors = [
                                                    'Pending' => 'warning',
                                                    'Under Review' => 'info',
                                                    'Approved' => 'success',
                                                    'Rejected' => 'danger',
                                                    'Enrolled' => 'primary',
                                                    'Withdrawn' => 'secondary'
                                                ];
                                                $color = $status_colors[$app['application_status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?> badge-status">
                                                    <?php echo htmlspecialchars($app['application_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($app['verification_status']): ?>
                                                    <span class="badge bg-<?php echo $app['verification_status'] === 'Verified' ? 'success' : 'warning'; ?> badge-status">
                                                        <?php echo htmlspecialchars($app['verification_status']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary badge-status">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><small><?php echo date('M d, Y', strtotime($app['application_date'])); ?></small></td>
                                            <td>
                                                <a href="view_application.php?lrn=<?php echo urlencode($app['lrn']); ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
