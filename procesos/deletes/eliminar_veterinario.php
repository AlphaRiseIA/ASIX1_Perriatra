<?php
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM veterinarios WHERE id_v = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if ($stmt) {
        header("Location: ../vistas/veterinarios.php?veterinario=exito=eliminado");
        exit();
    } else {
        echo "Error al eliminar: " . mysqli_error($conn);
    }
} else {
    echo "ID no proporcionado.";
}
?>