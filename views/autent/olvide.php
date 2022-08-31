<h1 class="nombre-pagina">Olvide contraseña</h1>
<p class="descripcion-pagina">Restablece tu contraseña</p>
<?php include_once __DIR__ .  '/../templates/alertas.php';?>
<form action="/olvide" method="POST" class="formulario">
  <div class="campo">
  <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Tu email">
  </div>


    <button type="submit" class="btn">Enviar instrucciones</button>  
</form>

<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>