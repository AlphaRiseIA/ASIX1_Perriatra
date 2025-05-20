<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

// 1. Autenticación
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

// 2. Obtener ID de especialidad
$id_especialidad = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_especialidad <= 0) {
    header("Location: ../vistas/especialidades.php");
    exit();
}

// 3. CSRF: generar token en GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 4. Inicializar variables
$errores    = [];
$nombre_val = '';

// 5. Procesar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 5.1. CSRF
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("Token CSRF inválido.");
    }

    // 5.2. Recoger y sanear
    $nombre_val = trim($_POST['nombre'] ?? '');

    // 5.3. Validar no vacío y longitudes
    if ($nombre_val === '') {
        $errores['nombre'] = "El nombre no puede quedar vacío.";
    } elseif (mb_strlen($nombre_val) < 3) {
        $errores['nombre'] = "El nombre debe tener al menos 3 caracteres.";
    } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $nombre_val)) {
        $errores['nombre'] = "Solo se permiten letras y espacios.";
    }

    // 5.4. Validar unicidad (ignorando mayúsculas)
    if (empty($errores['nombre'])) {
        $normalized = mb_strtolower($nombre_val, 'UTF-8');
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id_e FROM especialidades WHERE LOWER(nombre_e) = ? AND id_e != ?"
        );
        mysqli_stmt_bind_param($stmt, "si", $normalized, $id_especialidad);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errores['nombre'] = "Ya existe otra especialidad con ese nombre.";
        }
        mysqli_stmt_close($stmt);
    }

    // 5.5. Si todo OK, actualizar y redirigir
    if (empty($errores)) {
        $nombre_norm = ucwords($normalized);
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE especialidades SET nombre_e = ? WHERE id_e = ?"
        );
        mysqli_stmt_bind_param($stmt, "si", $nombre_norm, $id_especialidad);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../vistas/especialidades.php?msg=updated");
            exit();
        } else {
            $errores['general'] = "Error al actualizar: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    // 6. GET: cargar valor actual
    $stmt = mysqli_prepare(
        $conn,
        "SELECT nombre_e FROM especialidades WHERE id_e = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_especialidad);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre_val);
    if (!mysqli_stmt_fetch($stmt)) {
        die("Especialidad no encontrada.");
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Especialidad</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../vistas/especialidades.php" class="btn-volver">⟵ Volver al listado</a>
    <div class="container">
        <h2>Editar Especialidad</h2>


        <form method="POST" action="update_especialidades.php?id=<?= $id_especialidad ?>">
            <input 
                type="hidden" 
                name="csrf_token" 
                value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
            >

            <label for="nombre">Nombre de la Especialidad:</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                required
                value="<?= htmlspecialchars($nombre_val) ?>"
                onblur="validaNombre()"
            >
            <span class="error"><?= $errores['nombre'] ?? '' ?></span>

            <input type="submit" value="Actualizar Especialidad">
        </form>
    </div>
    <script src="../../script/script.js"></script>
</body>
</html>
