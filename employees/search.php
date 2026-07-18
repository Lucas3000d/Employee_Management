<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$db = new Database();
$conn = $db->connect();

$keyword = trim($_GET['keyword'] ?? '');
$results = [];

if ($keyword !== '') {
    $sql = "SELECT e.employee_id, e.first_name, e.last_name, e.gender, d.department_name, e.email
            FROM employees e
            LEFT JOIN departments d ON e.department_id = d.department_id
            WHERE e.first_name LIKE :keyword OR e.last_name LIKE :keyword OR d.department_name LIKE :keyword
            ORDER BY e.first_name ASC";

    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $keyword . '%';
    $stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Search Employees</h2>

        <form method="get" class="row g-3 mt-2">
            <div class="col-md-8">
                <input type="text" name="keyword" class="form-control" placeholder="Search by name or department" value="<?php echo htmlspecialchars($keyword); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if ($keyword !== ''): ?>
            <h4 class="mt-4">Results</h4>
            <?php if (count($results) > 0): ?>
                <table class="table table-bordered table-striped mt-2">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning mt-3">No results found.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
