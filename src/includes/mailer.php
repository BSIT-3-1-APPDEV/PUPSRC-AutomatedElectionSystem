<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../../vendor/autoload.php";

// CONFIGURATION

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

// Enable verbose debug output. Uncomment if needed—debugging purposes
// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

// Send using SMTP
$mail->isSMTP();

// Enable SMTP authentication
$mail->SMTPAuth = true;

// SMTP server
$mail->Host = "smtp.gmail.com";

// TLS encryption — secure than SSL
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

// TCP Port
$mail->Port = 587;

// SMTP username
$mail->Username = "carltabuso2275@gmail.com";

// SMTP password
$mail->Password = "akgsezfmrlluxybg";

// Set email format to HTML
$mail->isHtml(true);

return $mail;