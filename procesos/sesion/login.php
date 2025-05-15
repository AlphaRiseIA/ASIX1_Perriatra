<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <script src="../../script/script.js"></script>
</head>
<body>
<div class="container">
    <form action="../querys/loginproc.php" method="POST" id="form"> <!--Envio de POST de datos sensibles -->
    <h2>Login de Veterinaria</h2>
        <label for=usuario>Introduce tu nombre:</label>
        <input type="text" id="usuario" name="usuario" onblur="validaUsuario()"> <!--Validacion del nombre del usuario ya registrado-->
        <span id="errorUsuario" class="error"></span>
        <label for="password">Introduzca su contraseña:</label>
        <input type="password" id="password" name="password" onblur="validaPassword()"> <!--Validacion de contraeña del usuario registrado-->
        <span id="errorPassword" class="error"></span>
        <input type="submit" value="Iniciar sesión"> 
</form>
    
</html> 