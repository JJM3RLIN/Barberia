<?php
namespace Controllers;

use Models\Usuario;
use MVC\Router;
use Classes\Email;
class LoginController{
    public static function login(Router $router){

        $alertas = [];
        $aut = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
          $aut = new Usuario($_POST);

         $alertas = $aut->validarLogin();

         if( empty($alertas) ){
             //Comprobar que el usuario exista
             $usuario = Usuario::where('email', $aut->email);
             
             if($usuario){

                //Verificar la contraseña, nos regresa un true
               $verificar = password_verify($aut->password, $usuario->password);

               //Que ya haya confirmado su cuenta
               if(!$usuario->confirmado){
                Usuario::setAlerta('error', "La cuenta no se ha confirmado");
               }
               if(!$verificar){
                Usuario::setAlerta('error', "Contraseña incorrecta");
               }else{
                   //Autenticar el usuario
                   session_start();

                   //Llenar los datos de sesión para tenerlos en la app
                   $_SESSION['id'] = $usuario->id;
                   $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                   $_SESSION['email'] = $usuario->email;
                   $_SESSION['login'] = true;

                   //Redireccionar
                   if($usuario->admin === '1'){
                      //Es un admin

                      //Iniciamos la sesión del admin
                      $_SESSION['admin'] = $usuario->admin;
                      header('Location:/admin');

                   }else{
                       //Es un cliente
                       header('Location:/cita');
                   }
               }

             }else{
                 Usuario::setAlerta('error', "Usuario no encontrado");
             }
         }

        }
        
        $alertas= Usuario::getAlertas();
        $router->render('autent/login', [
            'alertas'=> $alertas,
            'aut'=> $aut
        ]);
    }
    public static function logout(){

        session_start();
           $_SESSION = [];
           header('Location: /');
    }
    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $aut = new Usuario($_POST);
            $alertas = $aut->validarEmail();

            if( empty($alertas) ){
                //Traer los datos del usuario desde la bd
                $usuario = Usuario::where('email', $aut->email);

                //verificar que exista y que este verificado
                if($usuario && $usuario->confirmado==='1'){

                    //Darle un token
                    $usuario->token();
                    //Actualizamos la BD para que guarde el token
                    $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');

                }else{

                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                 
                }
            }
        }
        $alertas = Usuario::getAlertas();
       $router->render('autent/olvide', [
           'alertas'=>$alertas
       ]);
    }
    public static function recuperar(Router $router){
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        $alertas = [];
        //Pata que no se muestre el formulario
        $error = false;
       if( empty($usuario) ){
           Usuario::setAlerta('error', 'Token no valido');
           $error = true;
       }
       if($_SERVER['REQUEST_METHOD'] === 'POST'){
           //Leer la nueva contraseña y guardarla en memoria
           $password = new Usuario($_POST);
         $alertas =  $password->validarPassword();
           if( empty($alertas) ){
               //Eliminamos la contraseña anterior
            $usuario->password = '';
            //Le asignamos la nueva contraseña al objeto en memoria
            $usuario->password = $password->password;
            $usuario->hashPassword();
            //eliminamos el token
            $usuario->token = '';
            //Lo guardamos en la BD
             $resultado =  $usuario->guardar();

             if($resultado){
                 header('Location:/');
             }
           }
       }
       $alertas = Usuario::getAlertas();
       $router->render('autent/recuperar-cuenta', [
           'alertas'=>$alertas,
            'error' => $error
       ]);
    }
    public static function crear(Router $router){
        $usuario = new Usuario;

        //Alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if(empty($alertas)){

                //Verificar que el usuario este registrado
            $resultado =  $usuario->usuarioExiste();

            if( $resultado->num_rows ){

                //Obtenemos a las alertas, ya que en usuarioExiste agregamos una alerta
                $alertas = Usuario::getAlertas();

            }else{
                //No esta registrado el usuario

                //Hashear password
                $usuario->hashPassword();

                //Mandar un token para verificar que sea un humano y tener mas seguridad
                $usuario->token();

                //Enviar el correo  
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                $email->enviarConfirmacion();

                //Crear el usuario
                $resultado =$usuario->guardar();

                if($resultado){
                    header('Location:/mensaje');
                }
            }
                
            }
        }
       $router->render('autent/crear', [
           'usuario' => $usuario,
           'alertas' => $alertas
       ]);
    }
    public static function confirmarCuenta(Router $router){
        
        $alertas = [];  
        $token = s($_GET['token']);
        $usuario = Usuario::where("token", $token);
   
        if( empty($usuario) ){  
            //Mostrar mensaje de error
          Usuario::setAlerta('error', 'Token no valido');
        }else{
            //Modificar el usuario a confirmado
            $usuario->confirmado = "1";
            $usuario->token = '';
            //Actualizar el registro
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }
        $alertas = Usuario::getAlertas();

        //Renderizar la vista
        $router->render('autent/confirmar', [
            'alertas'=>$alertas
        ]);
    }
    public static function mensaje(Router $router){
     
        $router->render('autent/mensaje', [  ]);
    }
}