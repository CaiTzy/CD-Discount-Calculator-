<?php
require_once("db_connection.php");
session_start();

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Code Index - Smart Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .code-card {
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }
        .code-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .category-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0 20px 0;
        }
        .file-badge {
            font-size: 0.85em;
            padding: 5px 10px;
        }
        .stats-box {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .code-link {
            text-decoration: none;
            color: inherit;
        }
        .code-link:hover {
            color: #007bff;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container my-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4"><i class="bi bi-code-square"></i> Master Code Index</h1>
            <p class="lead">Complete catalog of all code files in the Smart Enrollment System</p>
        </div>

        <!-- Statistics Overview -->
        <div class="stats-box">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2><i class="bi bi-file-code"></i> 31</h2>
                    <p>PHP Files</p>
                </div>
                <div class="col-md-3">
                    <h2><i class="bi bi-database"></i> 2</h2>
                    <p>SQL Files</p>
                </div>
                <div class="col-md-3">
                    <h2><i class="bi bi-book"></i> 10</h2>
                    <p>Documentation</p>
                </div>
                <div class="col-md-3">
                    <h2><i class="bi bi-star"></i> 300+</h2>
                    <p>Features</p>
                </div>
            </div>
        </div>

        <!-- Core Pages -->
        <div class="category-header">
            <h2><i class="bi bi-house-door"></i> Core Pages (6 files)</h2>
            <p class="mb-0">Main entry points and navigation</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> index.php</h5>
                        <p class="card-text">Landing page - First entry point</p>
                        <span class="badge bg-primary file-badge">Entry Point</span>
                        <a href="index.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> home.php</h5>
                        <p class="card-text">Homepage with statistics and features</p>
                        <span class="badge bg-success file-badge">Popular</span>
                        <a href="home.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> master_index.php</h5>
                        <p class="card-text">Visual code browser (This page!)</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-info file-badge">You are here</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> all_features.php</h5>
                        <p class="card-text">Complete feature hub with 200+ features</p>
                        <span class="badge bg-primary file-badge">Feature Hub</span>
                        <a href="all_features.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> test_system.php</h5>
                        <p class="card-text">Comprehensive system testing interface</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <a href="test_system.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> limbo.activity4.php</h5>
                        <p class="card-text">Original CD Calculator (legacy)</p>
                        <span class="badge bg-secondary file-badge">Legacy</span>
                        <a href="limbo.activity4.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Management -->
        <div class="category-header">
            <h2><i class="bi bi-pencil-square"></i> Enrollment Management (3 files)</h2>
            <p class="mb-0">Student enrollment forms and application viewing</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> enrollment_page.php</h5>
                        <p class="card-text">Simple enrollment form with auto-age calculation</p>
                        <span class="badge bg-success file-badge">Simple</span>
                        <a href="enrollment_page.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> enrollment_form.php</h5>
                        <p class="card-text">Advanced enrollment form with pricing</p>
                        <span class="badge bg-primary file-badge">Advanced</span>
                        <a href="enrollment_form.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> view_application.php</h5>
                        <p class="card-text">View detailed student application</p>
                        <span class="badge bg-info file-badge">Read-only</span>
                        <a href="view_application.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Management -->
        <div class="category-header">
            <h2><i class="bi bi-people"></i> Student Management (2 files)</h2>
            <p class="mb-0">Student lists and dashboards</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> student_list.php</h5>
                        <p class="card-text">Simple student list with search/filter/pagination</p>
                        <span class="badge bg-success file-badge">Simple</span>
                        <span class="badge bg-primary file-badge">15/page</span>
                        <a href="student_list.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> admin_dashboard.php</h5>
                        <p class="card-text">Advanced dashboard with analytics</p>
                        <span class="badge bg-primary file-badge">Advanced</span>
                        <span class="badge bg-warning file-badge">Admin Only</span>
                        <a href="admin_dashboard.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Management -->
        <div class="category-header">
            <h2><i class="bi bi-file-earmark-text"></i> Document Management (2 files)</h2>
            <p class="mb-0">Document upload and API</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> upload_documents.php</h5>
                        <p class="card-text">Document upload interface (6 document types)</p>
                        <span class="badge bg-success file-badge">Upload</span>
                        <a href="upload_documents.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> api_documents.php</h5>
                        <p class="card-text">Document verification API (JSON)</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-info file-badge">API</span>
                        <a href="api_documents.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authentication -->
        <div class="category-header">
            <h2><i class="bi bi-shield-lock"></i> Authentication (4 files)</h2>
            <p class="mb-0">Login and logout handlers</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> simple_login.php</h5>
                        <p class="card-text">Simple login interface</p>
                        <span class="badge bg-success file-badge">Simple</span>
                        <a href="simple_login.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> login.php</h5>
                        <p class="card-text">Advanced login with last login tracking</p>
                        <span class="badge bg-primary file-badge">Advanced</span>
                        <a href="login.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> simple_logout.php</h5>
                        <p class="card-text">Simple logout handler</p>
                        <span class="badge bg-secondary file-badge">Handler</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> logout.php</h5>
                        <p class="card-text">Advanced logout handler</p>
                        <span class="badge bg-secondary file-badge">Handler</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Management -->
        <div class="category-header">
            <h2><i class="bi bi-person-gear"></i> User Management (1 file)</h2>
            <p class="mb-0">User CRUD operations</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> manage_users.php</h5>
                        <p class="card-text">Add, edit, delete users with role management</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-danger file-badge">Admin Only</span>
                        <a href="manage_users.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports & Analytics -->
        <div class="category-header">
            <h2><i class="bi bi-graph-up"></i> Reports & Analytics (2 files)</h2>
            <p class="mb-0">Data reports and advanced search</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> reports.php</h5>
                        <p class="card-text">8 different report types with CSV export</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-success file-badge">8 Reports</span>
                        <a href="reports.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> advanced_search.php</h5>
                        <p class="card-text">Multi-field search with filters and export</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <a href="advanced_search.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Endpoints -->
        <div class="category-header">
            <h2><i class="bi bi-cloud-arrow-up"></i> API Endpoints (3 files)</h2>
            <p class="mb-0">RESTful JSON APIs</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> api_students.php</h5>
                        <p class="card-text">Student CRUD API (GET/POST/PUT/DELETE)</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-info file-badge">REST API</span>
                        <a href="api_students.php?action=list" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> api_tracks.php</h5>
                        <p class="card-text">Track management API (GET/POST/PUT/DELETE)</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-info file-badge">REST API</span>
                        <a href="api_tracks.php?action=list" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> api_documents.php</h5>
                        <p class="card-text">Document verification API (JSON)</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-info file-badge">REST API</span>
                        <a href="api_documents.php?action=list" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Management -->
        <div class="category-header">
            <h2><i class="bi bi-gear"></i> System Management (6 files)</h2>
            <p class="mb-0">Configuration, backup, and utilities</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> settings.php</h5>
                        <p class="card-text">System configuration and settings</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <a href="settings.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> backup.php</h5>
                        <p class="card-text">Database backup utility</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-danger file-badge">Admin Only</span>
                        <a href="backup.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> restore.php</h5>
                        <p class="card-text">Database restore utility</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-danger file-badge">Admin Only</span>
                        <a href="restore.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> db_utilities.php</h5>
                        <p class="card-text">Database health checks and optimization</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <a href="db_utilities.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> activity_log.php</h5>
                        <p class="card-text">User activity and audit trail</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <a href="activity_log.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> notifications.php</h5>
                        <p class="card-text">System notifications and alerts</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <a href="notifications.php" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-eye"></i> View</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Infrastructure -->
        <div class="category-header">
            <h2><i class="bi bi-server"></i> Infrastructure (4 files)</h2>
            <p class="mb-0">Core infrastructure components</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> config.php</h5>
                        <p class="card-text">Advanced database configuration (PDO)</p>
                        <span class="badge bg-primary file-badge">Advanced</span>
                        <span class="badge bg-info file-badge">Core</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> db_connection.php</h5>
                        <p class="card-text">Simple database connection (mysqli)</p>
                        <span class="badge bg-success file-badge">Simple</span>
                        <span class="badge bg-info file-badge">Core</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> header.php</h5>
                        <p class="card-text">Responsive navigation with dropdown menus</p>
                        <span class="badge bg-success file-badge">Component</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> footer.php</h5>
                        <p class="card-text">Footer with copyright and scripts</p>
                        <span class="badge bg-success file-badge">Component</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Files -->
        <div class="category-header">
            <h2><i class="bi bi-database"></i> Database Files (2 files)</h2>
            <p class="mb-0">SQL schemas and sample data</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> improved_schema.sql</h5>
                        <p class="card-text">Complete database schema with indexes and constraints</p>
                        <span class="badge bg-primary file-badge">Schema</span>
                        <span class="badge bg-success file-badge">5 Tables</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-code"></i> complete_sample_data.sql</h5>
                        <p class="card-text">Sample data: 6 users, 15 students, 5 tracks</p>
                        <span class="badge bg-info file-badge">Test Data</span>
                        <span class="badge bg-success file-badge">373 Lines</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation -->
        <div class="category-header">
            <h2><i class="bi bi-book"></i> Documentation (10 files)</h2>
            <p class="mb-0">Complete guides and references</p>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> README.md</h5>
                        <p class="card-text">Main project documentation</p>
                        <span class="badge bg-primary file-badge">Main</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> QUICKSTART.md</h5>
                        <p class="card-text">5-minute setup guide</p>
                        <span class="badge bg-success file-badge">Quick</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> COMPLETE_SETUP_GUIDE.md</h5>
                        <p class="card-text">Complete setup instructions</p>
                        <span class="badge bg-info file-badge">Detailed</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> ALL_FEATURES.md</h5>
                        <p class="card-text">Complete feature documentation</p>
                        <span class="badge bg-primary file-badge">Features</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> CODE_INDEX.md</h5>
                        <p class="card-text">Complete code file catalog</p>
                        <span class="badge bg-warning file-badge">NEW</span>
                        <span class="badge bg-info file-badge">Reference</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card code-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> + 5 more docs</h5>
                        <p class="card-text">Architecture, Schema, Data, Installation, Project Summary</p>
                        <span class="badge bg-secondary file-badge">Additional</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">
                <h4><i class="bi bi-lightning"></i> Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="home.php" class="btn btn-lg btn-outline-primary w-100">
                            <i class="bi bi-house"></i><br>Homepage
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="all_features.php" class="btn btn-lg btn-outline-success w-100">
                            <i class="bi bi-stars"></i><br>All Features
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="test_system.php" class="btn btn-lg btn-outline-info w-100">
                            <i class="bi bi-clipboard-check"></i><br>Test System
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="enrollment_page.php" class="btn btn-lg btn-outline-warning w-100">
                            <i class="bi bi-pencil-square"></i><br>Enroll Now
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="student_list.php" class="btn btn-lg btn-outline-danger w-100">
                            <i class="bi bi-people"></i><br>Student List
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="reports.php" class="btn btn-lg btn-outline-secondary w-100">
                            <i class="bi bi-graph-up"></i><br>Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="alert alert-info mt-5">
            <h5><i class="bi bi-info-circle"></i> System Information</h5>
            <p class="mb-0">
                <strong>Total Files:</strong> 31 PHP + 2 SQL + 10 Documentation = 43 files<br>
                <strong>Total Features:</strong> 300+<br>
                <strong>User Status:</strong> <?php echo $is_logged_in ? "Logged in as <strong>$username</strong>" : "Not logged in"; ?><br>
                <strong>Version:</strong> 2.0 - Complete Edition
            </p>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
