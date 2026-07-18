<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$departmentId = (int) $_GET['id'];

// Require a POST request to prevent CSRF via GET links
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$db   = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("DELETE FROM departments WHERE department_id = :id");
    $stmt->bindParam(':id', $departmentId, PDO::PARAM_INT);
    $stmt->execute();
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    // FK constraint: department still has linked employees
    header('Location: index.php?error=has_employees');
    exit;
}
