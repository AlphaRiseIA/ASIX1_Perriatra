<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (isset($_GET['id'])) {
    $chip = $_GET['id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM usuario WHERE id_u = ?");
    mysqli_stmt_bind_param($stmt, 'i', $chip);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: ../vistas/mascotas.php?msg=deleted");
        exit;
    } else {
        echo "Error al eliminar mascota: " . mysqli_error($conn);
    }
} else {
    header("Location: ../vistas/mascotas.php");
    exit;
}
