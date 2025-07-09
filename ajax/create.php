<?php

require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/Employee.php';
require_once '../classes/Validator.php';
require_once '../classes/Mailer.php';

header('Content-Type: application/json');

// Validate fields
$name = htmlspecialchars(trim($_POST['name'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$image = $_FILES['profileImg'] ?? null;

$errors = [];

if (!Validator::validateName($name)) {
    $errors['name'] = 'Name must be at least 3 characters.';
}

if ($errorMsg = Validator::validateEmail($email, null)) {
    $errors['email'] = $errorMsg;
}

if (!$image || !Validator::validateImage($image)) {
    $errors['profileImg'] = 'Invalid image (must be JPG/PNG and < 2MB).';
}

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

// Upload image
$ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
$newFileName = uniqid('emp_', true) . '.' . $ext;
$uploadPath = UPLOAD_DIR . $newFileName;
$moved = true;

if ($ext == 'heic') {
    try {
        $imagick = new Imagick();
        $imagick->readImage($image['tmp_name']);
        $imagick->setImageFormat('jpg');

        // Save with .jpg extension
        $newFileName = uniqid('emp_', true) . '.jpg';
        $uploadPath = UPLOAD_DIR . $newFileName;
        $imagick->writeImage($uploadPath);
        $imagick->clear();
        $imagick->destroy();
        $moved = false;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Try uploading png, jpeg, jpg formats']);
        exit;
    }
}



if ($moved && !move_uploaded_file($image['tmp_name'], $uploadPath)) {
    echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
    exit;
}


// Insert into DB
$db = (new Database())->connect();
$employee = new Employee($db);
$created = $employee->create($name, $email, $newFileName);

if ($created) {
    // Send welcome email
    Mailer::sendWelcomeEmail($email, $name);

    echo json_encode([
        'status' => 'success',
        'message' => 'Employee created successfully.'
    ]);
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
exit;
