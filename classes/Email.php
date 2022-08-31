<?php 
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;
//Es un herlper para no escribir todo el codigo en varias partes del proyecto
class Email{
    public $nombre;
    public $email;
    public $token;
    public function __construct($email, $nombre, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
        
    }
public static function configEmail(){
   //Crear un objeto del email
   $mail = new PHPMailer();
   $mail->isSMTP();
   $mail->Host = 'smtp.mailtrap.io';
   $mail->SMTPAuth = true;
   $mail->Port = 2525;
   $mail->Username = 'c3deae1e937d9c';
   $mail->Password = 'a8fd8556632699';
    
   //Quien envia el correo
   $mail->setFrom('cuentas@appsalon.com');

   //Para quien es el correo
   $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');

   //Retornar la estancia de email
   return $mail;
}
public function enviarConfirmacion(){

   $mail = static::configEmail();

    $mail->Subject = 'Confirma tu cuenta';

    //Decir que utilizaremos html
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
      
    $contenido = '<html>
                     <p><strong> Hola ';
    $contenido .= $this->nombre . '</strong> Has creado tu cuenta en App Salon solo 
    debes confirmarla presionando el siguiente enlace</p>';
     $contenido .= '<p>Presiona aquí: <a href="http://localhost:3000/confirmar-cuenta?token=';
     $contenido .= "'" . $this->token . "'" .'">' . 'Confirmar cuenta</a></p>';
     $contenido .= '<p>Si tú no solicitaste esta cuenta puedes ignorar el mensaje</p></html>';

     $mail->Body = $contenido;

     //Enviar email
     $mail->send();

}
public function enviarInstrucciones(){
      
    $mail = static::configEmail();
      $mail->Subject = 'Reestablece tu contraseña';
  
      //Decir que utilizaremos html
      $mail->isHTML(true);
      $mail->CharSet = 'UTF-8';
        
      $contenido = '<html>
                       <p><strong> Hola ';
      $contenido .= $this->nombre . '</strong> Has solicitado restablecer tu contraseña</p>';
       $contenido .= '<p>Presiona aquí: <a href="http://localhost:3000/recuperar-cuenta?token=';
       $contenido .=  $this->token  .'">' . 'Reestablecer la contraseña</a></p>';
       $contenido .= '<p>Si tú no solicitaste el cambio de contraseña puedes ignorar el mensaje</p></html>';
  
       $mail->Body = $contenido;
  
       //Enviar email
       $mail->send();
}
    
}