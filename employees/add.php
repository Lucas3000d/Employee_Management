<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$message = '';

$db = new Database();
$conn = $db->connect();

$stmt = $conn->query("SELECT department_id, department_name FROM departments ORDER BY department_name ASC");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        $encryption     = new Encryption();
        $encryptedPhone  = $encryption->encrypt($phone);
        $encryptedSalary = $encryption->encrypt($salary);

        $sql = "INSERT INTO employees (first_name, last_name, gender, department_id, phone, email, salary, hire_date) VALUES (:first_name, :last_name, :gender, :department_id, :phone, :email, :salary, :hire_date)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':first_name',    $firstName,      PDO::PARAM_STR);
        $stmt->bindParam(':last_name',     $lastName,       PDO::PARAM_STR);
        $stmt->bindParam(':gender',        $gender,         PDO::PARAM_STR);
        $stmt->bindParam(':department_id', $departmentId,   PDO::PARAM_INT);
        $stmt->bindParam(':phone',         $encryptedPhone, PDO::PARAM_STR);
        $stmt->bindParam(':email',         $email,          PDO::PARAM_STR);
        $stmt->bindParam(':salary',        $encryptedSalary,PDO::PARAM_STR);
        $stmt->bindParam(':hire_date',     $hireDate,       PDO::PARAM_STR);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $message = 'Failed to add employee.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Add Employee</h2>

        <?php if ($message !== ''): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" class="mt-3">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select id="department_id" name="department_id" class="form-select" required>
                        <option value="">Select</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?php echo $department['department_id']; ?>"><?php echo htmlspecialchars($department['department_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="number" id="salary" name="salary" class="form-control" step="0.01" min="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="hire_date" class="form-label">Hire Date</label>
                    <input type="date" id="hire_date" name="hire_date" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save Employee</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
