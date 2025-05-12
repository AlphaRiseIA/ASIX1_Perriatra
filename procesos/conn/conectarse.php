<?php
include "conexion.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "<script>alert('Error de conexion');</script>";
    die("Error de conexión: " . mysqli_connect_error());
    }
?>

