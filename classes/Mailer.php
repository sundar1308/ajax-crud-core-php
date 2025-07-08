<?php
require_once __DIR__ . '/../vendor/PHPmailer/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPmailer/Exception.php';
require_once __DIR__ . '/../vendor/PHPmailer/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    public static function sendWelcomeEmail($to, $name)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = SMTP_PORT;

            $mail->setFrom(EMAIL_FROM, 'Employee Management');
            $mail->addAddress($to, $name);

            $mail->isHTML(true);
            $mail->Subject = "Welcome to the Company!";
            $mail->Body = "<p>Hi <strong>$name</strong>,<br>Welcome to the team!</p>";

            return $mail->send();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
