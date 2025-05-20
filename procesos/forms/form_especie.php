<?php
// Conexión a la base de datos
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Especie</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <div class="container">
    <h2>Registrar nueva especie</h2>
    <form action="../insert/insert_especie.php" method="post" id="form">
        <label for="nombre_esp">Nombre de la especie:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingresa nombre de la especie" onblur="validaNombre();">
        <span id="errorNombre" class="error"></span><br>
        <input type="submit" value="Registrar">
    </form>
</div>
</body>
</html>
