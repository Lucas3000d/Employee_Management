<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departmentName = trim($_POST['department_name'] ?? '');

    if ($departmentName === '') {
        $message = 'Department name is required.';
    } else {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (:name)");
        $stmt->bindParam(':name', $departmentName, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Failed to add department.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Add Department</h2>

        <?php if ($message !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" class="mt-3">
            <div class="mb-3">
                <label for="department_name" class="form-label">Department Name</label>
                <input type="text" id="department_name" name="department_name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save Department</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
