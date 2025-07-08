<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Employee.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid employee ID.']);
    exit;
}

$db = (new Database())->connect();
$employee = new Employee($db);

$data = $employee->getById($id);

if ($data) {
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
exit;
