<?php

require_once __DIR__ . '/../../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    public static function sendOTP($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tenmienfree26@gmail.com';
            $mail->Password = 'dtnzvyakgmauszqs';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tenmienfree26@gmail.com', 'MediTrust');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Ma OTP Dang Nhap';
            $mail->Body = "<h3>Ma OTP cua ban la: <b>$otp</b></h3>";

            $mail->send();
            return true;

        } catch (Exception $e) {
            echo "Loi gui mail: " . $mail->ErrorInfo;
            return false;
        }
    }
}