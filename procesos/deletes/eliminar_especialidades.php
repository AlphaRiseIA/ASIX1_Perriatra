<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM especialidades WHERE id_e = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: ../vistas/especialidades.php?msg=deleted");
        exit;
    } else {
        echo "Error al eliminar especialidad: " . mysqli_error($conn);
    }
} else {
    header("Location: ../vistas/especialidades.php");
    exit;
}
