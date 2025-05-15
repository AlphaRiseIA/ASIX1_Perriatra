<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="icon" href="../img/2.svg">
    <script src="../../script/script.js"></script>
    <title>Registro</title>
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h2>¡Regístrate para disfrutar de nuestro servicio!</h2>
    <div class="container">
        <h1>Registro de Veterinario</h1>

        <form id="registerForm" action="../insert/insert_veterinarios.php" method="POST" onsubmit="return validarFormulario()">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingresa un usuario" required 
                   onblur="validaUsuario()">
            <span id="errorUsuario" class="error"></span>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required 
                   onblur="validaNombre()">
            <span id="errorNombre" class="error"></span>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ingresa tu número de teléfono" required onblur="validaTelefono()">
            <span id="errorTelefono" class="error"></span>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Ingresa una contraseña"
                   onblur="validaPassword()">
            <span id="errorPassword" class="error"></span>

            <label for="confirmPassword">Confirmar Contraseña:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirma tu contraseña"
                   onblur="validaConfirmPassword()">
            <span id="errorConfirmPassword" class="error"></span>

        <label for="especialidad">Especialidad:</label>
        <select id="especialidad" name="especialidad">
            <option value="" disabled selected>-- Selecciona una especialidad --</option>
            <?php
            // Incluir archivos de conexión
            include "../conn/conectarse.php";
            include "../conn/conexion.php";

            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Consulta para obtener especialidades
            $sql = "SELECT id_e, Nombre_e FROM especialidades";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) {
                    echo "<option value='" . $fila['id_e'] . "'>" . htmlspecialchars($fila['Nombre_e']) . "</option>";
                }
            } else {
                echo "<option value=''>--No hay especialidades registradas--</option>";
            }

            $conn->close(); // Cierra la conexión
            ?>
        </select>

        <label for="salario">Salario</label>
        <input type="text" id="salario" name="salario" placeholder="introduce el salario" required onblur="validaSalario()">
        <span id="errorSalario" class="error"></span>

            <input type="submit" value="Registrar">
            <br><br>
        </form>
    </div>
</body>
</html>
