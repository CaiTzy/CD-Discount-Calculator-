<?php
$page_title = "Home - Smart Enrollment System";
require_once("db_connection.php");
require_once("header.php");

// Get quick statistics
$total_students = 0;
$total_tracks = 0;

$count_query = "SELECT COUNT(*) as count FROM Application";
$result = $conn->query($count_query);
if ($result) {
    $total_students = $result->fetch_assoc()['count'];
}

$tracks_query = "SELECT COUNT(*) as count FROM Track WHERE is_active = 1";
$result = $conn->query($tracks_query);
if ($result) {
    $total_tracks = $result->fetch_assoc()['count'];
}
?>

<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <div class="p-5 bg-white rounded shadow-lg">
                <h1 class="display-3 mb-3" style="color: #667eea;">
                    <i class="bi bi-mortarboard-fill"></i> Smart Online Enrollment System
                </h1>
                <p class="lead text-muted mb-4">
                    Streamline your enrollment process with our modern, secure, and user-friendly platform
                </p>
                <?php if (!$is_logged_in): ?>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="enrollment_page.php" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-pencil-square"></i> Enroll Now
                        </a>
                        <a href="simple_login.php" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="bi bi-shield-lock"></i> Admin Login
                        </a>
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="admin_dashboard.php" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-speedometer2"></i> Go to Dashboard
                        </a>
                        <a href="student_list.php" class="btn btn-info btn-lg px-5 text-white">
                            <i class="bi bi-people"></i> View Students
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card text-center shadow h-100">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary" style="font-size: 4rem;"></i>
                    <h2 class="display-4 text-primary mt-3"><?php echo $total_students; ?></h2>
                    <h5>Total Students Enrolled</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center shadow h-100">
                <div class="card-body">
                    <i class="bi bi-book-fill text-success" style="font-size: 4rem;"></i>
                    <h2 class="display-4 text-success mt-3"><?php echo $total_tracks; ?></h2>
                    <h5>Available Tracks/Strands</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h2 class="text-center mb-4">Why Choose Our Enrollment System?</h2>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-lightning-charge-fill text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Quick & Easy</h4>
                    <p class="text-muted">Complete your enrollment in just a few minutes with our intuitive online form</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Secure</h4>
                    <p class="text-muted">Your data is protected with enterprise-grade security and encryption</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history text-info" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Track Status</h4>
                    <p class="text-muted">Monitor your application status and get real-time updates</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Tracks -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-book"></i> Available Tracks/Strands</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        $tracks_list = "SELECT track_code, strand_course, tuition_fee, capacity FROM Track WHERE is_active = 1 ORDER BY strand_course";
                        $tracks_result = $conn->query($tracks_list);
                        if ($tracks_result && $tracks_result->num_rows > 0):
                            while ($track = $tracks_result->fetch_assoc()):
                        ?>
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($track['track_code']); ?></span>
                                        <?php echo htmlspecialchars($track['strand_course']); ?>
                                    </h5>
                                    <small class="text-muted">Capacity: <?php echo $track['capacity']; ?> students</small>
                                </div>
                                <p class="mb-1">
                                    <strong>Tuition Fee:</strong> ₱<?php echo number_format($track['tuition_fee'], 2); ?>
                                </p>
                            </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Track information will be available soon.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <?php if (!$is_logged_in): ?>
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card text-center bg-light shadow-lg">
                <div class="card-body p-5">
                    <h2 class="mb-3">Ready to Get Started?</h2>
                    <p class="lead mb-4">Join thousands of students who have successfully enrolled through our platform</p>
                    <a href="enrollment_page.php" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-pencil-square"></i> Start Your Enrollment
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Enrollment Process -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h2 class="text-center mb-4">Simple Enrollment Process</h2>
        </div>
        <div class="col-md-3 text-center">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="badge bg-primary rounded-circle p-3 mb-3" style="font-size: 2rem;">1</div>
                    <h5>Fill Form</h5>
                    <p class="text-muted">Complete the enrollment form with your information</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="badge bg-primary rounded-circle p-3 mb-3" style="font-size: 2rem;">2</div>
                    <h5>Select Track</h5>
                    <p class="text-muted">Choose your preferred academic track or strand</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="badge bg-primary rounded-circle p-3 mb-3" style="font-size: 2rem;">3</div>
                    <h5>Submit</h5>
                    <p class="text-muted">Submit your application and get confirmation</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="badge bg-success rounded-circle p-3 mb-3" style="font-size: 2rem;">✓</div>
                    <h5>Done!</h5>
                    <p class="text-muted">You're officially enrolled!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("footer.php"); ?>
