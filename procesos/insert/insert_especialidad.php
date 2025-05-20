<?php
session_start();
include "../conn/conexion.php";
include "../conn/conectarse.php";
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// Comprobar conexión
if (mysqli_connect_errno()) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Recoger y limpiar el dato
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if (strlen($nombre) < 3 || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/', $nombre)) {
    header("Location: ../forms/form_especialidad.php?error=nombre-no-valido");
    exit;
}

// Normalizar: primera letra en mayúscula de cada palabra
$nombre = ucwords(strtolower($nombre));

// Verificar si ya existe
$sql_check = "SELECT id_e FROM especialidades WHERE LOWER(nombre_e) = LOWER(?)";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "s", $nombre);
mysqli_stmt_execute($stmt_check);
mysqli_stmt_store_result($stmt_check);

if (mysqli_stmt_num_rows($stmt_check) > 0) {
    mysqli_stmt_close($stmt_check);
    mysqli_close($conn);
    header("Location: ../forms/form_especialidad.php?error=especialidad-ya-existe");
    exit;
}
mysqli_stmt_close($stmt_check);

// Insertar nueva especialidad
$sql_insert = "INSERT INTO especialidades (nombre_e) VALUES (?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);
mysqli_stmt_bind_param($stmt_insert, "s", $nombre);

if (mysqli_stmt_execute($stmt_insert)) {
    header("Location: ../forms/form_especialidad.php?exito=registro-correcto");
} else {
    header("Location: ../forms/form_especialidad.php?error=fallo-insercion");
}

mysqli_stmt_close($stmt_insert);
mysqli_close($conn);
?>
