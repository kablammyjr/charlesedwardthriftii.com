<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Mail {

public static function sendMail($subject, $body, $address) {

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = '465';
    $mail->isHTML();
    $mail->Username = 'charlesedwardthriftiiwebsite@gmail.com';
    $mail->Password = 'Webboy.webboy7';
    $mail->SetFrom('no-reply@edboy.space');
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($address);

    $mail->Send();

}







}
?>