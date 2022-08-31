<?php
namespace Models;
class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password',
    'telefono' ,'confirmado', 'admin', 'token'];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $confirmado;
    public $admin;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->admin = $args['admin'] ?? '0';
        $this->token = $args['token'] ?? '';
    }
    public function validarNuevaCuenta(){
        if(!$this->nombre ){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->apellido ){
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El E-mail es obligatorio';
        }
        if(!$this->telefono){
            self::$alertas['error'][] = 'El teléfono es obligatorio';
        }
        if(!$this->password ){
            self::$alertas['error'][] = 'Inserta una contraseña';
        }
        if( strlen($this->password) < 6){
          self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }

           return self::$alertas;
    }

    //Revise si el usuario no existe
    public function usuarioExiste(){

        $query = "SELECT * FROM " . self::$tabla . " WHERE email= '" . $this->email ."'";
        $resultado = self::$db->query($query);
        if($resultado->num_rows ){
            self::$alertas['error'][] = 'Ya hay un usuario registrado con este correo';
        }
        return $resultado;
    }

    //Metodos para crear una cuenta

    //Hashear passwords
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }
    public function token(){

        //Que nos genere aleatoriamente un id
        $this->token = uniqid();
    }

    //Iniciar sesión
    public function validarLogin(){

        if(!$this->email){
            self::$alertas['error'][] = 'El E-mail es obligatorio';
        }

        if(!$this->password ){
            self::$alertas['error'][] = 'Contraseña incorrecta';
        }
        return self::$alertas;
        
    }
    //Olvido contraseña y nevesitamos validar el email
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El E-mail es obligatorio';
        }
        return self::$alertas;
    }
    public function validarPassword(){
        if(!$this->password ){
            self::$alertas['error'][] = 'Inserta una contraseña';
        }
        if( strlen($this->password) < 6){
          self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }
}