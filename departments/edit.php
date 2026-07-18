<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$message = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$departmentId = (int) $_GET['id'];

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT department_id, department_name FROM departments WHERE department_id = :id LIMIT 1");
$stmt->bindParam(':id', $departmentId, PDO::PARAM_INT);
$stmt->execute();

$department = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$department) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departmentName = trim($_POST['department_name'] ?? '');

    if ($departmentName === '') {
        $message = 'Department name is required.';
    } else {
        $updateStmt = $conn->prepare("UPDATE departments SET department_name = :name WHERE department_id = :id");
        $updateStmt->bindParam(':name', $departmentName, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $departmentId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Failed to update department.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Edit Department</h2>

        <?php if ($message !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" class="mt-3">
            <div class="mb-3">
                <label for="department_name" class="form-label">Department Name</label>
                <input type="text" id="department_name" name="department_name" class="form-control" value="<?php echo htmlspecialchars($department['department_name']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Department</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
