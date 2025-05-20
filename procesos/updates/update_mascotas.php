<?php 
session_start(); 
include "../conn/conectarse.php"; 
include "../conn/conexion.php"; 
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// 1. Leer chip original desde GET (para mostrar el formulario) 
$orig_chip = isset($_GET['id']) ? trim($_GET['id']) : ''; 
if (!$orig_chip) { 
    header("Location: ../../mascotas.php"); 
    exit; 
} 

// 2. Procesar actualización cuando se envía el formulario 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    // Leer chip original del formulario hidden 
    $orig_chip = trim($_POST['orig_chip']); 
    
    // Recoger y sanear nuevos valores 
    $new_chip         = mysqli_real_escape_string($conn, trim($_POST['chip'])); 
    $nombre           = mysqli_real_escape_string($conn, trim($_POST['nombre'])); 
    $genero           = mysqli_real_escape_string($conn, $_POST['genero']); 
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']); 
    $id_r             = mysqli_real_escape_string($conn, $_POST['id_r']); 
    $dni_p            = mysqli_real_escape_string($conn, $_POST['dni_p']); 
    $id_v             = mysqli_real_escape_string($conn, $_POST['id_v']); 
    
    // Validar campos obligatorios 
    if (empty($new_chip) || empty($nombre) || empty($fecha_nacimiento) || empty($dni_p) || empty($id_r)) { 
        die('Los campos Chip, Nombre, Fecha de Nacimiento, Raza y Propietario son obligatorios.'); 
    } 
    
    // Ejecutar UPDATE procedural 
    $update_sql = "UPDATE mascota SET 
        Chip_m = '$new_chip', 
        Nombre_m = '$nombre', 
        genero_m = '$genero', 
        Fecha_Nacimiento_m = '$fecha_nacimiento',
        id_r = '$id_r',
        DNI_p = '$dni_p',
        id_v = " . ($id_v ? "'$id_v'" : "NULL") . "
        WHERE Chip_m = '" . mysqli_real_escape_string($conn, $orig_chip) . "'"; 
    
    if (!mysqli_query($conn, $update_sql)) { 
        die('Error al actualizar: ' . mysqli_error($conn)); 
    } 
    
    // Redirigir tras actualizar 
    header("Location: ../../mascotas.php"); 
    exit; 
} 

// 3. Si no es POST, obtener datos actuales para llenar el formulario 
$orig_chip_esc = mysqli_real_escape_string($conn, $orig_chip); 
$query = "SELECT m.Chip_m, m.Nombre_m, m.genero_m, m.Fecha_Nacimiento_m, m.id_r, m.DNI_p, m.id_v, 
                 p.Nombre_p, v.Nombre_v, r.Nombre_r
          FROM mascota m
          LEFT JOIN propietario p ON m.DNI_p = p.DNI_p
          LEFT JOIN veterinarios v ON m.id_v = v.id_v
          LEFT JOIN raza r ON r.id_r = m.id_r
          WHERE m.Chip_m = '$orig_chip_esc' LIMIT 1"; 
$res = mysqli_query($conn, $query); 
if (!$res || mysqli_num_rows($res) === 0) { 
    die("Mascota no encontrada."); 
} 
$mascota = mysqli_fetch_assoc($res); 

// Obtener lista de razas para el select
$query_razas = "SELECT id_r, Nombre_r FROM raza ORDER BY Nombre_r";
$res_razas = mysqli_query($conn, $query_razas);
if (!$res_razas) {
    die("Error al obtener razas: " . mysqli_error($conn));
}

// Obtener lista de propietarios para el select
$query_propietarios = "SELECT DNI_p, Nombre_p FROM propietario ORDER BY Nombre_p";
$res_propietarios = mysqli_query($conn, $query_propietarios);
if (!$res_propietarios) {
    die("Error al obtener propietarios: " . mysqli_error($conn));
}

// Obtener lista de veterinarios para el select
$query_veterinarios = "SELECT id_v, Nombre_v FROM veterinarios ORDER BY Nombre_v";
$res_veterinarios = mysqli_query($conn, $query_veterinarios);
if (!$res_veterinarios) {
    die("Error al obtener veterinarios: " . mysqli_error($conn));
}
$especies = mysqli_query($conn, "SELECT * from especie");
?> 

<!DOCTYPE html> 
<html lang="es"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Editar Mascota</title> 
    <link rel="stylesheet" href="../../css/styles.css"> 
    <script src="../../script/script.js"></script> 
