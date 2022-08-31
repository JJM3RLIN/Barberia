<h1>Panel de administración</h1>
<?php include_once __DIR__ .'/../templates/barra.php'?>
<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario" action="/admin">
       <div class="campo">
       <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>"/>
       </div>
    </form>
</div>
<div id="citas-admin">
    <?php 
    if($mensaje){
        echo "<div class='alerta error'>${mensaje}</div>";
    }
    ?>
   <ul class="citas">
   <?php 
   $idCita = 0;
   foreach($citas as $key => $cita){
    if($idCita != $cita->id){

        $total = 0;
        //guardar el id anterior
        $idCita = $cita->id
    ?>

    <li>
        <p>ID: <span><?php echo $cita->id; ?></span></p>
        <p>Hora: <span><?php echo $cita->hora; ?></span></p>
        <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
        <p>Email: <span><?php echo $cita->email; ?></span></p>
        <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>
        <h3>Servicios</h3>
        <?php } // fin del if
        $total+= $cita->precio;
        ?>
        <p class="servicio"><?php echo $cita->servicio . " $" . $cita->precio; ?></p>
    <?php 
    $registroActual = $idCita;
    $proximo = $citas[$key+1]->id ?? 0;
    if(esUltimo($registroActual, $proximo)){
     echo '<p class="total">Total: <span>$' . $total . '</span></p>';
    
     echo " <form action='/api/eliminar' method='POST'>
     <input type='hidden' name='id' value='{$cita->id}' />
     <button class='btn-eliminar' type='submit'>Eliminar</button>
     </form>";
    }
    ?>
   
    <?php
      if($idCita != $cita->id) echo '</li>';
    ?>
        <!--</li>-->
    <?php } //fin de forEach?>
   </ul>
</div>
<?php 
$script = "<script src='build/js/buscador.js'></script>"
?>