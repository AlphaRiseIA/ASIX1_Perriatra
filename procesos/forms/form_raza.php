<?php
// Conexión a la base de datos (procedural)
include '../conn/conexion.php';
include '../conn/conectarse.php';

session_start();

if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

// Verificar conexión
if (mysqli_connect_errno()) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Consulta INNER JOIN para obtener id_esp y nombre
$sql = "SELECT id_esp, nombre_esp FROM especie";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Raza</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <div class="container">
    <h2>Registrar nueva raza</h2>
    <form action="../insert/insert_raza.php" method="post" id="form">
        <label for="nombre_r">Nombre de la raza:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingresa nombre de la raza" onblur="validaNombre()">
        <span class="error" id="errorNombre"></span>
        <label for="id_esp">Especie:</label>
        <select id="id_esp" name="id_esp" onblur="validaEspecialidad()">
            <option value="">-- Selecciona una especie --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['id_esp'] ?>"><?= htmlspecialchars($row['nombre_esp']) ?></option>
            <?php endwhile; ?>
        </select>
        <span id="error" style="color:red;"></span>
        <input type="submit" value="Registrar">
    </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
