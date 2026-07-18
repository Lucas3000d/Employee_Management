<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$message = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$employeeId = (int) $_GET['id'];

$db   = new Database();
$conn = $db->connect();

$stmt = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selectStmt = $conn->prepare("SELECT employee_id, first_name, last_name, gender, department_id, phone, email, salary, hire_date FROM employees WHERE employee_id = :id LIMIT 1");
$selectStmt->bindParam(':id', $employeeId, PDO::PARAM_INT);
$selectStmt->execute();

$employee = $selectStmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    header('Location: index.php');
    exit;
}

$encryption = new Encryption();
$employee['phone']  = $encryption->decrypt($employee['phone']);
$employee['salary'] = $encryption->decrypt($employee['salary']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName    = trim($_POST['first_name'] ?? '');
    $lastName     = trim($_POST['last_name'] ?? '');
    $gender       = trim($_POST['gender'] ?? '');
    $departmentId = (int) ($_POST['department_id'] ?? 0);
    $phone        = trim($_POST['phone'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $salary       = trim($_POST['salary'] ?? '');
    $hireDate     = trim($_POST['hire_date'] ?? '');

    if ($firstName === '' || $lastName === '' || $gender === '' || $departmentId <= 0 || $phone === '' || $email === '' || $salary === '' || $hireDate === '') {
        $message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } elseif (!is_numeric($salary) || (float)$salary < 0) {
        $message = 'Please enter a valid positive salary amount.';
    } else {
        $encryptedPhone  = $encryption->encrypt($phone);
        $encryptedSalary = $encryption->encrypt($salary);

        $updateStmt = $conn->prepare("UPDATE employees SET first_name = :first_name, last_name = :last_name, gender = :gender, department_id = :department_id, phone = :phone, email = :email, salary = :salary, hire_date = :hire_date WHERE employee_id = :id");
        $updateStmt->bindParam(':first_name',    $firstName,       PDO::PARAM_STR);
        $updateStmt->bindParam(':last_name',     $lastName,        PDO::PARAM_STR);
        $updateStmt->bindParam(':gender',        $gender,          PDO::PARAM_STR);
        $updateStmt->bindParam(':department_id', $departmentId,    PDO::PARAM_INT);
        $updateStmt->bindParam(':phone',         $encryptedPhone,  PDO::PARAM_STR);
        $updateStmt->bindParam(':email',         $email,           PDO::PARAM_STR);
        $updateStmt->bindParam(':salary',        $encryptedSalary, PDO::PARAM_STR);
        $updateStmt->bindParam(':hire_date',     $hireDate,        PDO::PARAM_STR);
        $updateStmt->bindParam(':id',            $employeeId,      PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Failed to update employee.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Edit Employee</h2>

        <?php if ($message !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" class="mt-3">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Male" <?php echo $employee['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $employee['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $employee['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select id="department_id" name="department_id" class="form-select" required>
                        <option value="">Select</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?php echo $department['department_id']; ?>" <?php echo $employee['department_id'] == $department['department_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($department['department_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="number" id="salary" name="salary" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($employee['salary']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="hire_date" class="form-label">Hire Date</label>
                    <input type="date" id="hire_date" name="hire_date" class="form-control" value="<?php echo htmlspecialchars($employee['hire_date']); ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Employee</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
