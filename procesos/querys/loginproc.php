<?php
session_start();  // Ya está iniciada al principio del script
include "../conn/conectarse.php";
include "../conn/conexion.php";

// Verifico POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['usuario']) && isset($_POST['password'])) {
        $errorUsuario = "";
        $errorPassword = "";     // Almaceno en variables enviadas en el form
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);  // Prevención de inyección SQL
        $password = $_POST['password'];
        $error = false;  // Variable para controlar si hay errores

        if (strlen($usuario) < 3) {
            $errorUsuario = "El nombre de usuario debe tener al menos 3 caracteres.";
            $error = true;
        }
        
        if (strlen($password) < 3) {
            $errorPassword = "La contraseña debe tener al menos 3 caracteres.";
            $error = true;
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errorPassword = "La contraseña debe tener como mínimo una mayúscula.";
            $error = true;
        }
        
        if ($error) {
            // Guardo los errores en la sesión para mostrarlos en la página de login
            $_SESSION['errorUsuario'] = $errorUsuario;
            $_SESSION['errorPassword'] = $errorPassword;
            header("Location: ../sesion/login.php?1");  // Faltaba la palabra Location
            exit();
        } else {
            $sql = "SELECT id_u, nombre_u, password_u FROM usuario WHERE nombre_u = ?";
            $stmt = mysqli_prepare($conn, $sql);
            
            if (!$stmt) {
                die("Error al preparar la consulta: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt, "s", $usuario);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            
            if (!$resultado) {
                die("Error al ejecutar la consulta: " . mysqli_error($conn));
            }
            
            if (mysqli_num_rows($resultado) > 0) {
                $datos_u = mysqli_fetch_assoc($resultado);
                $id_u = $datos_u['id_u'];
                $nombre_u = $datos_u['nombre_u'];
                $password_u = $datos_u['password_u'];
                
                if (password_verify($password, $password_u)) {
                    // No necesitamos iniciar sesión de nuevo, ya está iniciada
                    $_SESSION['nombre_u'] = $nombre_u; 
                    $_SESSION['id_u'] = $id_u;           
                    header("Location: ../../index.php?mensaje=" . urlencode("Inicio de sesión exitoso. Bienvenido $nombre_u"));
                    exit();
                } else {
                    $_SESSION['loginError'] = "Contraseña incorrecta";
                    header("Location: ../sesion/login.php");
                    exit();
                }
            } else {
                $_SESSION['loginError'] = "El usuario no existe";
                header("Location: ../sesion/login.php");
                exit();
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    } else {
        $_SESSION['loginError'] = "Por favor, complete todos los campos";
        header("Location: ../sesion/login.php");
        exit();
    }
}
?>