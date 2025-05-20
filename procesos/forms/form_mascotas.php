<?php
include "../conn/conectarse.php";
include "../conn/conexion.php";
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
// Obtener propietarios
$propietarios = mysqli_query($conn, "SELECT DNI_p, Nombre_p FROM propietario");

// Obtener veterinarios
$veterinarios = mysqli_query($conn, "SELECT v.*, e.nombre_e FROM veterinarios v INNER JOIN especialidades e ON e.id_e = v.id_e");

// Obtener razas
$razas = mysqli_query($conn, "SELECT * from raza");

$especies = mysqli_query($conn, "SELECT * from especie");
?>

<script src="../../script/script.js"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styles.css">
    <title>Formulario Rellenar animales</title>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <div class="container">
        <h2>Formulario de Registro de Mascota</h2>
        <!-- ESPECIE -->
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
            <form id="form" action="../insert/insert_mascotas.php" method="POST">
                <label for="chip">Chip:</label>
                <input type="text" id="chip" name="chip" required placeholder="Introducir el chip de la mascota"
                    onblur="validarChip()">
                <span id="errorChip" class="error"></span>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Introducir nombre de la mascota"
                    onblur="validaNombre()">
                <span id="errorNombre" class="error"></span>

                <label for="genero">Género:</label>
                <select id="genero" name="genero" onblur="validarGenero()" required>
                    <option value="" disabled selected>-- Selecciona --</option>
                    <option value="M">M</option>
                    <option value="F">F</option>
                </select>
                <span id="errorGenero" class="error"></span>
                
                <label for="raza">Raza:</label>
                <select id="raza" name="raza" required onblur="validar_raza()">
                    <option value="" disabled selected>-- Selecciona una raza --</option>
                </select>
                <span id="error_raza" class="error"></span>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label><br>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required onblur="validarFechaNacimiento()">
                <span id="errorDate" class="error"></span>

                <!-- Campo Propietario (antes SELECT, ahora INPUT TEXT) -->
                <label for="propietario">DNI Propietario:</label>
                <input type="text" id="propietario" name="propietario" required onblur="validarPropietario()" placeholder="DNI del propietario">
                <span id="errorPropietario" class="error"></span>

                <label for="id_v">Veterinario:</label>
                <select id="id_v" name="id_v" onblur="validarVeterinario()">
                    <option value="" disabled selected>-- Selecciona un veterinario --</option>
                    <?php while($row = mysqli_fetch_assoc($veterinarios)): ?>
                    <option value="<?= $row['id_v'] ?>"><?= $row['Nombre_v'] ?> (ID: <?= $row['id_v'] ?>) (<?= $row['nombre_e'] ?>)</option>
                    <?php endwhile; ?>
                </select>
                <span id="errorVet" class="error"></span>

                <input type="submit" value="Registrar Mascota">
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