<?php

require './lib/phpmailer/Exception.php';
require './lib/phpmailer/OAuth.php';
require './lib/phpmailer/PHPMailer.php';
require './lib/phpmailer/POP3.php';
require './lib/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// print_r($_POST);
// echo '<br>';



class Message {
   private $destinatario = null;
   private $assunto = null;
   private $mensagem = null;

   public function __get($name) {
      return $this->$name;
   }
   public function __set($atribute, $value) {
      $this->$atribute = $value;
   }
   public function validateMessage() {
      echo empty($this->destinatario);
      if (empty($this->destinatario) || empty($this->assunto) || empty($this->mensagem)) {
         return false;
      }

      return true;
   }
}

$message = new Message();

$message->__set('destinatario', $_POST['destinatario']);
$message->__set('assunto', $_POST['assunto']);
$message->__set('mensagem', $_POST['mensagem']);
// print_r($message);
if (!$message->validateMessage()) {
   echo 'inválido';
   //mata o processamento do script caso falhe
   die();
}
$mail = new PHPMailer(true);

try {
   //Server settings
   $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
   $mail->isSMTP();                                            //Send using SMTP
   $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
   $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
   $mail->Username   = 'user@example.com';                     //SMTP username
   $mail->Password   = 'secret';                               //SMTP password
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
   $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

   //Recipients
   $mail->setFrom('from@example.com', 'Mailer');
   $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
   $mail->addAddress('ellen@example.com');               //Name is optional
   $mail->addReplyTo('info@example.com', 'Information');
   $mail->addCC('cc@example.com');
   $mail->addBCC('bcc@example.com');

   //Attachments
   $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

   //Content
   $mail->isHTML(true);                                  //Set email format to HTML
   $mail->Subject = 'Here is the subject';
   $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
   $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

   $mail->send();
   echo 'Message has been sent';
} catch (Exception $e) {
   echo 'não foi possível enviar este email';
   echo '<hr>';
   echo "Detalhes: {$mail->ErrorInfo}";
}
