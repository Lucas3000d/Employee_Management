<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$employeeId = (int) $_GET['id'];

$db = new Database();
$conn = $db->connect();

$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.gender, e.department_id, e.phone, e.email, e.salary, e.hire_date, d.department_name
        FROM employees e
        LEFT JOIN departments d ON e.department_id = d.department_id
        WHERE e.employee_id = :id LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $employeeId, PDO::PARAM_INT);
$stmt->execute();

$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    header('Location: index.php');
    exit;
}

$encryption = new Encryption();
$employee['phone'] = $encryption->decrypt($employee['phone']);
$employee['salary'] = $encryption->decrypt($employee['salary']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Employee Details</h2>

        <div class="card mt-3">
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo htmlspecialchars($employee['employee_id']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($employee['gender']); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($employee['department_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($employee['phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
                <p><strong>Salary:</strong> <?php echo htmlspecialchars($employee['salary']); ?></p>
                <p><strong>Hire Date:</strong> <?php echo htmlspecialchars($employee['hire_date']); ?></p>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Back</a>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
