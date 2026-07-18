<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$db   = new Database();
$conn = $db->connect();

$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.gender, d.department_name, e.email
        FROM employees e
        LEFT JOIN departments d ON e.department_id = d.department_id
        ORDER BY e.first_name ASC";

$stmt      = $conn->query($sql);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Employees</h2>
            <a href="add.php" class="btn btn-success">Add Employee</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($employees) > 0): ?>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['gender']); ?></td>
                            <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $employee['employee_id']; ?>" class="btn btn-info btn-sm">View</a>
                                <a href="edit.php?id=<?php echo $employee['employee_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <form method="post" action="delete.php?id=<?php echo $employee['employee_id']; ?>" style="display:inline;" onsubmit="return confirm('Delete this employee?')">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
