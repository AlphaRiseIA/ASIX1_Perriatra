<?php
include '../conn/conectarse.php';
include '../conn/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validación de campos
    if (empty($_POST['nombre']) || empty($_POST['id_esp'])) {
        header("Location: ../forms/form_raza.php?error=campos-obligatorios");
        exit();
    }

    // Limpiar y normalizar datos
    $nombre = trim($_POST['nombre']);
    $nombre = mb_strtolower($nombre, 'UTF-8');
    $nombre = ucwords($nombre); // Capitalizar palabras
    $id_esp = intval($_POST['id_esp']);

    // Verificar conexión
    if (mysqli_connect_errno()) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Comprobar si ya existe la raza (independientemente de mayúsculas/minúsculas)
    $sql_check = "SELECT id_r FROM raza WHERE LOWER(nombre_r) = LOWER(?) AND id_esp = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "si", $nombre, $id_esp);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header("Location: ../forms/form_raza.php?error=raza-ya-registrada");
        exit();
    }

    mysqli_stmt_close($stmt_check);

    // Insertar nueva raza
    $sql_insert = "INSERT INTO raza (nombre_r, id_esp) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "si", $nombre, $id_esp);

    if (mysqli_stmt_execute($stmt_insert)) {
        header("Location: ../forms/form_raza.php?exito=raza-registrada");
    } else {
        header("Location: ../forms/form_raza.php?error=fallo-insercion");
    }

    mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);

} else {
    header("Location: ../forms/form_raza.php");
    exit();
}
