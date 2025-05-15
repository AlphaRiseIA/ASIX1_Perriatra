<?php
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ./procesos/sesion/Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Veterinaria</title>
    <link rel="stylesheet" href="./css/styles.css"> 
</head>

<body>

    <div class="admin-panel">
        <h1>Bienvenido al Panel de Administración</h1>
        <p class="welcome">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre_u']); ?></strong></p>

        <div class="admin-options">
            <a href="./procesos/vistas/veterinarios.php">Veterinarios</a>
            <a href="./procesos/vistas/propietarios.php">Propietarios</a>
            <a href="./procesos/vistas/mascotas.php">Mascotas</a>
            <a href="./procesos/vistas/razas.php">Razas</a>
            <a href="./procesos/vistas/especies.php">Especies</a>
            <a href="./procesos/vistas/especialidades.php">Especialidades</a>
            <a href="./procesos/forms/form_mascotas.php">Registrar Mascota</a>
            <a href="./procesos/forms/form_propietario.php">Registrar Propietario</a>
            <a href="./procesos/forms/form_veterinario.php">Registrar Veterinario</a>
            <a href="./procesos/forms/form_raza.php">Registrar Raza</a>
            <a href="./procesos/forms/form_especialidad.php">Registrar Especialidad</a>
            <a href="./procesos/forms/form_especie.php">Registrar Especie Animal</a>
        </div>

        <a class="logout" href="./procesos/sesion/cerrarsesion.php">Cerrar sesión</a>
    </div>

</body>
</html>
