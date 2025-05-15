<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();

// Consulta que une las mascotas con sus usuarios
$sql = "SELECT m.*, r.nombre_r, v.nombre_v
        FROM mascota m
        INNER JOIN raza r ON r.id_r = m.id_r
        INNER JOIN veterinarios v ON v.id_v = m.id_v";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantilla de Mascotas</title>
    <link rel="stylesheet" href="../../css/styles.css"> 
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Listado de Mascotas</h1>
    <table class="tabla-vet">
        <tr>
            <th>Chip</th>
            <th>Nombre</th>
            <th>Genero</th>
            <th>Raza</th>
            <th>Fecha de nacimiento</th>
            <th>DNI propietario</th>
            <th>Veterinario</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Chip_m']); ?></td>
                <td><?php echo htmlspecialchars($row['Nombre_m']); ?></td>
                <td><?php echo htmlspecialchars($row['genero_m']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_r']); ?></td>
                <td><?php echo date($row['Fecha_Nacimiento_m']); ?></td>
                <td><?php echo htmlspecialchars($row['DNI_p']);?></td>
                <td><?php echo htmlspecialchars($row['nombre_v']); ?></td>
                <td>
                    <a href='./procesos/deletes/eliminar_veterinario.php?id={$row['id_u']}'>Eliminar</a><br>
                    <a href='./procesos/forms/modificar_artista.php?id={$row['id_u']}&usr={$_SESSION['usuario']}&vet={$row['genero']}&nom={$artista['nombre']}'>Editar</a><br>
                    <a href='./procesos/forms/agregar_contacto.php?id={$artista['id']}'>Agregar Contacto</a><br>
                    <a href='./procesos/vistas/ver_contactos.php?id={$artista['id']}'>Ver Contactos</a>
                </td>
            </tr>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
                <td colspan="5">No hay Mascotas registradas.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
