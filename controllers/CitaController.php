<?php
namespace Controllers;
use MVC\Router;
class CitaController{
public static  function index(Router $router){
    if (!$_SESSION['nombre']) {
        session_start();
      }
    $router->render('citas/index', [
        'nombre' => $_SESSION['nombre'],
        'id'=> $_SESSION['id']
    ]);
}
}