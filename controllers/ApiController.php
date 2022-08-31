<?php
namespace Controllers;

use Models\Cita;
use Models\CitaServicio;
use Models\Servicio;

class ApiController{

    public static function index(){

        //Obtener todos los servicios
      $servicios = Servicio::all();
      echo json_encode($servicios);
    }

    public static function guardar(){
      $cita = new Cita($_POST);

      //guardar cita
     $resultado = $cita->guardar();

     $id = $resultado['id'];
     //guardar la cita y servicios

     //Nos permite separar un string en array, separador y string a separar
     $idServicios = explode(',', $_POST['servicios']);

     foreach($idServicios as $idServicio){

      //dependiendo de los servicios se crearan los elementos
      $args = [
        'citaId' => $id,
        'servicioId' => $idServicio
      ];
      $citaServicio = new CitaServicio($args);
      $citaServicio->guardar();
     }

     

      echo json_encode(['resultado' => $citaServicio]);
    }

    public static function eliminar(){
      if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $cita = Cita::find($id);
        $cita->eliminar();
        //para que nos direccione a la misma página donde veniamos
        header('Location: ' . $_SERVER['HTTP_REFERER'] );

      }
    }

}