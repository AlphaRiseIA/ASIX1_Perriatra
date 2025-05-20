<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

// 1. Autenticación
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

// 2. Obtener ID vía GET
$id_especie = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_especie <= 0) {
    header("Location: ../vistas/especies.php");
    exit();
}

$errores = [];
$nombre_especie = '';

// 3. Si viene POST, procesar y validar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_especie = trim($_POST['nombre'] ?? '');
    $id_post = intval($_POST['id_especie'] ?? 0);

    // 3.1. Validaciones
    if ($nombre_especie === '') {
        $errores['nombre'] = "El nombre de la especie no puede quedar vacío.";
    } elseif (mb_strlen($nombre_especie) < 3) {
        $errores['nombre'] = "El nombre debe tener al menos 3 caracteres.";
    }

    // 3.2. Unicidad: no debe existir otra especie con el mismo nombre
    if (empty($errores['nombre'])) {
        $stmt_check = mysqli_prepare(
            $conn,
            "SELECT id_esp FROM especie WHERE nombre_esp = ? AND id_esp != ?"
        );
        mysqli_stmt_bind_param($stmt_check, "si", $nombre_especie, $id_post);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $errores['nombre'] = "Ya existe otra especie con ese nombre.";
        }
        mysqli_stmt_close($stmt_check);
    }

    // 3.3. Si no hay errores, ejecutar UPDATE
    if (empty($errores)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE especie SET nombre_esp = ? WHERE id_esp = ?"
        );
        mysqli_stmt_bind_param($stmt, "si", $nombre_especie, $id_post);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ../vistas/especies.php");
        exit();
    }

} else {
    // 4. En GET, cargar datos actuales
    $stmt = mysqli_prepare(
        $conn,
        "SELECT nombre_esp FROM especie WHERE id_esp = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_especie);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre_especie);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Especie</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>
<body>
    <a href="../vistas/especies.php" class="btn-volver">⟵ Volver al listado</a>
    <div class="container">
        <h2>Editar Especie</h2>

        <form method="POST" action="">
            <input type="hidden" name="id_especie" value="<?= $id_especie ?>">

            <label for="nombre">Nombre de la Especie:</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                value="<?= htmlspecialchars($nombre_especie) ?>"
                onblur="validaNombre()"
            >
            <span id="errorNombre" class="error"><?= $errores['nombre'] ?? '' ?></span>

            <input type="submit" value="Actualizar Especie">
        </form>
    </div>
</body>
</html>
