<div class="barra">
    <p>Hola <?php echo $nombre ?? '' ?></p>
    <a class="btn" href="/logout">Cerrar sesión</a>
</div>
<?php if(isset($_SESSION['admin'])){ ?>
<div class="barra-servicios">
    <a href="/admin" class="btn">Ver citas</a>
    <a href="/servicios" class="btn">Ver servicios</a>
    <a href="/servicios/crear" class="btn">Nuevo servicio</a>
</div>
<?php }?>