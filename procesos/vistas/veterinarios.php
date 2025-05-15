<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();

// Consulta que une los veterinarios con sus usuarios
$sql = "SELECT u.*, v.*, e.*
        FROM usuario u
        INNER JOIN Veterinarios v ON u.id_u = v.id_v
        INNER JOIN especialidades e ON v.id_e = e.id_e";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantilla de Veterinarios</title>
    <link rel="stylesheet" href="../../css/styles.css"> <!-- Asegúrate de tener los estilos ahí -->  
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Listado de Veterinarios</h1>
    <table class="tabla-vet">
        <tr>
            <th>Usuario</th>
            <th>Nombre completo</th>
            <th>Teléfono</th>
            <th>Especialidad</th>
            <th>Salario (€)</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre_u']); ?></td>
                <td><?php echo htmlspecialchars($row['Nombre_v']); ?></td>
                <td><?php echo htmlspecialchars($row['Telf_v']); ?></td>
                <td><?php echo htmlspecialchars($row['Nombre_e']); ?></td>
                <td><?php echo number_format($row['Salario_v'], 2, ',', '.'); ?></td>
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
                <td colspan="5">No hay veterinarios registrados.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
