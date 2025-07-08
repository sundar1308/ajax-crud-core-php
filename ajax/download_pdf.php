<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Employee.php';
require_once '../classes/PDFGenerator.php';

$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    echo "Invalid employee ID.";
    exit;
}

$db = (new Database())->connect();
$employee = new Employee($db);
$data = $employee->getById($id);

if (!$data) {
    echo "Employee not found.";
    exit;
}

// Generate and download PDF
PDFGenerator::generateEmployeePDF($data);
exit;
