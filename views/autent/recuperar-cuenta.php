<h1 class="nombre-pagina">Recupera tu contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña</p>
<?php include_once __DIR__ .'/../templates/alertas.php' ?>
<?php if($error) return; ?>
<form method="POST" class="formulario">
    <div class="campo">
        <label for="password">Contraseña:</label>
        <input 
        type="password"
        id="password"
        name="password"
        placeholder="Tu nueva contraseña"
        >
    </div>
    <button type="submit" class="btn">Guardar nueva contraseña</button>
</form>
<div class="acciones">
    <a href="/">Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">Aún no tienes una cuenta? Crear una</a>
</div>