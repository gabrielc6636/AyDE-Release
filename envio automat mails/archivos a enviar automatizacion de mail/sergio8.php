<?php
date_default_timezone_set('Etc/UTC');
require 'phpmailer/PHPMailerAutoload.php';

//Crea nueva instancia de PHP Mailer
$mail = new PHPMailer;
//Uso SMTP
$mail->isSMTP();
//SMTP debugging?
// 0 = off
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//HTML-friendly debug output
$mail->Debugoutput = 'html';
//Hostname del mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Encryption system - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//SMTP authentication
$mail->SMTPAuth = true;
//Usuario para SMTP
$mail->Username = "rcp.desarrollo@gmail.com";
//Password para SMTP
$mail->Password = "AyDEEquipo2";
//Quien envia el mensaje
$mail->setFrom('rcp.desarrollo@gmail.com', 'RCP Desarrollos');
//Direccion alternativa de respuesta
$mail->addReplyTo('rcp.desarrollo@gmail.com', 'RCP Desarrollos');

//Destinatario de mensaje
$mail->addAddress('rcp.desarrollo@gmail.com', 'Sergio');
//Asunto
$mail->Subject = '[Sergio] Reporte Mensual';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body

$mail->msgHTML(file_get_contents('reporteSergio8.html'), dirname(__FILE__));

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//Enviar el mensaje y verificar errores
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Mensaje enviado!";
}