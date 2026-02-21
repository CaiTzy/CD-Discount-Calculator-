<?php
require_once 'config.php';
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is admin
if ($_SESSION['role'] !== 'Admin') {
    header('Location: index.php');
    exit;
}

$page_title = 'Admin Dashboard';
include 'header.php';

// Get comprehensive statistics
$stats_sql = "
    SELECT 
        COUNT(*) as total_applications,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'Under Review' THEN 1 ELSE 0 END) as under_review,
        SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'Enrolled' THEN 1 ELSE 0 END) as enrolled,
        SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected
    FROM Application
";
$stats = db_fetch($conn, $stats_sql);

// Get track statistics
$track_stats_sql = "
    SELECT 
        t.strand_course,
        COUNT(a.lrn) as student_count,
        SUM(CASE WHEN a.status = 'Enrolled' THEN 1 ELSE 0 END) as enrolled_count,
        t.tuition_fee,
        SUM(CASE WHEN a.status = 'Enrolled' THEN t.tuition_fee ELSE 0 END) as revenue
    FROM Track t
    LEFT JOIN Application a ON t.track_id = a.track_id
    GROUP BY t.track_id, t.strand_course, t.tuition_fee
    ORDER BY student_count DESC
";
$track_stats_result = db_query($conn, $track_stats_sql);
$track_stats = mysqli_fetch_all($track_stats_result, MYSQLI_ASSOC);

// Get recent applications
$recent_sql = "
    SELECT 
        a.lrn,
        CONCAT(a.first_name, ' ', a.last_name) as full_name,
        a.status,
        a.application_date,
        t.strand_course
    FROM Application a
    JOIN Track t ON a.track_id = t.track_id
    ORDER BY a.application_date DESC
    LIMIT 10
";
$recent_result = db_query($conn, $recent_sql);
$recent_applications = mysqli_fetch_all($recent_result, MYSQLI_ASSOC);

// Get document completion rate
$doc_stats_sql = "
    SELECT 
        COUNT(DISTINCT a.lrn) as total_students,
        COUNT(DISTINCT d.lrn) as with_documents
    FROM Application a
    LEFT JOIN Document d ON a.lrn = d.lrn
";
$doc_stats = db_fetch($conn, $doc_stats_sql);
$doc_completion_rate = $doc_stats['total_students'] > 0 
    ? round(($doc_stats['with_documents'] / $doc_stats['total_students']) * 100, 1) 
    : 0;
?>

<h2 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>

<!-- Main Statistics -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center shadow-sm bg-primary text-white">
            <div class="card-body">
                <h6>Total Applications</h6>
                <h2><?php echo $stats['total_applications']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center shadow-sm bg-warning text-dark">
            <div class="card-body">
                <h6>Pending</h6>
                <h2><?php echo $stats['pending']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center shadow-sm bg-info text-white">
            <div class="card-body">
                <h6>Under Review</h6>
                <h2><?php echo $stats['under_review']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center shadow-sm bg-success text-white">
            <div class="card-body">
                <h6>Approved</h6>
                <h2><?php echo $stats['approved']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center shadow-sm" style="background-color: #6610f2; color: white;">
            <div class="card-body">
                <h6>Enrolled</h6>
                <h2><?php echo $stats['enrolled']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center shadow-sm bg-danger text-white">
            <div class="card-body">
                <h6>Rejected</h6>
                <h2><?php echo $stats['rejected']; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Track Statistics -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Track/Strand Statistics</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Track/Strand</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Enrolled</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_revenue = 0;
                            foreach ($track_stats as $track): 
                                $total_revenue += $track['revenue'];
                            ?>
                                <tr>
                                    <td><small><?php echo htmlspecialchars($track['strand_course']); ?></small></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?php echo $track['student_count']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?php echo $track['enrolled_count']; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <small>₱<?php echo number_format($track['revenue'], 2); ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3" class="text-end">Total Revenue:</th>
                                <th class="text-end">₱<?php echo number_format($total_revenue, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Applications</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Track</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_applications as $app): ?>
                                <tr>
                                    <td>
                                        <a href="view_application.php?lrn=<?php echo urlencode($app['lrn']); ?>">
                                            <?php echo htmlspecialchars($app['full_name']); ?>
                                        </a>
                                    </td>
                                    <td><small><?php echo htmlspecialchars(substr($app['strand_course'], 0, 20)); ?>...</small></td>
                                    <td>
                                        <?php
                                        $badge_class = [
                                            'Pending' => 'warning',
                                            'Under Review' => 'info',
                                            'Approved' => 'success',
                                            'Rejected' => 'danger',
                                            'Enrolled' => 'primary'
                                        ];
                                        $class = $badge_class[$app['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $class; ?>"><?php echo $app['status']; ?></span>
                                    </td>
                                    <td><small><?php echo date('M d', strtotime($app['application_date'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Metrics -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-file-check"></i> Document Completion</h6>
            </div>
            <div class="card-body text-center">
                <h1 class="display-4"><?php echo $doc_completion_rate; ?>%</h1>
                <p class="text-muted">
                    <?php echo $doc_stats['with_documents']; ?> of <?php echo $doc_stats['total_students']; ?> students
                </p>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" 
                         style="width: <?php echo $doc_completion_rate; ?>%" 
                         aria-valuenow="<?php echo $doc_completion_rate; ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle"></i> Approval Rate</h6>
            </div>
            <div class="card-body text-center">
                <?php 
                $approval_rate = $stats['total_applications'] > 0 
                    ? round((($stats['approved'] + $stats['enrolled']) / $stats['total_applications']) * 100, 1)
                    : 0;
                ?>
                <h1 class="display-4"><?php echo $approval_rate; ?>%</h1>
                <p class="text-muted">
                    <?php echo ($stats['approved'] + $stats['enrolled']); ?> approved/enrolled
                </p>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?php echo $approval_rate; ?>%" 
                         aria-valuenow="<?php echo $approval_rate; ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Pending Review</h6>
            </div>
            <div class="card-body text-center">
                <h1 class="display-4"><?php echo $stats['pending'] + $stats['under_review']; ?></h1>
                <p class="text-muted">
                    <?php echo $stats['pending']; ?> pending + <?php echo $stats['under_review']; ?> reviewing
                </p>
                <a href="student_list.php?status=Pending" class="btn btn-warning btn-sm">
                    <i class="bi bi-eye"></i> View Pending
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="enrollment_form.php" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus"></i> New Application
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="student_list.php" class="btn btn-success w-100">
                            <i class="bi bi-list"></i> View All Students
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="student_list.php?status=Pending" class="btn btn-warning w-100">
                            <i class="bi bi-clock"></i> Pending Applications
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="upload_documents.php" class="btn btn-info w-100">
                            <i class="bi bi-upload"></i> Upload Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
