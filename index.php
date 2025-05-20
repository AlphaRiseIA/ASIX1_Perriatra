<?php
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ./procesos/sesion/Login.php");
    exit();
}
$isAdmin = ($_SESSION['nombre_u'] === 'admin');
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
        <h1>Panel de Administración Veterinaria</h1><br>
        <p class="welcome">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['nombre_u']); ?></strong></p>

        <div class="category-container">
            <h2>Consultar, editar y eliminar Información</h2>
            <div class="admin-options">
                <a href="./procesos/vistas/veterinarios.php">Veterinarios</a>
                <a href="./procesos/vistas/propietarios.php">Propietarios</a>
                <a href="./procesos/vistas/mascotas.php">Mascotas</a>
                <a href="./procesos/vistas/razas.php">Razas</a>
                <a href="./procesos/vistas/especies.php">Especies</a>
                <a href="./procesos/vistas/especialidades.php">Especialidades</a>
                <?php if ($isAdmin): ?>
                <a href="./procesos/vistas/usuarios.php">Usuarios</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="category-container">
            <h2>Registrar:</h2>
            <div class="admin-options">
                <a href="./procesos/forms/form_propietario.php">Propietario</a>
                <a href="./procesos/forms/form_mascotas.php">Mascota</a>
                <a href="./procesos/forms/form_raza.php">Raza</a>
                <a href="./procesos/forms/form_especie.php">Especie</a>
                <a href="./procesos/forms/form_especialidad.php">Especialidad</a>
                 <?php if ($isAdmin): ?>
                <a href="./procesos/forms/form_veterinario.php">Veterinarios</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="category-container">
            <h2>Ayuda</h2>
            <div class="admin-options">
                <a href="./FAQ/Manual.php">Manual De Usuario</a>
            </div>
        </div>
        
        <a class="logout" href="./procesos/sesion/cerrarsesion.php">Cerrar sesión</a>
    </div>

</body>
</html>
