<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gicaldepositos";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$archivo_actual = basename(__FILE__, ".php");

$sql_cliente = "SELECT id, nombre FROM clientes WHERE LOWER(nombre) = LOWER('$archivo_actual')";
$result_cliente = $conn->query($sql_cliente);

if ($result_cliente->num_rows == 0) {
    echo "Cliente '$archivo_actual' no encontrado.";
    exit();
}
$cliente_data = $result_cliente->fetch_assoc();
$cliente_id = $cliente_data['id'];
$cliente_nombre = $cliente_data['nombre'];

// Crear carpeta uploads si no existe
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

// Insertar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $nombre = $_POST['nombre'];
    $lote = $_POST['lote'];
    $fecha_fabric = $_POST['fecha_fabric'];
    $fecha_venc = $_POST['fecha_venc'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $cantidad = $_POST['cantidad'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $imagen_path = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen_nombre = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
        $imagen_path = "uploads/" . $imagen_nombre;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen_path);
    }

    $sql = "INSERT INTO productos (nombre, lote, fecha_fabric, fecha_venc, fecha_ingreso, cantidad, tipo_movimiento, cliente_id, imagen)
            VALUES ('$nombre', '$lote', '$fecha_fabric', '$fecha_venc', '$fecha_ingreso', $cantidad, '$tipo_movimiento', $cliente_id, '$imagen_path')";
    $conn->query($sql);
}

// Eliminar producto
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Borrar imagen del servidor también
    $img_query = $conn->query("SELECT imagen FROM productos WHERE id=$id AND cliente_id=$cliente_id");
    if ($img_query && $img_query->num_rows > 0) {
        $img_row = $img_query->fetch_assoc();
        if ($img_row['imagen'] && file_exists($img_row['imagen'])) {
            unlink($img_row['imagen']);
        }
    }

    $conn->query("DELETE FROM productos WHERE id=$id AND cliente_id=$cliente_id");
}

$sql = "SELECT * FROM productos WHERE cliente_id = $cliente_id ORDER BY fecha_ingreso DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - <?php echo htmlspecialchars($cliente_nombre); ?></title>
    <link rel="stylesheet" href="../EstilosCSS/Estilosempresas.css">
</head>
<body>
        <div class="fila">
        <button onclick="history.back()">⬅ Volver Atrás</button>
    </div>
    <h2>Productos Registrados para <?php echo htmlspecialchars($cliente_nombre); ?></h2>

    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="nombre">Nombre del producto:</label>
            <input type="text" name="nombre" placeholder="Nombre del producto" required>
        </div>

        <div>
            <label for="lote">Número de lote:</label>
            <input type="text" name="lote" placeholder="Número de lote" required>
        </div>

        <div>
            <label for="fecha_fabric">Fecha de fabricación:</label>
            <input type="date" name="fecha_fabric" required>
        </div>

        <div>
            <label for="fecha_venc">Fecha de vencimiento:</label>
            <input type="date" name="fecha_venc" required>
        </div>

        <div>
            <label for="fecha_ingreso">Fecha de ingreso:</label>
            <input type="date" name="fecha_ingreso" required>
        </div>

        <div>
            <label for="cantidad">Cantidad de productos:</label>
            <input type="number" name="cantidad" placeholder="Cantidad de productos" required>
        </div>

        <div>
            <label for="tipo_movimiento">Tipo de movimiento:</label>
            <select name="tipo_movimiento" required>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
            </select>
        </div>

        <div>
            <label for="imagen">Imagen del producto:</label>
            <input type="file" name="imagen" accept="image/*">
        </div>

        <button type="submit" name="add_product">Agregar Producto</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Lote</th>
                <th>Fecha Fabricación</th>
                <th>Fecha Vencimiento</th>
                <th>Fecha Ingreso</th>
                <th>Cantidad</th>
                <th>Tipo</th>
                <th>Imagen</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['lote']) ?></td>
                    <td><?= $row['fecha_fabric'] ?></td>
                    <td><?= $row['fecha_venc'] ?></td>
                    <td><?= $row['fecha_ingreso'] ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td><?= ucfirst($row['tipo_movimiento']) ?></td>
                    <td>
                        <?php if ($row['imagen']): ?>
                            <img src="<?= $row['imagen'] ?>" alt="Imagen" width="80">
                        <?php else: ?>
                            Sin imagen
                        <?php endif; ?>
                    </td>
                    <td><a href="<?= $archivo_actual ?>.php?delete=<?= $row['id'] ?>">Eliminar</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
