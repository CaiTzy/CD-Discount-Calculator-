<?php
include("db_connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get all students with their track information
$query = "SELECT a.lrn, a.first_name, a.last_name, a.age, a.gender, a.address, 
          t.strand_course, a.enrollment_fee, a.discount_percent, a.total_amount, 
          a.payment_status, a.created_at
          FROM Application a
          LEFT JOIN Track t ON a.track_id = t.track_id
          ORDER BY a.created_at DESC";

$result = $conn->query($query);
$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Calculate statistics
$total_students = count($students);
$total_revenue = 0;
$pending_payments = 0;
foreach ($students as $student) {
    $total_revenue += $student['total_amount'];
    if ($student['payment_status'] === 'Pending') {
        $pending_payments++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List - Smart Online Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .list-card {
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
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .badge-pending {
            background-color: #ffc107;
        }
        .badge-paid {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🎓 Smart Online Enrollment</span>
            <div>
                <span class="me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="enrollment_form.php" class="btn btn-sm btn-primary me-2">New Enrollment</a>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="list-card p-4">
            <h2 class="mb-4">📋 Student Enrollment List</h2>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card text-center">
                        <h3><?php echo $total_students; ?></h3>
                        <p class="mb-0">Total Enrollments</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card text-center">
                        <h3>₱<?php echo number_format($total_revenue, 2); ?></h3>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card text-center">
                        <h3><?php echo $pending_payments; ?></h3>
                        <p class="mb-0">Pending Payments</p>
                    </div>
                </div>
            </div>
            
            <?php if (count($students) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>LRN</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Track/Strand</th>
                                <th>Base Fee</th>
                                <th>Discount</th>
                                <th>Total Amount</th>
                                <th>Payment Status</th>
                                <th>Enrolled Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['lrn']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['age']); ?></td>
                                    <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($student['strand_course']); ?></td>
                                    <td>₱<?php echo number_format($student['enrollment_fee'], 2); ?></td>
                                    <td><?php echo number_format($student['discount_percent'], 0); ?>%</td>
                                    <td><strong>₱<?php echo number_format($student['total_amount'], 2); ?></strong></td>
                                    <td>
                                        <span class="badge <?php echo $student['payment_status'] === 'Paid' ? 'badge-paid' : 'badge-pending'; ?>">
                                            <?php echo htmlspecialchars($student['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <p class="mb-0">No students enrolled yet. <a href="enrollment_form.php">Start enrolling students</a></p>
                </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="enrollment_form.php" class="btn btn-primary">← Back to Enrollment Form</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
