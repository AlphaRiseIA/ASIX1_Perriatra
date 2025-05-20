<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
$filtrosNombre=isset($_GET['nombre'])? trim($_GET['nombre']):'';
$filtrosDni=isset($_GET['dni']) ? trim($_GET['dni']) : '';

// Consulta que une los veterinarios con sus usuarios
$sql = "SELECT * FROM propietario";
$where = [];

if (!empty($filtrosNombre)) {
    $filtrosNombre = mysqli_real_escape_string($conn, $filtrosNombre);
    $where[] = "Nombre_p LIKE '$filtrosNombre'";
}
if (!empty($filtrosDni)) {
    $filtrosDni = mysqli_real_escape_string($conn, $filtrosDni);
    $where[] = "DNI_p LIKE '%$filtrosDni%'";
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
    <title>Listado de Propietarioss</title>
    <link rel="stylesheet" href="../../css/styles.css"> 
    <script src="../../script/script.js"></script>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Listado de Propietarios</h1>

    <div class="filtros-container">
    <form method="GET" action="propietarios.php" class="filtros-flex">
        <div class="filtro-item">
        <label>Nombre:</label>
        <select name="nombre">
            <option value="">-- Todos --</option>
            <?php
            // Obtener nombres únicos de propietarios
            $nombresQuery = "SELECT DISTINCT Nombre_p FROM propietario ORDER BY Nombre_p";
            $nombresResult = mysqli_query($conn, $nombresQuery);
            while ($nombreRow = mysqli_fetch_assoc($nombresResult)) {
                $selected = ($filtrosNombre === $nombreRow['Nombre_p']) ? 'selected' : '';
                echo "<option value=\"".htmlspecialchars($nombreRow['Nombre_p'])."\" $selected>".htmlspecialchars($nombreRow['Nombre_p'])."</option>";
            }
            ?>
        </select></div>
        <div class="filtro-item">
        <label>DNI:</label>
        <input type="text" name="dni" onblur="validarDNI()"value="<?php echo htmlspecialchars($filtrosDni); ?>"></div><br>
        <button type="submit">Aplicar Filtros</button>
        <button type="button" onclick="window.location.href='propietarios.php'">Limpiar</button>
    </form>
    </div>
</div>

    <table class="tabla-vet">
        <thead>
        <tr>
            <th>DNI</th>
            <th>Nombre completo</th>
            <th>Direccion</th>
            <th>Telefono</th>
            <th>Mail</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['DNI_p']); ?></td>
                <td><?php echo htmlspecialchars($row['Nombre_p']); ?></td>
                <td><?php echo htmlspecialchars($row['Direccion_p']); ?></td>
                <td><?php echo htmlspecialchars($row['Telf_p']); ?></td>
                <td><?php echo htmlspecialchars($row['Mail_p']); ?></td>
                <td>
                    <?php 
                        echo "<a href='../deletes/eliminar_propietarios.php?id={$row['DNI_p']}' class='delP' name='delP'>Eliminar</a>";    ?> <br>
                    <?php    echo "<a href='../updates/updates_propietarios.php?id={$row['DNI_p']}' class='editP' name='editP'>Editar</a>"; 
                    ?> 
                </td>
            </tr>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
                <td colspan="5">No hay propietarios registrados.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
