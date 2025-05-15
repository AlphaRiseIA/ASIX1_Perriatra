<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Especialidad</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <div class="container">
        <h2>Registrar Especialidad</h2>
        <form id="formEspecialidad" method="POST" action="../insert/insert_especialidad.php">
            <label for="nombre">Nombre de la especialidad:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingresa nombre especialidad" onblur="validaNombre()">
            <span class="error" id="errorNombre"></span>
            <input type="submit" value="Registrar">
        </form>
    </div>

