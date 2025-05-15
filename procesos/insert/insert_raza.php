<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de campos obligatorios
    if (empty($_POST['nombre']) || empty($_POST['id_esp'])) {
        header("Location: ../forms/form_raza.php?error=Todos los campos son obligatorios");
        exit();
    }

    $nombre = strtolower(trim($_POST['nombre']));
    $id_esp = intval($_POST['id_esp']);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Comprobar si la raza ya existe para esa especie (ignorando mayúsculas/minúsculas)
    $check = $conn->prepare("SELECT id_r FROM raza WHERE nombre_r = ?");
    $check->bind_param("s", $nombre);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        $conn->close();
        header("Location: ../forms/form_raza.php?error=Esa-raza-ya-esta-registrada");
        exit();
    }

    $check->close();

    // Insertar nueva raza
    $stmt = $conn->prepare("INSERT INTO raza (nombre_r, id_esp) VALUES (?, ?)");
    $stmt->bind_param("si", $nombre, $id_esp);

    if ($stmt->execute()) {
        header("Location: ../forms/form_raza.php?exito=Raza-registrada-correctamente");
    } else {
        header("Location: ../forms/form_raza.php?error=Error-al-registrar-la-raza");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../forms/form_raza.php");
    exit();
}
