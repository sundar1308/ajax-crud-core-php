<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Employee.php';
require_once '../classes/Validator.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;
$name = strip_tags(trim($_POST['name'] ?? ''));
$email = strip_tags(trim($_POST['email'] ?? ''));
$image = $_FILES['profileImg'] ?? null;

$errors = [];

if (!$id || !is_numeric($id)) {
    $errors['id'] = 'Invalid employee ID.';
}
if (!Validator::validateName($name)) {
    $errors['name'] = 'Name must be at least 3 characters.';
}

if ($errorMsg = Validator::validateEmail($email, $id)) {
    $errors['email'] = $errorMsg;
}

if (!empty($image['name']) && !Validator::validateImage($image)) {
    $errors['profileImg'] = 'Invalid image (JPG/PNG, max 2MB).';
}
// echo "hsvbd";
// print_r(isset($image['name']));
// exit;
if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

// Connect to DB
$db = (new Database())->connect();
$employee = new Employee($db);

// Check if employee exists
$current = $employee->getById($id);
if (!$current) {
    echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
    exit;
}

$imagePath = $current['profileImg'];

if (!empty($image['name'])) {
    // Delete old image
    $oldImage = UPLOAD_DIR . $current['profileImg'];
    if (file_exists($oldImage)) unlink($oldImage);

    // Upload new image
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $imagePath = uniqid('emp_', true) . '.' . $ext;
    move_uploaded_file($image['tmp_name'], UPLOAD_DIR . $imagePath);
}

$updated = $employee->update($id, $name, $email, $imagePath);

echo json_encode([
    'status' => $updated ? 'success' : 'error',
    'message' => $updated ? 'Employee updated successfully.' : 'Failed to update employee.'
]);
