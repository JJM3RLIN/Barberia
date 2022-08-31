<h1 class="nombre-pagina">Crear nueva cita</h1>
<p class="descripcion-pagina">Elije tus servicios y agrega tus datos</p>
<?php include_once __DIR__ .'/../templates/barra.php'?>
<div id="app">
    <nav class="tabs">
        <button class="actual" data-paso="1">Servicios</button>
        <button data-paso="2">Información</button>
        <button data-paso="3">Resumen</button>
    </nav>
    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elije tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios">

        </div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus datos y citas</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>
        <form method="POST" class="formulario">
            <input type="hidden" id="id" value="<?php echo $id ?>" />
            <div class="campo">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" disabled value="<?php echo $nombre; ?>">
            </div>

            <div class="campo">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d', strtotime('+1 day')) ?>">
            </div>

            <div class="campo">
                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora">
            </div>

        </form>
    </div>
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>
    <div class="paginacion">
        <button class="btn" id="anterior">&laquo;Anterior</button>
        <button class="btn" id="siguiente">Siguiente &raquo;</button>
    </div>
</div>
<?php 
//Se inyectara directamente en el layout
$script = '
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="build/js/app.js"></script>';
?>