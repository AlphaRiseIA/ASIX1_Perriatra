<?php
include '../conn/conexion.php';
include '../conn/conectarse.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validar CHIP
    if (empty($_POST['chip']) || !preg_match('/^[a-zA-Z0-9]{5,20}$/', $_POST['chip'])) {
        $errores[] = "El chip debe ser alfanumérico, entre 5 y 20 caracteres.";
    } else {
        $chip = trim($_POST['chip']);
        $stmt_chip = mysqli_prepare($conn, "SELECT 1 FROM mascota WHERE chip_m = ?");
        mysqli_stmt_bind_param($stmt_chip, "s", $chip);
        mysqli_stmt_execute($stmt_chip);
        mysqli_stmt_store_result($stmt_chip);
        if (mysqli_stmt_num_rows($stmt_chip) > 0) {
            $errores[] = "El número de chip ya está registrado.";
        }
        mysqli_stmt_close($stmt_chip);
    }

    // Validar NOMBRE
    if (empty($_POST['nombre'])) {
        $errores[] = "El nombre de la mascota es obligatorio.";
    } else {
        $nombre = trim($_POST['nombre']);
    }

    // Validar GÉNERO
    if (!isset($_POST['genero']) || !in_array($_POST['genero'], ['M', 'F'])) {
        $errores[] = "El género debe ser M o F.";
    } else {
        $genero = $_POST['genero'];
    }

    // Validar RAZA (ID)
    if (!isset($_POST['raza']) || !preg_match('/^\d+$/', $_POST['raza'])) {
        $errores[] = "Selecciona una raza válida.";
    } else {
        $raza = intval($_POST['raza']);
    }

    // Validar FECHA DE NACIMIENTO
    if (!isset($_POST['fecha_nacimiento']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha_nacimiento'])) {
        $errores[] = "La fecha de nacimiento no es válida.";
    } else {
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
    }

    // Validar PROPIETARIO (DNI)
    if (!isset($_POST['propietario']) || !preg_match('/^\d{7,10}$/', $_POST['propietario'])) {
        $errores[] = "Selecciona un propietario válido.";
    } else {
        $propietario = $_POST['propietario'];
        $stmt_prop = mysqli_prepare($conn, "SELECT 1 FROM propietario WHERE DNI_p = ?");
        mysqli_stmt_bind_param($stmt_prop, "s", $propietario);
        mysqli_stmt_execute($stmt_prop);
        mysqli_stmt_store_result($stmt_prop);
        if (mysqli_stmt_num_rows($stmt_prop) === 0) {
            $errores[] = "El propietario no existe.";
        }
        mysqli_stmt_close($stmt_prop);
    }

    // Validar VETERINARIO (opcional)
    $vet = null;
    if (!empty($_POST['id_v'])) {
        if (!preg_match('/^\d+$/', $_POST['id_v'])) {
            $errores[] = "Veterinario no válido.";
        } else {
            $vet_input = intval($_POST['id_v']);
            $stmt_vet = mysqli_prepare($conn, "SELECT 1 FROM veterinarios WHERE id_v = ?");
            mysqli_stmt_bind_param($stmt_vet, "i", $vet_input);
            mysqli_stmt_execute($stmt_vet);
            mysqli_stmt_store_result($stmt_vet);
            if (mysqli_stmt_num_rows($stmt_vet) === 0) {
                $errores[] = "El veterinario no existe.";
            } else {
                $vet = $vet_input;
            }
            mysqli_stmt_close($stmt_vet);
        }
    }

    // Si hay errores, redirige
    if (!empty($errores)) {
        $_SESSION['errores_mascota'] = $errores;
        header("Location: ../forms/form_mascotas.php?error=1");
        exit;
    }

    // Preparar e insertar
    $sql = "INSERT INTO mascota (chip_m, Nombre_m, Genero_m, id_r, Fecha_nacimiento_m, DNI_p, id_v)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sssissi",
        $chip,
        $nombre,
        $genero,
        $raza,
        $fecha_nacimiento,
        $propietario,
        $vet
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../forms/form_mascotas.php?registro=exito");
    } else {
        if (mysqli_errno($conn) == 1062) {
            header("Location: ../forms/form_mascotas.php?registro=duplicado");
        } else {
            $_SESSION['errores_mascota'] = ["Error en la base de datos: " . mysqli_error($conn)];
            header("Location: ../forms/form_mascotas.php?registro=error");
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: ../forms/form_mascota.php");
    exit;
}
?>
