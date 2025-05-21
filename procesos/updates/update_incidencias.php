<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";
if (!isset($_SESSION['nombre_u']) || $_SESSION['nombre_u'] !== 'admin') {
    header("Location: ../../index.php?no-tienes-acceso-aqui");
    exit();
}
$errors = [];
$success = false;

// Validar que haya ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de ayuda no válido.");
}
$id = $_GET['id'];

// Obtener los datos actuales de la incidencia
$result = mysqli_query($conn, "SELECT * FROM form_ayuda WHERE id_ayuda = '$id'");
$incidencia = mysqli_fetch_assoc($result);
$estadoActual = $incidencia['estado'] ?? 'pendiente';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoEstado = $_POST['estado'] ?? '';
    $estadosPermitidos = ['pendiente', 'en revision', 'solucionada'];

    if (!in_array($nuevoEstado, $estadosPermitidos)) {
        $errors[] = "Estado no válido.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE form_ayuda SET estado = ? WHERE id_ayuda = ?");
        mysqli_stmt_bind_param($stmt, "si", $nuevoEstado, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $success = true;
        $estadoActual = $nuevoEstado;
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar Estado de Ayuda</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>

<body>
    <a href="../vistas/panel.php" class="btn-volver">⟵ Volver a Incidencias</a>
    <div class="container">
        <h2>Actualizar Estado de Incidencia</h2>
        <form method="POST" action="">
            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option class="badge-pendiente" value="pendiente" <?= $estadoActual === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option class="badge-en-revision" value="en revision" <?= $estadoActual === 'en revision' ? 'selected' : '' ?>>En revisión</option>
                <option class="badge-solucionada" value="solucionada" <?= $estadoActual === 'solucionada' ? 'selected' : '' ?>>Solucionada</option>
            </select>

            <input type="submit" value="Actualizar Estado">
        </form>
    </div>
</body>

</html>
