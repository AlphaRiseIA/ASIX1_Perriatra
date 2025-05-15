<?php
// Conexión a la base de datos
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

// Comprobar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoger y limpiar el dato
$nombre = strtolower(trim($_POST['nombre']));

// Validación
if (strlen($nombre) < 3 || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/', $nombre)) {
    // Redirigir de nuevo al formulario con mensaje de error
    header("Location: ../forms/form_especialidad.php?error=1");
    exit;
}
// Comprobar si ya existe
    $check = $conn->prepare("SELECT id_e FROM especialidades WHERE nombre_e = ?");
    $check->bind_param("s", $nombre);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Ya existe
        $check->close();
        $conn->close();
        header("Location: ../forms/form_especialidad.php?error-especialidad-ya-existe");
        exit();
    }

    $check->close();
// Preparar y ejecutar el INSERT
$stmt = $conn->prepare("INSERT INTO especialidades (nombre_e) VALUES (?)");
$stmt->bind_param("s", $nombre);

if ($stmt->execute()) {
   header("Location: ../forms/form_especialidad.php?existo-en-registro");
} else {
    header("Location: ../forms/form_especialidad.php?error=2");;
}

$stmt->close();
$conn->close();
?>
