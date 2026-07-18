<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Department Details</h2>

        <div class="card mt-3">
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo htmlspecialchars($department['department_id']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($department['department_name']); ?></p>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Back</a>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
