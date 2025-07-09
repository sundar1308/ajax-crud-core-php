<?php
require_once('Employee.php');
require_once('Database.php');

class Validator
{
    public static function validateName($name)
    {
        return !empty($name) && strlen($name) >= 3;
    }

    public static function validateEmail($email, $id)
    {
        $error = '';
        $validate =  filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$validate) {
            $error = 'Invalid email address.';
            return $error;
        }

        $db = (new Database())->connect();

        // Check if email already exists
        if (!empty((new Employee($db))->checkIUnique($email, $id))) {
            $error = 'Email already exists.';
        }

        return $error;
    }

    public static function validateImage($file)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/heic', 'image/heif'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];

        $maxSize = 2 * 1024 * 1024; // 2MB

        return in_array($ext, $allowedExtensions) &&
            in_array($mimeType, $allowedMimeTypes) &&
            $file['size'] <= $maxSize;
    }
}
