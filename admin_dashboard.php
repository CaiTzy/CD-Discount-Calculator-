<?php
include("db_connection.php");

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle track management
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_track') {
        $strand_course = trim($_POST['strand_course']);
        $enrollment_fee = floatval($_POST['enrollment_fee']);
        
        $stmt = $conn->prepare("INSERT INTO Track (strand_course, enrollment_fee) VALUES (?, ?)");
        $stmt->bind_param("sd", $strand_course, $enrollment_fee);
        $stmt->execute();
        $stmt->close();
        
        header("Location: admin_dashboard.php");
        exit();
    }
}

// Get tracks
$tracks_query = "SELECT * FROM Track ORDER BY track_id";
$tracks_result = $conn->query($tracks_query);
$tracks = [];
if ($tracks_result) {
    while ($row = $tracks_result->fetch_assoc()) {
        $tracks[] = $row;
    }
}

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_students,
    SUM(total_amount) as total_revenue,
    SUM(CASE WHEN payment_status = 'Pending' THEN 1 ELSE 0 END) as pending_payments,
    SUM(CASE WHEN payment_status = 'Paid' THEN 1 ELSE 0 END) as paid_students
    FROM Application";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get enrollment by track
$track_stats_query = "SELECT t.strand_course, COUNT(a.lrn) as student_count, SUM(a.total_amount) as track_revenue
                      FROM Track t
                      LEFT JOIN Application a ON t.track_id = a.track_id
                      GROUP BY t.track_id, t.strand_course";
$track_stats_result = $conn->query($track_stats_query);
$track_stats = [];
if ($track_stats_result) {
    while ($row = $track_stats_result->fetch_assoc()) {
        $track_stats[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Online Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🎓 Smart Online Enrollment - Admin</span>
            <div>
                <span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="student_list.php" class="btn btn-sm btn-info me-2">View Students</a>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Statistics Section -->
            <div class="col-md-12">
                <div class="dashboard-card p-4 mb-4">
                    <h2 class="mb-4">📊 Dashboard Overview</h2>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h3><?php echo $stats['total_students'] ?? 0; ?></h3>
                                <p class="mb-0">Total Enrollments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h3>₱<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></h3>
                                <p class="mb-0">Total Revenue</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h3><?php echo $stats['paid_students'] ?? 0; ?></h3>
                                <p class="mb-0">Paid Students</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center">
                                <h3><?php echo $stats['pending_payments'] ?? 0; ?></h3>
                                <p class="mb-0">Pending Payments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Track Management -->
            <div class="col-md-6">
                <div class="dashboard-card p-4 mb-4">
                    <h3 class="mb-3">🎯 Manage Tracks</h3>
                    
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="action" value="add_track">
                        <div class="mb-3">
                            <label class="form-label">Track/Strand Name:</label>
                            <input type="text" name="strand_course" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Enrollment Fee (₱):</label>
                            <input type="number" step="0.01" name="enrollment_fee" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add New Track</button>
                    </form>
                    
                    <h5 class="mt-4 mb-3">Current Tracks:</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Track/Strand</th>
                                    <th>Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tracks as $track): ?>
                                    <tr>
                                        <td><?php echo $track['track_id']; ?></td>
                                        <td><?php echo htmlspecialchars($track['strand_course']); ?></td>
                                        <td>₱<?php echo number_format($track['enrollment_fee'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Track Statistics -->
            <div class="col-md-6">
                <div class="dashboard-card p-4 mb-4">
                    <h3 class="mb-3">📈 Enrollment by Track</h3>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Track/Strand</th>
                                    <th>Students</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($track_stats as $track_stat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($track_stat['strand_course']); ?></td>
                                        <td><?php echo $track_stat['student_count']; ?></td>
                                        <td>₱<?php echo number_format($track_stat['track_revenue'] ?? 0, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
