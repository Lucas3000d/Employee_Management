<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$db   = new Database();
$conn = $db->connect();

$stmt        = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] === 'has_employees') {
    $errorMessage = 'Cannot delete this department because it still has linked employees. Reassign or remove those employees first.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Departments</h2>
            <a href="add.php" class="btn btn-success">Add Department</a>
        </div>

        <?php if ($errorMessage !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($departments) > 0): ?>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($department['department_id']); ?></td>
                            <td><?php echo htmlspecialchars($department['department_name']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $department['department_id']; ?>" class="btn btn-info btn-sm">View</a>
                                <a href="edit.php?id=<?php echo $department['department_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <form method="post" action="delete.php?id=<?php echo $department['department_id']; ?>" style="display:inline;" onsubmit="return confirm('Delete this department?')">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No departments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
