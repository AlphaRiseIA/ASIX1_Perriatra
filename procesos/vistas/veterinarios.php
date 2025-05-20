<?php
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// veterinarios.php

include '../conn/conexion.php';
include '../conn/conectarse.php';

// Leer filtros desde GET
$filtroEspecialidad = isset($_GET['especialidad']) ? trim($_GET['especialidad']) : '';
$filtroSalario      = isset($_GET['salario'])      ? trim($_GET['salario'])      : '';

// Construir consulta con LEFT JOIN para incluir NULLs
$sql = "SELECT u.*,
               v.*,
               e.*
        FROM usuario u
        INNER JOIN veterinarios v ON u.id_u = v.id_v
        LEFT JOIN especialidades e ON v.id_e = e.id_e
        WHERE 1=1";

// Filtro por especialidad
if (!empty($filtroEspecialidad)) {
    $esp = mysqli_real_escape_string($conn, $filtroEspecialidad);
    $sql .= " AND e.Nombre_e = '$esp'";
}

// Filtro por salario
if (!empty($filtroSalario)) {
    switch ($filtroSalario) {
        case '0-1000':  $sql .= " AND v.Salario_v BETWEEN 0 AND 1000"; break;
        case '1000-2000': $sql .= " AND v.Salario_v BETWEEN 1000 AND 2000"; break;
        case '2000-3000': $sql .= " AND v.Salario_v BETWEEN 2000 AND 3000"; break;
        case '3000-5000': $sql .= " AND v.Salario_v BETWEEN 3000 AND 5000"; break;
        case '5000+':    $sql .= " AND v.Salario_v > 5000"; break;
        default:
            if (is_numeric($filtroSalario)) {
                $min = intval($filtroSalario);
                $sql .= " AND v.Salario_v >= $min";
            }
    }
}

// Ejecutar consulta
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Veterinarios</title>
  <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
  <a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
  <h1>Listado de Veterinarios</h1>

  <div class="filtros-container">
    <form method="GET" action="veterinarios.php" class="filtros-flex">
      <?php
      // Obtener especialidades para el select
      $espQuery = "SELECT Nombre_e FROM especialidades ORDER BY Nombre_e";
      $espResult = mysqli_query($conn, $espQuery);
      ?>
      <div class="filtro-item">
        <label>Especialidad:</label>
        <select name="especialidad">
          <option value="">-- Todas --</option>
          <?php while ($esp = mysqli_fetch_assoc($espResult)): ?>
            <option value="<?php echo htmlspecialchars($esp['Nombre_e']); ?>"
              <?php if ($filtroEspecialidad === $esp['Nombre_e']) echo 'selected'; ?> >
              <?php echo htmlspecialchars($esp['Nombre_e']); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="filtro-item">
        <label>Salario (€):</label>
        <select name="salario" onchange="this.form.submit()">
          <option value="">-- Todos --</option>
          <option value="0-1000"   <?php if ($filtroSalario==='0-1000') echo 'selected'; ?>>0–1.000</option>
          <option value="1000-2000"<?php if ($filtroSalario==='1000-2000') echo 'selected'; ?>>1.000–2.000</option>
          <option value="2000-3000"<?php if ($filtroSalario==='2000-3000') echo 'selected'; ?>>2.000–3.000</option>
          <option value="3000-5000"<?php if ($filtroSalario==='3000-5000') echo 'selected'; ?>>3.000–5.000</option>
          <option value="5000+"    <?php if ($filtroSalario==='5000+') echo 'selected'; ?>>>5.000</option>
        </select>
      </div>
      <button type="submit" class="no">Aplicar</button>
      <button type="button" class="no" onclick="window.location='veterinarios.php'">Limpiar</button>
    </form>
  </div>

  <table class="tabla-vet">
    <thead>
      <tr>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Especialidad</th>
        <th>Salario (€)</th>
        <th>Fecha Contrato</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['nombre_u']); ?></td>
          <td><?php echo htmlspecialchars($row['Nombre_v']); ?></td>
          <td><?php echo htmlspecialchars($row['Telf_v']); ?></td>
          <td><?php echo $row['Nombre_e'] ? htmlspecialchars($row['Nombre_e']) : '<em>Sin especialidad</em>'; ?></td>
          <td><?php echo $row['Salario_v'] !== null ? number_format($row['Salario_v'],2,',','.') : '<em>—</em>'; ?></td>
          <td><?php echo ($row['Fecha_Contrato_v']);?></td>
          <td>
            <a href="../deletes/eliminar_veterinario.php?id=<?php echo urlencode($row['id_v']); ?>">Eliminar</a><br>
            <a href="../updates/update_veterinarios.php?id=<?php echo urlencode($row['id_v']); ?>">Editar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6">No hay veterinarios que coincidan.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
```
