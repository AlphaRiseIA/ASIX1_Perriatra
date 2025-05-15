<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$errores = [];

// Validación de DNI
if (!isset($_POST["dni"]) || !is_numeric($_POST["dni"]) || strlen($_POST["dni"]) < 7 || strlen($_POST["dni"]) > 10) {
    $errores[] = "El DNI debe ser un número entre 7 y 10 dígitos.";
} else {
    $dni = intval($_POST["dni"]);
}

// Validación de Nombre
if (!isset($_POST["nombre"]) || trim($_POST["nombre"]) === "") {
    $errores[] = "El nombre no puede estar vacío.";
} else {
    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
}

// Validación de Dirección
if (!isset($_POST["direccion"]) || trim($_POST["direccion"]) === "") {
    $errores[] = "La dirección no puede estar vacía.";
} else {
    $direccion = mysqli_real_escape_string($conn, $_POST["direccion"]);
}

// Validación de Teléfono
if (!isset($_POST["telf"]) || !is_numeric($_POST["telf"]) || strlen($_POST["telf"]) < 7) {
    $errores[] = "El teléfono debe ser un número válido.";
} else {
    $telf = intval($_POST["telf"]);
}

// Validación de Email (ahora obligatorio)
if (!isset($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El correo electrónico es obligatorio y debe ser válido.";
} else {
    $mail = mysqli_real_escape_string($conn, $_POST["email"]);
}

// Si hay errores, vuelve al formulario
if (!empty($errores)) {
    $_SESSION["errores_registro"] = $errores;
    header("Location: ../forms/form_propietario.php");
    exit;
}

// Si no hay errores, procede a comprobar si el DNI ya existe
$sql_check_dni = "SELECT COUNT(*) AS count FROM propietario WHERE DNI_p = $dni";
$result_check_dni = mysqli_query($conn, $sql_check_dni);
$row = mysqli_fetch_assoc($result_check_dni);

if ($row['count'] > 0) {
    // El DNI ya existe en la base de datos
    header("Location: ../forms/form_propietario.php?registro=duplicado");
    exit;
}

// Si el DNI no existe, insertar los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO propietario (DNI_p, Nombre_p, Direccion_p, Telf_p, Mail_p) 
            VALUES ($dni, '$nombre', '$direccion', $telf, '$mail')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../forms/form_propietario.php?registro=exito");
    } else {
        // Si ocurre algún otro error
        header("Location: ../forms/form_propietario.php?registro=error");
    }

    mysqli_close($conn);
}
?>
