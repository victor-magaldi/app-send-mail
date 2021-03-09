<?php
print_r($_POST);
echo '<br>';


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
if ($message->validateMessage()) {
   echo 'validado';
} else {
   echo 'inválido';
}
