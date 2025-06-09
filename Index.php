<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
<link rel="stylesheet" href="EstilosCSS/EstilosIndex.css">
</head>

<body>
  <div class="login">
    <img src="Imagenes/logogical.png" class="img" alt="Logo de Gical"> 

    <div class="login-screen">
      <div class="app-title">
        <h1>Bienvenido</h1>
      </div>

      <div class="login-form">
<form action="VerificarBD/verificar.php" method="POST">
          <div class="control-group">
            <input type="text" class="login-field" placeholder="Usuario" name="usuario" required>
          </div>

          <div class="control-group">
            <input type="password" class="login-field" placeholder="Contraseña" name="contrasena" required>
          </div>

          <button type="submit" class="btn btn-primary btn-large btn-block">Acceder</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
