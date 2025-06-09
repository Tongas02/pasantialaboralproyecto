<?php
session_start();

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$conexion = new mysqli("localhost", "root", "", "gicaldepositos");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    // Comparación directa si la contraseña NO está encriptada
    if (trim($contrasena) === trim($row['contrasena'])) {
        // Almacenar datos en la sesión
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $row['rol'];  // El cargo del usuario
        $_SESSION['imagen'] = $row['imagen'];  // Ruta de la imagen del usuario

        header("Location: ../Depositos/Depositos.php");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='../index.php';</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href='../index.php';</script>";
}

$stmt->close();
$conexion->close();
?>
