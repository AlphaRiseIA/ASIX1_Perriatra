<?php
include "../conn/conectarse.php";
include "../conn/conexion.php";
if (!$_SESSION['nombre_u'] === 'admin') {
    header("Location: ../../index.php?no-tienes-acceso-aqui");
    exit();
}
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
        echo "Error al eliminar veterianrio: " . mysqli_error($conn);
    }
} else {
    header("Location: ../vistas/veterinarios.php?veterinario=error=eliminado");
}
?>