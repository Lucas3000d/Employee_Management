<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$employeeId = (int) $_GET['id'];

// Require a POST request to prevent CSRF via GET links
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$db   = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = :id");
$stmt->bindParam(':id', $employeeId, PDO::PARAM_INT);
$stmt->execute();

header('Location: index.php');
exit;
