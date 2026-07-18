<?php
require_once 'config/config.php';

require_once 'includes/auth.php';

$db = new Database();
$conn = $db->connect();

$stmt = $conn->query("SELECT COUNT(*) AS total_departments FROM departments");
$totalDepartments = $stmt->fetch(PDO::FETCH_ASSOC)['total_departments'];

$stmt = $conn->query("SELECT COUNT(*) AS total_employees FROM employees");
$totalEmployees = $stmt->fetch(PDO::FETCH_ASSOC)['total_employees'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Departments</h5>
                        <p class="card-text">Total departments: <?php echo $totalDepartments; ?></p>
                        <a href="departments/index.php" class="btn btn-primary">Manage Departments</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Employees</h5>
                        <p class="card-text">Total employees: <?php echo $totalEmployees; ?></p>
                        <a href="employees/index.php" class="btn btn-success">Manage Employees</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Search Employees</h5>
                        <p class="card-text">Search employee records quickly.</p>
                        <a href="employees/search.php" class="btn btn-info">Search</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Employee Report</h5>
                        <p class="card-text">View and export simple reports.</p>
                        <a href="reports/index.php" class="btn btn-warning">View Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
