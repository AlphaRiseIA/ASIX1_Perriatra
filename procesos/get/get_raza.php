<?php
// Configuración y conexión
include "../conn/conectarse.php";
include "../conn/conexion.php";
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
$id_especie = intval($_GET['id_especie']);

// Preparar la consulta
$sql = "SELECT id_r, nombre_r FROM raza WHERE id_esp = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Enlazar parámetro
    mysqli_stmt_bind_param($stmt, "i", $id_especie);

    // Ejecutar
    mysqli_stmt_execute($stmt);

    // Obtener resultado
    $result = mysqli_stmt_get_result($stmt);

    $razas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $razas[] = $row;
    }

    // Liberar y cerrar
    mysqli_stmt_close($stmt);
} else {
    // En caso de error en la preparación
    $razas = [];
}

// Devolver como JSON
header('Content-Type: application/json');
echo json_encode($razas);
?>
