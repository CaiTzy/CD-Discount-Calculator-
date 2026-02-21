<?php
$page_title = "Student List - Enrollment System";
require_once("db_connection.php");
require_once("header.php");

// Require login to access this page
if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Pagination settings
$records_per_page = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Search functionality
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize_input($_GET['status']) : '';

// Build query
$where_conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(a.lrn LIKE ? OR a.first_name LIKE ? OR a.last_name LIKE ? OR a.email LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    $types .= 'ssss';
}

if (!empty($status_filter)) {
    $where_conditions[] = "a.application_status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count
$count_query = "SELECT COUNT(*) as total FROM Application a $where_clause";
$count_stmt = $conn->prepare($count_query);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch students with track information
$query = "SELECT 
    a.lrn,
    CONCAT(a.first_name, ' ', IFNULL(a.middle_name, ''), ' ', a.last_name, ' ', IFNULL(a.suffix, '')) as full_name,
    a.email,
    a.phone_number,
    a.age,
    a.gender,
    t.strand_course,
    t.track_code,
    a.application_status,
    a.application_date,
    a.created_at
FROM Application a
INNER JOIN Track t ON a.track_id = t.track_id
$where_clause
ORDER BY a.created_at DESC
LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $params[] = $records_per_page;
    $params[] = $offset;
    $types .= 'ii';
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param('ii', $records_per_page, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">
                <i class="bi bi-people-fill text-primary"></i> Student List
            </h1>
            <p class="lead text-muted">View and manage student enrollment applications</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search by LRN, name, or email...">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status Filter</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Under Review" <?php echo $status_filter === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                        <option value="Approved" <?php echo $status_filter === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="Enrolled" <?php echo $status_filter === 'Enrolled' ? 'selected' : ''; ?>>Enrolled</option>
                        <option value="Rejected" <?php echo $status_filter === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                        <option value="Withdrawn" <?php echo $status_filter === 'Withdrawn' ? 'selected' : ''; ?>>Withdrawn</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2 class="text-primary"><?php echo $total_records; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2 class="text-warning">
                        <?php
                        $pending_query = "SELECT COUNT(*) as count FROM Application WHERE application_status = 'Pending'";
                        echo $conn->query($pending_query)->fetch_assoc()['count'];
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h5 class="card-title">Enrolled</h5>
                    <h2 class="text-success">
                        <?php
                        $enrolled_query = "SELECT COUNT(*) as count FROM Application WHERE application_status = 'Enrolled'";
                        echo $conn->query($enrolled_query)->fetch_assoc()['count'];
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <h2 class="text-info">
                        <?php
                        $approved_query = "SELECT COUNT(*) as count FROM Application WHERE application_status = 'Approved'";
                        echo $conn->query($approved_query)->fetch_assoc()['count'];
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-table"></i> Student Applications</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>LRN</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Track</th>
                            <th>Status</th>
                            <th>Date Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['lrn']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone_number'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['track_code']); ?></small><br>
                                        <?php echo htmlspecialchars(substr($row['strand_course'], 0, 30)); ?>...
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = [
                                            'Pending' => 'warning',
                                            'Under Review' => 'info',
                                            'Approved' => 'success',
                                            'Enrolled' => 'primary',
                                            'Rejected' => 'danger',
                                            'Withdrawn' => 'secondary'
                                        ];
                                        $class = $status_class[$row['application_status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $class; ?>">
                                            <?php echo htmlspecialchars($row['application_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($row['application_date'])); ?></td>
                                    <td>
                                        <a href="view_application.php?lrn=<?php echo urlencode($row['lrn']); ?>" 
                                           class="btn btn-sm btn-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">No students found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="card-footer">
                <nav aria-label="Student list pagination">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>">
                                Previous
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $page || $i == 1 || $i == $total_pages || abs($i - $page) <= 2): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif (abs($i - $page) == 3): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
                <p class="text-center text-muted mt-2 mb-0">
                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $records_per_page, $total_records); ?> of <?php echo $total_records; ?> students
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once("footer.php"); ?>
