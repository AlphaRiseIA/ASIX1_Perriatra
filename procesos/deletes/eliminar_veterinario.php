<?php
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM artistas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if ($stmt) {
        header("Location: ../../index.php?artista=eliminado");
        exit();
    } else {
        echo "Error al eliminar: " . mysqli_error($conn);
    }
} else {
    echo "ID no proporcionado.";
}
?>