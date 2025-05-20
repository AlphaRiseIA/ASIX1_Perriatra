<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

// 1. Obtener y validar ID de raza por GET
$id_raza = isset($_GET['id']) && is_numeric($_GET['id']) 
    ? intval($_GET['id']) 
    : 0;
if ($id_raza <= 0) {
    header("Location: ../vistas/razas.php?error=id_invalido");
    exit;
}

$errores = [];
$nombre_raza = '';
$id_esp_seleccionada = null;

// 2. Procesar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2.1 Recoger y sanear
    $nombre_raza = trim($_POST['nombre'] ?? '');
    $id_esp_seleccionada = isset($_POST['id_esp']) ? intval($_POST['id_esp']) : 0;

    // 2.2 Validar existencia de la raza
    // (aunque ya comprobamos GET, vamos a asegurarnos)
    $stmt = mysqli_prepare($conn, "SELECT 1 FROM raza WHERE id_r = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_raza);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) === 0) {
        $errores[] = "La raza indicada no existe.";
    }
    mysqli_stmt_close($stmt);

    // 2.3 Validar nombre
    if ($nombre_raza === '') {
        $errores[] = "El nombre de la raza es obligatorio.";
    } elseif (mb_strlen($nombre_raza) < 3 || mb_strlen($nombre_raza) > 50) {
        $errores[] = "El nombre debe tener entre 3 y 50 caracteres.";
    } elseif (!preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-]+$/u', $nombre_raza)) {
        $errores[] = "Solo se permiten letras, espacios y guiones en el nombre.";
    } else {
        // Nombre único
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id_r FROM raza WHERE Nombre_r = ? AND id_r != ?"
        );
        mysqli_stmt_bind_param($stmt, "si", $nombre_raza, $id_raza);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errores[] = "Ya existe otra raza con ese nombre.";
        }
        mysqli_stmt_close($stmt);
    }

    // 2.4 Validar id_esp
    if ($id_esp_seleccionada <= 0) {
        $errores[] = "Debe seleccionar una especie válida.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT 1 FROM especie WHERE id_esp = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $id_esp_seleccionada);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) === 0) {
            $errores[] = "La especie seleccionada no existe.";
        }
        mysqli_stmt_close($stmt);
    }

    // 2.5 Si no hay errores, ejecutar UPDATE
    if (empty($errores)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE raza 
               SET Nombre_r = ?, id_esp = ?
             WHERE id_r = ?"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "sii",
            $nombre_raza,
            $id_esp_seleccionada,
            $id_raza
        );
        if (!mysqli_stmt_execute($stmt)) {
            $errores[] = "Error al actualizar: " . mysqli_stmt_error($stmt);
        } else {
            header("Location: ../vistas/razas.php?exito=actualizado");
            exit;
        }
        mysqli_stmt_close($stmt);
    }
} else {
    // 3. Cargar datos actuales en GET
    $stmt = mysqli_prepare(
        $conn,
        "SELECT Nombre_r, id_esp 
           FROM raza 
          WHERE id_r = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_raza);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre_raza, $id_esp_seleccionada);
    if (!mysqli_stmt_fetch($stmt)) {
        header("Location: ../vistas/razas.php?error=no_encontrado");
        exit;
    }
    mysqli_stmt_close($stmt);
}

// 4. Obtener todas las especies para el <select>
$especies = mysqli_query(
    $conn,
    "SELECT id_esp, nombre_esp FROM especie ORDER BY nombre_esp"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Raza</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../vistas/razas.php" class="btn-volver">⟵ Volver al listado</a>
    <div class="container">
        <h2>Editar Raza</h2>

        <?php if (!empty($errores)): ?>
        <div class="error-box">
            <ul>
            <?php foreach ($errores as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="id_raza" value="<?= $id_raza ?>">

            <label for="nombre">Nombre de la Raza:</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                required
                value="<?= htmlspecialchars($nombre_raza) ?>"
                onblur="validaNombre()"
            >
            <span id="errorNombre" class="error"></span>

            <label for="id_esp">Especie asociada:</label>
            <select id="id_esp" name="id_esp" required>
                <option value="">-- Selecciona especie --</option>
                <?php while ($row = mysqli_fetch_assoc($especies)): ?>
                    <option
                        value="<?= $row['id_esp'] ?>"
                        <?= $row['id_esp'] == $id_esp_seleccionada ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($row['nombre_esp']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <span id="errorEspecie" class="error"></span>

            <input type="submit" value="Actualizar Raza">
        </form>
    </div>
    <script src="../../script/script.js"></script>
</body>
</html>
