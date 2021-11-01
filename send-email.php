<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/PHPMailer/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/PHPMailer/src/SMTP.php';
require 'vendor/autoload.php';

function sendEmail($userMail, $mailSubject, $mailBody)
{
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Host = "smtp.gmail.com";
    $mail->Username = 'ngocgiapkt@gmail.com';
    $mail->Password = "wvicsieughxvrbde";

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->IsHTML(true);
    $mail->addAddress($userMail);
    $mail->SetFrom("ngocgiapkt@gmail.com", "Giap Nguyen");
    $mail->Subject = $mailSubject;
    $mail->Body = $mailBody;
    
    $sendMailres = ($mail->Send()) ? true : false;
    $mail->clearAllRecipients();
    // return $sendMailres;
    if ($sendMailres === true) {
        echo "<span style='color: green'>Mail sent successfully</span>";
    } else {
        echo "<span style='color: red'>Mail failed</span>";
    }
}

?>