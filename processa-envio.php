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
   public $status = array('codigo_status' => null, 'descricao_status' => '');

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
   header('location: index.php');
   die();
}


$mail = new PHPMailer(true);

try {
   //Server settings
   $mail->SMTPDebug = false;                      //Enable verbose debug output
   $mail->isSMTP();                                            //Send using SMTP
   $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
   $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
   $mail->Username   = 'victmagaldi@gmail.com';                     //SMTP username
   $mail->Password   = '';                               //SMTP password
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
   $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

   //Recipients
   $mail->setFrom('victmagaldi@gmail.com', 'Victor email ');
   $mail->addAddress(
      $message->__get('destinatario')
   );     //Add a recipient

   // $mail->addReplyTo('victmagaldi@gmail.com', 'responda-me');
   // $mail->addCC('cc@example.com'); destinatário de cópia
   // $mail->addBCC('bcc@example.com'); destinatário de cópia oculta

   //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

   //Content

   $mail->isHTML(true);                                  //Set email format to HTML
   $mail->Subject = $message->__get('assunto');
   $mail->Body    = $message->__get('mensagem');
   $mail->AltBody = 'é necessario client html para ver o contéudo total';

   $mail->send();

   $message->status['codigo_status'] = 1;
   $message->status['descricao_status'] = 'E-mail enviado com Sucesso';

   echo 'E-mail enviado com Sucesso';
} catch (Exception $e) {
   $message->status['codigo_status'] = 2;
   $message->status['descricao_status'] = 'E-mail não enviado, tente novamente mais tarde' . $mail->ErrorInfo;

   echo 'não foi possível enviar este email';
   echo '<hr>';
   echo "Detalhes: {$mail->ErrorInfo}";
}

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
   <meta charset="utf-8" />
   <title>App Mail Send</title>

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
   <div class="container">
      <div class="py-3 text-center">
         <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
         <h2>Send Mail</h2>
         <p class="lead">Seu app de envio de e-mails particular!</p>
      </div>
      <div class="row">
         <div class="col-md-12">
            <?php
            if ($message->status['codigo_status'] == 1) { ?>
               <div class="container">
                  <h1 class="display-4 text-success">Suceso</h1>
                  <p>
                     <?= $message->status['descricao_status'] ?>
                  </p>
                  <a href="/" class="btn btn-success btn-lg mt-5 text-white"> voltar</a>
               </div>

            <?php } else { ?>

               <div class="container">
                  <h1 class="display-4 text-danger">Error</h1>
                  <p>
                     <?= $message->status['descricao_status'] ?>
                  </p>
                  <a href="/locahost/app-send-mai" class="btn btn-success btn-lg mt-5 text-white"> voltar</a>
               </div>
            <?php } ?>




         </div>
      </div>
   </div>

</body>

</html>