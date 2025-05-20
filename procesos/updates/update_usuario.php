<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";
if (!isset($_SESSION['nombre_u']) || $_SESSION['nombre_u'] !== 'admin') {
    header("Location: ../../index.php?no-tienes-acceso-aqui");
    exit();
}
$id_usuario = $_GET['id'] ?? '';
if (!$id_usuario) {
    header("Location: ../vistas/usuarios.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $nombre_usuario = trim($_POST['nombre_u']);
    $nombre_vet = trim($_POST['Nombre_v']);
    $telefono = trim($_POST['Telf_v']);
    $salario = floatval($_POST['Salario_v']);

    $especialidad = intval($_POST['id_e']);

    mysqli_query($conn, "
        UPDATE usuario SET
            nombre_u = '" . mysqli_real_escape_string($conn, $nombre_usuario) . "'
        WHERE id_u = '" . mysqli_real_escape_string($conn, $id_usuario) . "'
    ");

    mysqli_query($conn, "
        UPDATE Veterinarios SET
            Nombre_v = '" . mysqli_real_escape_string($conn, $nombre_vet) . "',
            Telf_v = '" . mysqli_real_escape_string($conn, $telefono) . "',
            Salario_v = '$salario',
            id_e = '$especialidad'
        WHERE id_u = '" . mysqli_real_escape_string($conn, $id_usuario) . "'
    ");

    header("Location: ../vistas/usuarios.php");
    exit;
}

$res = mysqli_query($conn, "
    SELECT u.*, v.*
    FROM usuario u
    JOIN Veterinarios v ON u.id_u = v.id_v
    WHERE u.id_u = '" . mysqli_real_escape_string($conn, $id_usuario) . "'
");
$usuario = mysqli_fetch_assoc($res);

$especialidades = mysqli_query($conn, "SELECT id_e, Nombre_e FROM especialidades");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario / Veterinario</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../vistas/usuarios.php" class="btn-volver">⟵ Volver al listado</a>
    <div class="container">
        <h2>Editar Usuario y Veterinario</h2>
        <form method="POST" action="">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_u']) ?>">

            <label for="nombre_u">Nombre de Usuario:</label>
            <input type="text" id="nombre_u" name="nombre_u" required value="<?= htmlspecialchars($usuario['nombre_u']) ?>">

            <label for="Nombre_v">Nombre completo (Vet):</label>
            <input type="text" id="Nombre_v" name="Nombre_v" required value="<?= htmlspecialchars($usuario['Nombre_v']) ?>">

            <label for="Telf_v">Teléfono:</label>
            <input type="text" id="Telf_v" name="Telf_v" required value="<?= htmlspecialchars($usuario['Telf_v']) ?>">

            <label for="Salario_v">Salario (€):</label>
            <input type="number" id="Salario_v" name="Salario_v" step="0.01" required value="<?= htmlspecialchars($usuario['Salario_v']) ?>">

            <label for="id_e">Especialidad:</label>
            <select name="id_e" id="id_e" required>
                <?php while ($esp = mysqli_fetch_assoc($especialidades)): ?>
                    <option value="<?= $esp['id_e'] ?>" <?= $esp['id_e'] == $usuario['id_e'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($esp['Nombre_e']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="submit" value="Actualizar Usuario">
        </form>
    </div>
</body>
</html>
