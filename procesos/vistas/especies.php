<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// Consulta que une los veterinarios con sus usuarios
$sql = "SELECT *
        FROM especie";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Especies</title>
    <link rel="stylesheet" href="../../css/styles.css"> <!-- Asegúrate de tener los estilos ahí -->  
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Listado de Especies</h1>
    <table class="tabla-vet">
        <thead>
        <tr>
            <th>Especies</th>
            <th>Acciones</th>
        </tr>
</thead>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre_esp']); ?></td>
                <td>
                    <?php 
                        echo "<a href='../deletes/eliminar_especies.php?id={$row['id_esp']}' class='delEsp' name='delEsp'>Eliminar</a>"; ?> <br>
                    <?php    echo "<a href='../updates/update_especies.php?id={$row['id_esp']}' class='editESP' name='editEsp'>Editar</a>"; 
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
                <td colspan="5">No hay Especies registradas.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
