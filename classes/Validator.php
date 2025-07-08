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
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        // print_r($file);
        // exit;
        return isset($file['type'], $file['size']) &&
            in_array($file['type'], $allowedTypes) &&
            $file['size'] <= $maxSize;
    }
}
