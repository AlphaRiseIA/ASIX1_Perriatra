<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

// Initialize variables
$id = null;
$vet = null;
$errores = [];

// Get the ID parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
}

// Make sure we have an ID
if (!$id) {
    header("Location: ../vistas/veterinarios.php?error=noid");
    exit();
}

// Obtain current data
$stmt = mysqli_prepare($conn, "SELECT Nombre_v, Telf_v, id_e, Fecha_Contrato_v, Salario_v FROM veterinarios WHERE id_v = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$vet = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// If veterinarian not found
if (!$vet) {
    header("Location: ../vistas/veterinarios.php?error=notfound");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nombre_v = trim($_POST['nombre']);
    $Telf_v = trim($_POST['telefono']);
    $id_e = intval($_POST['id_esp']);
    $Fecha_contrato_v = trim($_POST['fecha_nacimiento']);
    $Salario_v = trim($_POST['salario']);

    // Validaciones básicas
    if (strlen($Nombre_v) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres.";
    }

    if (!preg_match('/^\d{9}$/', $Telf_v)) {
        $errores[] = "El teléfono debe tener 9 dígitos.";
    }

    if (!is_numeric($Salario_v) || floatval($Salario_v) <= 0) {
        $errores[] = "El salario debe ser un número positivo.";
    }

    // Verificar nombre único (excepto en el propio ID)
    $stmt_check = mysqli_prepare($conn, "SELECT id_v FROM veterinarios WHERE Nombre_v = ? AND id_v != ?");
    mysqli_stmt_bind_param($stmt_check, "si", $Nombre_v, $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $errores[] = "Ya existe otro veterinario con ese nombre.";
    }

    mysqli_stmt_close($stmt_check);

    if (empty($errores)) {
        $sql = "UPDATE veterinarios SET
                    Nombre_v = ?, 
                    Telf_v = ?, 
                    id_e = ?, 
                    Fecha_contrato_v = ?, 
                    Salario_v = ?
                WHERE id_v = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssissi", $Nombre_v, $Telf_v, $id_e, $Fecha_contrato_v, $Salario_v, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: ../vistas/veterinarios.php?exito");
        exit();
    }
}

// Get specialties list for the dropdown
$especialidades = mysqli_query($conn, "SELECT id_e, Nombre_e FROM especialidades");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Veterinario</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>

<body>
    <a href="../vistas/veterinarios.php" class="btn-volver">⟵ Volver al listado</a>
    <div class="container">
        <h2>Editar Veterinario</h2>
        
        <form method="POST" action="update_veterinarios.php">
            <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($id) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" onblur="validaNombre()" id="nombre" name="nombre" required value="<?= htmlspecialchars($vet['Nombre_v']) ?>">
            <span id="errorNombre" class="error"></span>
            
            <label for="telefono">Teléfono:</label>
            <input onblur="validaTelefono()" type="text" id="telefono" name="telefono" required value="<?= htmlspecialchars($vet['Telf_v']) ?>">
            <span class="error" id="errorTelefono"></span> 
            
            <label for="id_esp">Especialidad:</label>
            <select onblur="validaEspecialidad()" id="id_esp" name="id_esp" required>
                <?php while ($row = mysqli_fetch_assoc($especialidades)): ?>
                <option value="<?= $row['id_e'] ?>" <?= $row['id_e'] == $vet['id_e'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['Nombre_e']) ?>
                </option>
                <?php endwhile; ?>
            </select>
            <span class="error" id="error"></span>
            
            <label for="fecha_nacimiento">Fecha de Contrato:</label>
            <input onblur="validarFechaNacimiento()" type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                value="<?= htmlspecialchars($vet['Fecha_Contrato_v']) ?>">
            <span class="error" id="errorDate"></span>
            
            <label for="salario">Salario:</label>
            <input onblur="validaSalario()" type="text" id="salario" name="salario" required
                value="<?= htmlspecialchars($vet['Salario_v']) ?>">
            <span class="error" id="errorSalario"></span>
            
            <input type="submit" value="Actualizar Veterinario">
        </form>
    </div>
</body>

</html>