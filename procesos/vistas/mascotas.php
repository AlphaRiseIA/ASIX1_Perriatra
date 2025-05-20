<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
$filtroNombre = isset($_GET['chip']) ? trim($_GET['chip']) : '';
$filtroDNI = isset($_GET['dni']) ? trim($_GET['dni']) : '';

// Consulta que une las mascotas con sus usuarios
$sql = "SELECT m.*, r.nombre_r, v.nombre_v
        FROM mascota m
        INNER JOIN raza r ON r.id_r = m.id_r
        INNER JOIN veterinarios v ON v.id_v = m.id_v";
$where = [];
if ($filtroNombre !== '') {
    $filtroNombre = mysqli_real_escape_string($conn, $filtroNombre);
    $where[] = "m.Chip_m LIKE '%$filtroNombre%'";
}
if ($filtroDNI !== '') {
    $filtroDNI = mysqli_real_escape_string($conn, $filtroDNI);
    $where[] = "m.DNI_p LIKE '%$filtroDNI%'";
}
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plantilla de Mascotas</title>
    <link rel="stylesheet" href="../../css/styles.css"> 
    <script src="../../script/script.js"></script>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Listado de Mascotas</h1>
<div class="filtros-container">
 <form method="get" action="mascotas.php" class="filtros-flex">
    <div class="filtro-item">
        <label>Chip de la mascota:</label>
        <select name="chip">
            <option value="">-- Todas --</option>
            <?php
            $nombresQuery = "SELECT Chip_m FROM mascota ORDER BY Nombre_m";
            $nombresResult = mysqli_query($conn, $nombresQuery);
            while ($nombreRow = mysqli_fetch_assoc($nombresResult)) {
                $selected = ($filtroNombre === $nombreRow['Chip_m']) ? 'selected' : '';
                echo "<option value=\"".htmlspecialchars($nombreRow['Chip_m'])."\" $selected>".htmlspecialchars($nombreRow['Chip_m'])."</option>";
            }
            ?>
        </select></div>
        <div class="filtro-item">
        <label>DNI propietario:</label>
        <input onblur="validarDNI()" type="text" name="dni" value="<?php echo htmlspecialchars($filtroDNI); ?>">
        </div>
        <br>
        <button type="submit" class="no">Filtrar</button>
        <button type="button" class="no" onclick="window.location.href='mascotas.php'">Limpiar</button>
    </form>
</div>
    <table class="tabla-vet">
        <thead>
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
        </thead>
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
                   <?php 
                        echo "<a href='../deletes/eliminar_mascota.php?id={$row['Chip_m']}' class='delM' name='delM'>Eliminar</a>";?><br>
                      <?php  echo "<a href='../updates/update_mascotas.php?id={$row['Chip_m']}' class='editM' name='editM'>Editar</a>"; 
                    ?> 
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
