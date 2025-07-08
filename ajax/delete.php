<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Employee.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete employee.']);
    exit;
}

$id = $_POST['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID.']);
    exit;
}

$db = (new Database())->connect();
$employee = new Employee($db);

$empData = $employee->getById($id);
if (!$empData) {
    echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
    exit;
}

$imgPath = UPLOAD_DIR . $empData['profileImg'];

if (file_exists($imgPath)) {
    unlink($imgPath);
}

$deleted = $employee->delete($id);

if ($deleted) {
    echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully.']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Failed to delete employee.']);
exit;