</head> 
<body> 
    <a href="../vistas/mascotas.php" class="btn-volver">⟵ Volver al listado</a> 
    <div class="container"> 
        <h2>Editar Mascota</h2> 
        <label class="label-inline" for="especie">Especie:</label>
                <select class="select-especie" id="especie" name="especie" required onchange="cargarRazasPorEspecie(this.value)">
                    <option value="" disabled selected>-- Selecciona una especie --</option>
                    <?php
                    // Asumiendo que ya tienes una consulta previa $especies = mysqli_query(...);
                    while ($row = mysqli_fetch_assoc($especies)) {
                        echo "<option value=\"" . htmlspecialchars($row['id_esp']) . "\">" . htmlspecialchars($row['nombre_esp']) . "</option>";
                    }
                    ?>
                </select>
                <span id="errorEspecie" class="error"></span>
        <form method="POST" action=""> 
            <!-- Hidden con chip original --> 
            <input type="hidden" name="orig_chip" value="<?= htmlspecialchars($mascota['Chip_m']) ?>"> 
            <label for="chip">Chip:</label>
            
            <input type="text" id="chip" onblur="validarChip()"  name="chip" required value="<?= htmlspecialchars($mascota['Chip_m']) ?>"> 
            <span id="errorChip" class="error"></span> 
            <label for="nombre">Nombre:</label> 
            <input type="text" id="nombre" onblur="validaNombre()" name="nombre" required value="<?= htmlspecialchars($mascota['Nombre_m']) ?>"> 
            <span id="errorNombre" class="error"></span>

            <label for="genero">Género:</label> 
            <select onblur="validarGenero()" id="genero" name="genero" required> 
                <option value="M" <?=$mascota['genero_m'] === 'M' ? 'selected' : '' ?>>M</option> 
                <option value="F" <?=$mascota['genero_m'] === 'F' ? 'selected' : '' ?>>F</option> 
            </select> 
            <span id="errorGenero" class="error"></span>
            
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input onblur="validarFechaNacimiento()" type="date" id="fecha_nacimiento" name="fecha_nacimiento" required 
                value="<?= htmlspecialchars($mascota['Fecha_Nacimiento_m']) ?>"> 
                <span id="errorDate" class="error"></span>
                
           
            <label for="raza">Raza:</label>
                <select id="raza" name="raza" required onblur="validar_raza()">
                    <option value="" disabled selected>-- Selecciona una raza --</option>
                </select>
                <span id="error_raza" class="error"></span>
            
            <label for="dni_p">Propietario:</label>
            <select onblur="validarDNI()" id="dni_p" name="dni_p" required>
                <?php while ($propietario = mysqli_fetch_assoc($res_propietarios)): ?>
                <option value="<?= htmlspecialchars($propietario['DNI_p']) ?>" 
                    <?= $mascota['DNI_p'] == $propietario['DNI_p'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($propietario['Nombre_p']) ?> (DNI: <?= htmlspecialchars($propietario['DNI_p']) ?>)
                </option>
                <?php endwhile; ?>
            </select>
            <span id="errorNombreProp" class="error"></span>
            
            <label for="id_v">Veterinario:</label>
            <select onblur="validarVeterinario()"id="id_v" name="id_v">
                <option value="">-- Seleccionar veterinario --</option>
                <?php while ($veterinario = mysqli_fetch_assoc($res_veterinarios)): ?>
                <option value="<?= htmlspecialchars($veterinario['id_v']) ?>" 
                    <?= $mascota['id_v'] == $veterinario['id_v'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($veterinario['Nombre_v']) ?> (ID: <?= htmlspecialchars($veterinario['id_v']) ?>)
                </option>
                <?php endwhile; ?>
            </select>
            <span id="errorNombreProp" class="error"></span>
            
            <input type="submit" value="Actualizar"> 
        </form> 
    </div> 
</body> 
<script>
document.getElementById("especie").addEventListener("change", function() {
    const especieId = this.value;
    const razaSelect = document.getElementById("raza");

    if (!especieId) {
        razaSelect.innerHTML = '<option value="" disabled selected>-- Selecciona una raza --</option>';
        razaSelect.disabled = true;
        return;
    }

    fetch("../get/get_raza.php?id_especie=" + especieId)
        .then(response => response.json())
        .then(data => {
            razaSelect.innerHTML = '<option value="">-- Selecciona una raza --</option>';
            data.forEach(raza => {
                const option = document.createElement("option");
                option.value = raza.id_r;
                option.textContent = raza.nombre_r;
                razaSelect.appendChild(option);
            });
            razaSelect.disabled = false;
        });
});
</script>
</html>