<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Index.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$conexion = new mysqli("localhost", "root", "", "gicaldepositos");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT rol, imagen FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $rol = $row['rol'];  // Cargo del usuario
    $imagen = $row['imagen'] ?: 'ruta/por/defecto.jpg';  // Imagen por defecto si no tiene imagen
} else {
    // Si no se encuentra el usuario en la base de datos, redirige a login
    header("Location: ../Index.php");
    exit();
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Depósitos - Gical</title>
    <link rel="stylesheet" href="../EstilosCSS/Estilodeposito.css">
</head>





<body>




<video autoplay muted loop id="video-fondo">
  <source src="../Imagenes/Gicalvideo.mp4" type="video/mp4">
  Tu navegador no soporta el video en HTML5.
</video>


    <div class="contenido">
        <H1>DEPÓSITOS GICAL TRINIDAD</H1>
    </div>

    <div class="fila">
      <a href="deposito1.php"><button>Depósito 1</button></a>
      <a href="deposito2.php"><button>Depósito 2</button></a>
      <a href="deposito3.php"><button>Depósito 3</button></a>
      <a href="deposito4.php"><button>Depósito 4</button></a>
    </div>

    <div class="fila">
      <a href="deposito5.php"><button>Depósito 5</button></a>
      <a href="deposito6.php"><button>Depósito 6</button></a>
      <a href="deposito7.php"><button>Depósito 7</button></a>
      <a href="deposito8.php"><button>Depósito 8</button></a>
    </div>

    <div class="fila">
        <button onclick="history.back()">⬅ Volver Atrás</button>
    </div>

    <div class="usuario-info">
        <img src="<?php echo htmlspecialchars($imagen); ?>" alt="Usuario">
        <a class="usuario-rol"><?php echo htmlspecialchars($rol); ?></a>
        <span class="nombre-usuario"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
    </div>

</body>
</html>
