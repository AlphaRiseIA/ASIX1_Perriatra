<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === '') {
        $errors[] = "El nombre es obligatorio.";
    } elseif (mb_strlen($nombre) < 3) {
        $errors[] = "El nombre debe tener al menos 3 caracteres.";
    } elseif (mb_strlen($nombre) > 100) {
        $errors[] = "El nombre no puede superar los 100 caracteres.";
    }

    if ($email === '') {
        $errors[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo electrónico no es válido.";
    }

    if ($asunto === '') {
        $errors[] = "El asunto es obligatorio.";
    } elseif (mb_strlen($asunto) < 3) {
        $errors[] = "El asunto debe tener al menos 3 caracteres.";
    } elseif (mb_strlen($asunto) > 150) {
        $errors[] = "El asunto no puede superar los 150 caracteres.";
    }

    if ($mensaje === '') {
        $errors[] = "El mensaje es obligatorio.";
    } elseif (mb_strlen($mensaje) < 10) {
        $errors[] = "El mensaje debe tener al menos 10 caracteres.";
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO form_ayuda (nombre, email, asunto, mensaje)
            VALUES (?, ?, ?, ?)
        ");
        mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $email, $asunto, $mensaje);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Ayuda</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>

<body>
    <a href="../../index.php" class="btn-volver">⟵ Volver a Inicio</a>
    <div class="container">
        <h2>Solicita Ayuda</h2>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (!empty($success)): ?>
            <p>Tu solicitud se ha enviado correctamente.</p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input onblur="validaNombre()" type="text" id="nombre" name="nombre" required
                value="<?= htmlspecialchars($nombre ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <span class="error" id="errorNombre"></span>

            <label for="email">Correo electrónico:</label>
            <input onblur="validarEmail()" type="email" id="email" name="email" required
                value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <span class="error" id="errorEmail"></span>
            
            <label for="asunto">Asunto:</label>
            <input type="text" id="asunto" name="asunto" required
                value="<?= htmlspecialchars($asunto ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje"
                required><?= htmlspecialchars($mensaje ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

            <input type="submit" value="Enviar Solicitud">
        </form>
    </div>
</body>

</html>