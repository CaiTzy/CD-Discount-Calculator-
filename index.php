<?php
require_once 'config.php';
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Home - ' . SITE_NAME;
include 'header.php';

// Get basic statistics
$stats_sql = "
    SELECT 
        COUNT(*) as total_applications,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'Enrolled' THEN 1 ELSE 0 END) as enrolled
    FROM Application
";
$stats = db_fetch($conn, $stats_sql);

// Get available tracks
$tracks_sql = "SELECT * FROM Track ORDER BY strand_course";
$tracks_result = db_query($conn, $tracks_sql);
$tracks = mysqli_fetch_all($tracks_result, MYSQLI_ASSOC);
?>

<div class="jumbotron bg-light p-5 rounded-3 shadow-sm mb-4">
    <h1 class="display-4">
        <i class="bi bi-mortarboard-fill text-primary"></i> 
        Welcome to Smart Online Enrollment System
    </h1>
    <p class="lead">
        Streamline your student enrollment process with our modern, efficient online system.
    </p>
    <hr class="my-4">
    <p>
        Manage student applications, track documents, and process enrollments all in one place.
    </p>
    <p class="lead">
        <a class="btn btn-primary btn-lg" href="enrollment_form.php" role="button">
            <i class="bi bi-person-plus"></i> Start New Enrollment
        </a>
        <a class="btn btn-success btn-lg" href="student_list.php" role="button">
            <i class="bi bi-list"></i> View Students
        </a>
    </p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-2"><?php echo $stats['total_applications']; ?></h3>
                <p class="text-muted">Total Applications</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-clock-fill text-warning" style="font-size: 3rem;"></i>
                <h3 class="mt-2"><?php echo $stats['pending']; ?></h3>
                <p class="text-muted">Pending Applications</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <h3 class="mt-2"><?php echo $stats['enrolled']; ?></h3>
                <p class="text-muted">Enrolled Students</p>
            </div>
        </div>
    </div>
</div>

<!-- Available Tracks -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-book"></i> Available Tracks & Strands</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($tracks as $track): ?>
                <div class="col-md-6 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <?php echo htmlspecialchars($track['strand_course']); ?>
                            </h5>
                            <?php if ($track['description']): ?>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($track['description']); ?>
                                </p>
                            <?php endif; ?>
                            <p class="mb-0">
                                <strong>Tuition Fee:</strong> 
                                <span class="text-success fs-5">₱<?php echo number_format($track['tuition_fee'], 2); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Features -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-lightning-fill text-warning" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Fast & Efficient</h5>
                <p class="card-text">
                    Quick enrollment process with auto-calculated age, duplicate detection, and instant validation.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-check-fill text-success" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Document Management</h5>
                <p class="card-text">
                    Upload and manage student documents digitally. Track submission status in real-time.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-graph-up-arrow text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-3">Advanced Search & Filter</h5>
                <p class="card-text">
                    Powerful search and filtering capabilities with pagination for easy student management.
                </p>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION['role'] === 'Admin'): ?>
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle"></i> Admin Access</h5>
        <p class="mb-0">
            You have administrator access. Visit the <a href="admin_dashboard.php" class="alert-link">Admin Dashboard</a> 
            for comprehensive statistics and management tools.
        </p>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
