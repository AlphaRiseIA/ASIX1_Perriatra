<?php
session_start();
include "../conn/conectarse.php";
include "../conn/conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../sesion/Registro.php?error=metodo-no-permitido");
    exit();
}

// 1. Recoger y limpiar datos
$username        = strtolower(trim($_POST['usuario'] ?? ''));
$nombre          = trim($_POST['nombre'] ?? '');
$telefono        = trim($_POST['telefono'] ?? '');
$contrasena      = $_POST['password'] ?? '';
$confirmpassword = $_POST['confirmPassword'] ?? '';
$salario         = $_POST['salario'] ?? '';
$especialidad    = $_POST['especialidad'] ?? '';

// 2. Validaciones de campos
if (empty($username) || empty($nombre) || empty($telefono) || empty($contrasena) || empty($confirmpassword) || empty($salario) || empty($especialidad)) {
    header("Location: ../sesion/Registro.php?error=campos-vacios");
    exit();
}

if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
    header("Location: ../sesion/Registro.php?error=nombre-novalido");
    exit();
}

if (!preg_match('/^\+?[0-9]{9,15}$/', $telefono)) {
    header("Location: ../sesion/Registro.php?error=telefono-novalido");
    exit();
}

if (strlen($contrasena) < 8 || !preg_match('/(?=.*[a-z])(?=.*[A-Z])/', $contrasena)) {
    header("Location: ../sesion/Registro.php?error=password-debil");
    exit();
}

if ($contrasena !== $confirmpassword) {
    header("Location: ../sesion/Registro.php?error=password-nomatch");
    exit();
}

if (!preg_match('/^\d+(\.\d{1,2})?$/', $salario) || $salario <= 0) {
    header("Location: ../sesion/Registro.php?error=salario-novalido");
    exit();
}

// 3. Comprobar duplicidades (usuario, nombre de veterinario, teléfono)
$duplicado = false;
$mensaje = '';

// Comprobación en Veterinarios.Nombre_v (procedural)
$sql = "SELECT 1 FROM Veterinarios WHERE LOWER(Nombre_v) = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $duplicado = true;
        $mensaje = 'usuario-existe-veterinario';
    }

    mysqli_stmt_close($stmt);
} else {
    // Manejo de error opcional
    $duplicado = false;
    $mensaje = 'error-consulta-veterinario';
}


// Comprobación en usuario.nombre_u (procedural)
$sql2 = "SELECT 1 FROM usuario WHERE LOWER(nombre_u) = ?";
$stmt2 = mysqli_prepare($conn, $sql2);

if ($stmt2) {
    mysqli_stmt_bind_param($stmt2, "s", $username);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_store_result($stmt2);

    if (mysqli_stmt_num_rows($stmt2) > 0) {
        $duplicado = true;
        $mensaje = 'usuario-existe-usuario';
    }

    mysqli_stmt_close($stmt2);
} else {
    // Manejo de error opcional
    $duplicado = false;
    $mensaje = 'error-consulta-usuario';
}


// Comprobación teléfono duplicado (procedural)
$sql3 = "SELECT 1 FROM Veterinarios WHERE Telf_v = ?";
$stmt3 = mysqli_prepare($conn, $sql3);

if ($stmt3) {
    mysqli_stmt_bind_param($stmt3, "s", $telefono);
    mysqli_stmt_execute($stmt3);
    mysqli_stmt_store_result($stmt3);

    if (mysqli_stmt_num_rows($stmt3) > 0) {
        $duplicado = true;
        $mensaje = 'telefono-duplicado';
    }

    mysqli_stmt_close($stmt3);
} else {
    // Manejo de error opcional
    $duplicado = false;
    $mensaje = 'error-consulta-telefono';
}


// Comprobación nombre exacto (opcional pero útil)
// Comprobación nombre de veterinario duplicado (procedural)
$sql4 = "SELECT 1 FROM Veterinarios WHERE LOWER(Nombre_v) = LOWER(?)";
$stmt4 = mysqli_prepare($conn, $sql4);

if ($stmt4) {
    mysqli_stmt_bind_param($stmt4, "s", $nombre);
    mysqli_stmt_execute($stmt4);
    mysqli_stmt_store_result($stmt4);

    if (mysqli_stmt_num_rows($stmt4) > 0) {
        $duplicado = true;
        $mensaje = 'nombre-veterinario-duplicado';
    }

    mysqli_stmt_close($stmt4);
} else {
    // Manejo de error opcional
    $duplicado = false;
    $mensaje = 'error-consulta-nombre-veterinario';
}


if ($duplicado) {
    header("Location: ../forms/form_veterinario.php?error=$mensaje");
    exit();
}

// 4. Insertar datos

// Hashear contraseña
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar en tabla Veterinarios
// Insertar en tabla Veterinarios
$sql_insert_vet = "INSERT INTO Veterinarios (Nombre_v, Telf_v, id_e, salario_v) VALUES (?, ?, ?, ?)";
$stmt_vet = mysqli_prepare($conn, $sql_insert_vet);

if ($stmt_vet) {
    mysqli_stmt_bind_param($stmt_vet, "sssd", $nombre, $telefono, $especialidad, $salario);
    if (!mysqli_stmt_execute($stmt_vet)) {
        header("Location: ../sesion/Registro.php?error=fallo-insert-veterinario");
        exit();
    }
    $id_veterinario = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt_vet);
} else {
    header("Location: ../sesion/Registro.php?error=fallo-prepare-veterinario");
    exit();
}

// Insertar en tabla usuario
$sql_insert_user = "INSERT INTO usuario (nombre_u, password_u, id_u) VALUES (?, ?, ?)";
$stmt_user = mysqli_prepare($conn, $sql_insert_user);

if ($stmt_user) {
    mysqli_stmt_bind_param($stmt_user, "ssi", $username, $hash, $id_veterinario);
    if (!mysqli_stmt_execute($stmt_user)) {
        header("Location: ../sesion/Registro.php?error=fallo-insert-usuario");
        exit();
    }
    mysqli_stmt_close($stmt_user);
} else {
    header("Location: ../sesion/Registro.php?error=fallo-prepare-usuario");
    exit();
}

// Registro exitoso
$_SESSION['usuario'] = $username;
header("Location: ../../index.php");
exit();
?>
