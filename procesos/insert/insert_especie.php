<?php
include '../conn/conexion.php'; // Asegúrate de que esta conexión usa estilo procedural
include '../conn/conectarse.php'; // Si no es necesario, puedes eliminar uno
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['nombre'])) {
        header("Location: ../forms/form_especie.php?error=El-nombre-es-obligatorio");
        exit();
    }

    $nombre = strtolower(trim($_POST['nombre']));

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Verificar duplicado
    $sql_check = "SELECT id_esp FROM especie WHERE LOWER(nombre_esp) = LOWER(?)";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $nombre);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header("Location: ../forms/form_especie.php?error=La especie ya existe");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Insertar especie
    $sql_insert = "INSERT INTO especie (nombre_esp) VALUES (?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "s", $nombre);

    if (mysqli_stmt_execute($stmt_insert)) {
        header("Location: ../forms/form_especie.php?exito=Registro-exitoso");
    } else {
        header("Location: ../forms/form_especie.php?error=No-se-pudo-registrar-la-especie");
    }

    mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);
} else {
    header("Location: ../forms/form_especie.php");
    exit();
}
?>
