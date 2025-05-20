<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

$dni = isset($_GET['id']) ? trim($_GET['id']) : '';
if (empty($dni)) {
    header("Location: ../vistas/propietarios.php");
    exit();
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni_original = isset($_POST['dni_original']) ? trim($_POST['dni_original']) : '';
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $mail = isset($_POST['email']) ? trim($_POST['email']) : '';


    if (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres.";
    }

    if (strlen($direccion) < 5) {
        $errores[] = "La dirección debe tener al menos 5 caracteres.";
    }

    if (!preg_match('/^\d{9}$/', $telefono)) {
        $errores[] = "El teléfono debe contener exactamente 9 dígitos.";
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }

    // Si no hay errores, actualizamos
    if (empty($errores)) {
        $sql = "UPDATE propietario SET  
                    Nombre_p = ?, 
                    Direccion_p = ?, 
                    Telf_p = ?, 
                    Mail_p = ? 
                WHERE DNI_p = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $nombre, $direccion, $telefono, $mail, $dni_original);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("Location: ../vistas/propietarios.php");
        exit();
    }
}

// Obtener los datos del propietario actual
$sql_select = "SELECT DNI_p, Nombre_p, Direccion_p, Telf_p, Mail_p FROM propietario WHERE DNI_p = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "s", $dni);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$propietario = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_select);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Propietario</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>

<body>
    <a href="../vistas/propietarios.php" class="btn-volver">⟵ Volver al listado</a>
    <div class="container">
        <h2>Editar Propietario</h2>
        <form method="POST" action="">
            <input type="hidden" name="dni_original" value="<?= htmlspecialchars($propietario['DNI_p']) ?>">

            <label for="nombre">Nombre completo:</label>
            <input onblur="validaNombre()" type="text" id="nombre" name="nombre" required
                value="<?= htmlspecialchars($propietario['Nombre_p']) ?>">
            <span id="errorNombre" class="error"></span>
            <label for="direccion">Dirección:</label>
            <input onblur="validarDireccion()" type="text" id="direccion" name="direccion" required
                value="<?= htmlspecialchars($propietario['Direccion_p']) ?>">
            <span id="errorDir" class="error"></span>
            <label for="telefono">Teléfono:</label>
            <input onblur="validaTelefono()" type="text" id="telefono" name="telefono" required
                value="<?= htmlspecialchars($propietario['Telf_p']) ?>">
            <span id="errorTelefono" class="error"></span>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" onblur="validarEmail()" name="email" required value="<?= htmlspecialchars($propietario['Mail_p']) ?>">
            <span id="errorEmail" class="error"></span>
            <input type="submit" value="Actualizar Propietario">
        </form>
    </div>
</body>

</html>