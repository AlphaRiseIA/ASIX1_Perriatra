<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();

if (!isset($_SESSION['nombre_u']) || $_SESSION['nombre_u'] !== 'admin') {
    header("Location: ../../index.php?no-tienes-acceso-aqui");
    exit();
}

// Consulta que une los veterinarios con sus usuarios
$sql = "SELECT u.*, v.*
        FROM usuario u
        INNER JOIN Veterinarios v ON u.id_u = v.id_v";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantilla de Usuarios</title>
    <link rel="stylesheet" href="../../css/styles.css"> <!-- Asegúrate de tener los estilos ahí -->  
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Listado de Usuarios</h1>
    <table class="tabla-vet">
        <thead>
        <tr>
            <th>Usuario</th>
            <th>Nombre completo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
</thead>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre_u']); ?></td>
                <td><?php echo htmlspecialchars($row['Nombre_v']); ?></td>
                <td><?php echo htmlspecialchars($row['Telf_v']); ?></td>
                <td>
                    <?php 
                        echo "<a href='../deletes/eliminar_usuario.php?id={$row['id_u']}' class='delU' name='delU'>Eliminar</a>"; ?><br>
                    <?php   echo "<a href='../updates/update_usuario.php?id={$row['id_u']}' class='editU' name='editU'>Editar</a>"; 
                    ?>
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
