<?php
require_once 'config.php';
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Student List';
include 'header.php';

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Search and filter parameters
$search = trim($_GET['search'] ?? '');
$status_filter = $_GET['status'] ?? '';
$track_filter = $_GET['track'] ?? '';

// Build query with filters
$where_conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(a.lrn LIKE ? OR a.first_name LIKE ? OR a.last_name LIKE ? OR CONCAT(a.first_name, ' ', a.last_name) LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $types .= 'ssss';
}

if (!empty($status_filter)) {
    $where_conditions[] = "a.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

if (!empty($track_filter)) {
    $where_conditions[] = "a.track_id = ?";
    $params[] = $track_filter;
    $types .= 'i';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Count total records
$count_sql = "SELECT COUNT(*) as total FROM Application a $where_clause";
$count_stmt = mysqli_prepare($conn, $count_sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $records_per_page);
mysqli_stmt_close($count_stmt);

// Fetch students with improved query
$sql = "
    SELECT 
        a.lrn, 
        a.first_name, 
        a.last_name,
        a.age,
        a.gender,
        a.status,
        a.application_date,
        t.strand_course,
        t.tuition_fee,
        CASE 
            WHEN d.document_id IS NOT NULL THEN 'Uploaded'
            ELSE 'Pending'
        END AS document_status
    FROM Application a
    JOIN Track t ON a.track_id = t.track_id
    LEFT JOIN Document d ON a.lrn = d.lrn
    $where_clause
    ORDER BY a.application_date DESC
    LIMIT ? OFFSET ?
";

$stmt = mysqli_prepare($conn, $sql);
$all_params = array_merge($params, [$records_per_page, $offset]);
$all_types = $types . 'ii';
mysqli_stmt_bind_param($stmt, $all_types, ...$all_params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Fetch tracks for filter dropdown
$tracks_sql = "SELECT track_id, strand_course FROM Track ORDER BY strand_course";
$tracks_result = mysqli_query($conn, $tracks_sql);
$tracks = mysqli_fetch_all($tracks_result, MYSQLI_ASSOC);

// Get statistics
$stats_sql = "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN status = 'Enrolled' THEN 1 ELSE 0 END) as enrolled
    FROM Application
";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="text-muted">Total Applications</h5>
                <h2 class="text-primary"><?php echo $stats['total']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="text-muted">Pending</h5>
                <h2 class="text-warning"><?php echo $stats['pending']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="text-muted">Approved</h5>
                <h2 class="text-success"><?php echo $stats['approved']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="text-muted">Enrolled</h5>
                <h2 class="text-info"><?php echo $stats['enrolled']; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-people-fill"></i> Student Applications</h4>
        <a href="enrollment_form.php" class="btn btn-light btn-sm">
            <i class="bi bi-person-plus"></i> New Application
        </a>
    </div>
    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" action="" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="LRN, Name...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Under Review" <?php echo $status_filter === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                    <option value="Approved" <?php echo $status_filter === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="Rejected" <?php echo $status_filter === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                    <option value="Enrolled" <?php echo $status_filter === 'Enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                    <option value="Withdrawn" <?php echo $status_filter === 'Withdrawn' ? 'selected' : ''; ?>>Withdrawn</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="track" class="form-label">Track</label>
                <select class="form-select" id="track" name="track">
                    <option value="">All Tracks</option>
                    <?php foreach ($tracks as $track): ?>
                        <option value="<?php echo $track['track_id']; ?>" 
                                <?php echo $track_filter == $track['track_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($track['strand_course']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="student_list.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                </div>
            </div>
        </form>

        <!-- Results -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Age/Gender</th>
                        <th>Track</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                <i class="bi bi-inbox"></i> No students found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($student['lrn']); ?></strong></td>
                                <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td><?php echo $student['age'] . ' / ' . $student['gender']; ?></td>
                                <td>
                                    <small><?php echo htmlspecialchars($student['strand_course']); ?></small>
                                </td>
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
                                    <span class="badge bg-<?php echo $class; ?>">
                                        <?php echo htmlspecialchars($student['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($student['document_status'] === 'Uploaded'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Uploaded</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?php echo date('M d, Y', strtotime($student['application_date'])); ?></small></td>
                                <td>
                                    <a href="view_application.php?lrn=<?php echo urlencode($student['lrn']); ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($student['document_status'] === 'Pending'): ?>
                                        <a href="upload_documents.php?lrn=<?php echo urlencode($student['lrn']); ?>" 
                                           class="btn btn-sm btn-outline-success" title="Upload Documents">
                                            <i class="bi bi-upload"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $query_params = $_GET;
                    unset($query_params['page']);
                    $query_string = http_build_query($query_params);
                    $base_url = '?' . ($query_string ? $query_string . '&' : '');
                    ?>
                    
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $base_url; ?>page=<?php echo $page - 1; ?>">Previous</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == 1 || $i == $total_pages || abs($i - $page) <= 2): ?>
                            <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $base_url; ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php elseif (abs($i - $page) === 3): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $base_url; ?>page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            
            <div class="text-center text-muted">
                <small>Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $records_per_page, $total_records); ?> 
                       of <?php echo $total_records; ?> records</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
