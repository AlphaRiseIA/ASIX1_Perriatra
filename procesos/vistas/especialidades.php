<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// 1. Leer filtro de especialidad desde GET
$filtroEspecialidad = isset($_GET['especialidad']) ? trim($_GET['especialidad']) : '';

// 2. Obtener lista de todas las especialidades
$espQuery  = "SELECT id_e, Nombre_e FROM especialidades ORDER BY Nombre_e";
$espResult = mysqli_query($conn, $espQuery);

// 3. Construir consulta principal con posible filtro
$sql = "SELECT * from especialidades";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Veterinarios por Especialidad</title>
  <link rel="stylesheet" href="../../css/styles.css">
   <script src="../../script/script.js"></script>

</head>
<body>
  <a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
  <h1>Listado de Especialidades</h1>

  <table class="tabla-vet">
    <thead>
      <tr>
        <th>Especialidad</th>
        <th>Acciones</th>
      </tr>
          </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['Nombre_e']); ?></td>
            <td>
               <?php 
                        echo "<a href='../deletes/eliminar_especialidades.php?id={$row['id_e']}' class='delE' name='delE'>Eliminar</a>";   ?> <br>
                 <?php  echo "<a href='../updates/update_especialidades.php?id={$row['id_e']}' class='editE' name='editE'>Editar</a>"; 
                ?> 
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">No hay veterinarios para la especialidad seleccionada.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>