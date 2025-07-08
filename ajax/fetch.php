<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Employee.php';

header('Content-Type: application/json');

// Default values
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'DESC';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch data
$db = (new Database())->connect();
$employee = new Employee($db);

$rows = $employee->getAll($search, $sort, $order, $offset, $limit);
$total = $employee->countAll($search);

$html = '';
$i = 1;
// print_r($rows);
// exit;
foreach ($rows as $emp) {
    $html .= '<tr class="tableDataRow">';
    $html .= '<td>' . $i++ . '</td>';
    $html .= '<td><img src="uploads/' . htmlspecialchars($emp['profileImg']) . '" width="50" class="rounded"></td>';
    $html .= '<td>' . htmlspecialchars($emp['name']) . '</td>';
    $html .= '<td>' . htmlspecialchars($emp['email']) . '</td>';
    $html .= '<td>' . htmlspecialchars($emp['created_at']) . '</td>';
    $html .= '<td>
        <a class="me-2" onclick="viewEmployee(' . $emp['id'] . ')" href="javascript:void(0)"><i title="View Employee" class="fa-solid fa-eye"></i></a>
        <a class="me-2" onclick="editEmployee(' . $emp['id'] . ')" href="javascript:void(0)"><i title="Edit Employee" class="fa-solid fa-pen-to-square"></i></a>
        <a class="me-2" onclick="deleteEmployee(' . $emp['id'] . ')" href="javascript:void(0)"><i title="Delete Employee" class="fa-solid fa-trash"></i></a>
        <a onclick="downloadPDF(' . $emp['id'] . ')" href="javascript:void(0)"><i title="Download Employee Details as Pdf" class="fa-solid fa-download"></i></a>
    </td>';
    $html .= '</tr>';
}


echo json_encode([
    'status' => 'success',
    'data' => $html,
    'total' => $total,
    'page' => $page,
    'limit' => $limit
]);
