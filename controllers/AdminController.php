<?php

namespace Controllers;

use Models\AdminCita;
use MVC\Router;

class AdminController
{
    public static function index(Router $router)
    {
        if(!isset($_SESSION['admin'])){
            session_start();
        }
        isAdmin();
         $fecha = '';
        if(isset($_GET['fecha'])){
            $fecha = $_GET['fecha'];

            //Validar la fecha, checkdate los recibe separados y de tipo int
            $arregloFecha = explode('-', $fecha);
           if( !checkdate($arregloFecha[1], $arregloFecha[2], $arregloFecha[0] )){
            header('Location: /404');
           }

        }else{
            $fecha = date('Y-m-d');
        }
     
        if (!$_SESSION['login']) {
            session_start();
        }
        //Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citas_servicios ";
        $consulta .= " ON citas_servicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citas_servicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

      $citas =  AdminCita::SQL($consulta);
      $mensaje = '';
         if( sizeof($citas) === 0 ){
            $mensaje = 'No hay citas para este día';
         }
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'mensaje'=> $mensaje,
            'fecha' => $fecha
        ]);
    }
}
