<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// Leer filtro de especie (si existe)
$filtroEspecie = isset($_GET['especie']) ? trim($_GET['especie']) : '';

// Consulta base con posible filtro
$sql = "SELECT r.*, e.nombre_esp 
        FROM raza r
        INNER JOIN especie e ON r.id_esp = e.id_esp";

if (!empty($filtroEspecie)) {
    $filtroEspecie = mysqli_real_escape_string($conn, $filtroEspecie);
    $sql .= " WHERE r.id_esp = '$filtroEspecie'";
}

$result = mysqli_query($conn, $sql);

// Obtener todas las especies para el <select>
$especiesQuery = "SELECT id_esp, nombre_esp FROM especie ORDER BY nombre_esp";
$especiesResult = mysqli_query($conn, $especiesQuery);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Razas</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
    <h1>Listado de Razas</h1>
<div class="filtros-container">
    <form method="GET" action="razas.php" class="filtro-flex">
        <div class="filtro-item">
        <label>Filtrar por especie:</label>
        <select name="especie">
            <option value="">-- Todas las especies --</option>
            <?php while ($esp = mysqli_fetch_assoc($especiesResult)): ?>
                <option value="<?php echo $esp['id_esp']; ?>" <?php if ($filtroEspecie == $esp['id_esp']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($esp['nombre_esp']); ?>
                </option>
            <?php endwhile; ?>
        </select>
            </div>
        <br>
        <button type="submit" class="no">Filtrar</button>
        <button type="button" class="no" onclick="window.location.href='razas.php'">Limpiar</button>
            </form>
</div>
    <table class="tabla-vet">
        <thead>
        <tr>
            <th>Raza</th>
            <th>Acciones</th>
        </tr>
            </thead>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Nombre_r']); ?></td>
                    <td>
                        <?php 
                        echo "<a href='../deletes/eliminar_razas.php?id={$row['id_r']}' class='delR' name='delR'>Eliminar</a>"; ?><br>
                      <?php  echo "<a href='../updates/update_razas.php?id={$row['id_r']}' class='editR' name='editR'>Editar</a>"; 
                    ?> 
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">No hay razas que coincidan con el filtro.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

