<?php
$page_title = "All Features - Smart Enrollment System";
require_once("db_connection.php");
require_once("header.php");

// Require login to access this page
if (!$is_logged_in) {
    header("Location: simple_login.php");
    exit();
}
?>

<div class="container">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h1 class="display-4 mb-3">
                <i class="bi bi-grid-3x3-gap text-primary"></i> All Features
            </h1>
            <p class="lead text-muted">Complete Smart Online Enrollment System</p>
            <p class="text-muted">Access all features and capabilities from this centralized hub</p>
        </div>
    </div>

    <!-- Dashboard & Analytics -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="border-bottom pb-2 mb-3">
                <i class="bi bi-speedometer2 text-primary"></i> Dashboard & Analytics
            </h3>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-grid text-info"></i> Main Dashboard
                    </h5>
                    <p class="card-text">
                        Advanced administrative dashboard with real-time statistics, recent applications, track analytics, and status management.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> Real-time statistics</li>
                        <li><i class="bi bi-check-circle text-success"></i> Track enrollment</li>
                        <li><i class="bi bi-check-circle text-success"></i> Status updates</li>
                        <li><i class="bi bi-check-circle text-success"></i> Recent applications</li>
                    </ul>
                    <a href="admin_dashboard.php" class="btn btn-primary">
                        <i class="bi bi-arrow-right"></i> Open Dashboard
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-table text-success"></i> Simple Student List
                    </h5>
                    <p class="card-text">
                        Simplified view of all students with search, filter, and pagination capabilities. Perfect for quick lookups.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> Search by LRN/name/email</li>
                        <li><i class="bi bi-check-circle text-success"></i> Filter by status</li>
                        <li><i class="bi bi-check-circle text-success"></i> Pagination (15 per page)</li>
                        <li><i class="bi bi-check-circle text-success"></i> Statistics cards</li>
                    </ul>
                    <a href="student_list.php" class="btn btn-success">
                        <i class="bi bi-arrow-right"></i> View Students
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Management -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="border-bottom pb-2 mb-3">
                <i class="bi bi-pencil-square text-primary"></i> Enrollment Management
            </h3>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-file-earmark-plus text-primary"></i> Simple Enrollment
                    </h5>
                    <p class="card-text">
                        Quick enrollment form with essential fields, auto-age calculation, and duplicate detection.
                    </p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check2"></i> Auto-age calculation</li>
                        <li><i class="bi bi-check2"></i> Duplicate LRN check</li>
                        <li><i class="bi bi-check2"></i> Simple interface</li>
                    </ul>
                    <a href="enrollment_page.php" class="btn btn-sm btn-primary">
                        <i class="bi bi-arrow-right"></i> Simple Form
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-file-earmark-text text-info"></i> Advanced Enrollment
                    </h5>
                    <p class="card-text">
                        Comprehensive enrollment with complete student information, guardian details, and academic records.
                    </p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check2"></i> Complete student info</li>
                        <li><i class="bi bi-check2"></i> Guardian details</li>
                        <li><i class="bi bi-check2"></i> Academic records</li>
                    </ul>
                    <a href="enrollment_form.php" class="btn btn-sm btn-info">
                        <i class="bi bi-arrow-right"></i> Advanced Form
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-eye text-secondary"></i> View Application
                    </h5>
                    <p class="card-text">
                        Detailed view of student applications with complete information and history tracking.
                    </p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check2"></i> Complete details</li>
                        <li><i class="bi bi-check2"></i> Document status</li>
                        <li><i class="bi bi-check2"></i> Change history</li>
                    </ul>
                    <a href="student_list.php" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-right"></i> Search Student
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Management -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="border-bottom pb-2 mb-3">
                <i class="bi bi-cloud-upload text-primary"></i> Document Management
            </h3>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-upload text-warning"></i> Upload Documents
                    </h5>
                    <p class="card-text">
                        Upload and manage student documents including certificates, diplomas, and other required files.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-file-earmark"></i> Birth Certificate</li>
                        <li><i class="bi bi-file-earmark"></i> Diploma</li>
                        <li><i class="bi bi-file-earmark"></i> Good Moral Certificate</li>
                        <li><i class="bi bi-file-earmark"></i> Report Card</li>
                        <li><i class="bi bi-file-earmark"></i> 2x2 Photo</li>
                        <li><i class="bi bi-file-earmark"></i> Transfer Credentials</li>
                    </ul>
                    <a href="upload_documents.php" class="btn btn-warning text-white">
                        <i class="bi bi-cloud-upload"></i> Upload Documents
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm hover-card bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle text-info"></i> Document Requirements
                    </h5>
                    <p class="card-text">Required documents for enrollment:</p>
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><i class="bi bi-check2-circle text-success"></i> Birth Certificate</td>
                                <td><span class="badge bg-danger">Required</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-check2-circle text-success"></i> Report Card</td>
                                <td><span class="badge bg-danger">Required</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-check2-circle text-success"></i> Good Moral</td>
                                <td><span class="badge bg-warning">Recommended</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-check2-circle text-success"></i> 2x2 Photo</td>
                                <td><span class="badge bg-warning">Recommended</span></td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-check2-circle text-success"></i> Others</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="border-bottom pb-2 mb-3">
                <i class="bi bi-shield-lock text-primary"></i> Authentication & Access
            </h3>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-box-arrow-in-right text-success"></i> Simple Login
                    </h5>
                    <p class="card-text">
                        Quick login page with minimal interface for fast access.
                    </p>
                    <p><strong>Current User:</strong><br>
                    <?php echo htmlspecialchars($user_name); ?> (<?php echo htmlspecialchars($user_role); ?>)</p>
                    <a href="simple_logout.php" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout (Simple)
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-shield-check text-primary"></i> Advanced Login
                    </h5>
                    <p class="card-text">
                        Full-featured login with additional security options.
                    </p>
                    <p><strong>Session Info:</strong><br>
                    Role: <?php echo htmlspecialchars($user_role); ?><br>
                    Active Session</p>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout (Advanced)
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm hover-card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people"></i> Available Roles
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-person-badge"></i> Administrator</li>
                        <li><i class="bi bi-person"></i> Staff</li>
                        <li><i class="bi bi-person"></i> Registrar</li>
                        <li><i class="bi bi-person"></i> Principal</li>
                        <li><i class="bi bi-person"></i> Teacher</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Database & Configuration -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="border-bottom pb-2 mb-3">
                <i class="bi bi-database text-primary"></i> Database & System
            </h3>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-server text-primary"></i> Database Connection
                    </h5>
                    <p class="card-text">Two connection methods available:</p>
                    <ul class="list-unstyled">
                        <li><strong>Simple:</strong> db_connection.php
                            <ul>
                                <li>Direct mysqli connection</li>
                                <li>sanitize_input() function</li>
                            </ul>
                        </li>
                        <li><strong>Advanced:</strong> config.php
                            <ul>
                                <li>Singleton Database class</li>
                                <li>Advanced configuration</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-table text-success"></i> Database Tables
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-check2"></i> <strong>Track</strong> - Academic tracks/strands</li>
                        <li><i class="bi bi-check2"></i> <strong>Application</strong> - Student applications</li>
                        <li><i class="bi bi-check2"></i> <strong>Document</strong> - Document records</li>
                        <li><i class="bi bi-check2"></i> <strong>Users</strong> - Admin/staff accounts</li>
                        <li><i class="bi bi-check2"></i> <strong>Enrollment_History</strong> - Audit trail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Data -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-database-fill-check text-success"></i> Sample Data Included
                    </h5>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h2 class="text-primary">15</h2>
                            <p class="text-muted">Sample Students</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h2 class="text-success">6</h2>
                            <p class="text-muted">User Accounts</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h2 class="text-info">5</h2>
                            <p class="text-muted">Academic Tracks</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h2 class="text-warning">34+</h2>
                            <p class="text-muted">History Records</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentation -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="border-bottom pb-2 mb-3">
                <i class="bi bi-book text-primary"></i> Documentation
            </h3>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-file-text"></i> COMPLETE_SETUP_GUIDE.md</h6>
                    <p class="small">Full setup instructions with 3-step quickstart</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-file-text"></i> NEW_COMPONENTS_README.md</h6>
                    <p class="small">Component documentation and usage guide</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-file-text"></i> ARCHITECTURE.md</h6>
                    <p class="small">System architecture and data flow diagrams</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-file-text"></i> QUICKSTART.md</h6>
                    <p class="small">5-minute setup guide</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-file-text"></i> DATA_VERIFICATION.md</h6>
                    <p class="small">Database verification queries</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-file-text"></i> README.md</h6>
                    <p class="small">Main project documentation</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card shadow-lg bg-primary text-white">
                <div class="card-body text-center p-4">
                    <h3 class="mb-3"><i class="bi bi-lightning-fill"></i> Quick Actions</h3>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <a href="enrollment_page.php" class="btn btn-light btn-lg">
                            <i class="bi bi-person-plus"></i> New Enrollment
                        </a>
                        <a href="student_list.php" class="btn btn-light btn-lg">
                            <i class="bi bi-search"></i> Search Students
                        </a>
                        <a href="admin_dashboard.php" class="btn btn-light btn-lg">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="upload_documents.php" class="btn btn-light btn-lg">
                            <i class="bi bi-upload"></i> Upload Docs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>

<?php require_once("footer.php"); ?>
