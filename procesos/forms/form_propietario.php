<script src="../../script/script.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
  <div class="container">

            <h2>Insertar Nuevo Propietario</h2>          
        <form id="formPropietario" method="post" action="../insert/insert_propietario.php">
          <label for="dni">DNI:</label><br>
          <input type="text" id="dni" name="dni" placeholder="Ingresa DNI sin letra" onblur="validarDNI()">
          <span id="errorDNI" class="error"></span>

          <label for="nombre_prop">Nombre:</label>
          <input type="text" id="nombre_prop" name="nombre" placeholder="Ingresa el nombre" onblur="validarNombrePropietario()">
          <span id="errorNombreProp" class="error"></span>

          <label for="direccion">Dirección:</label>
          <input type="text" id="direccion" name="direccion" placeholder="Ingresa la direccion" onblur="validarDireccion()">
          <span id="errorDir" class="error"></span>

          <label for="telefono">Teléfono:</label><br>
          <input type="text" id="telefono" name="telf" placeholder="Ingresa el nº de telefono" onblur="validaTelefono()">
          <span id="errorTelefono" class="error"></span>

          <label for="email">Email:</label><br>
          <input type="email" id="email" name="email" placeholder="Ingresa un email" onblur="validarEmail()">
          <span id="errorEmail" class="error"></span>

          <input type="submit" value="Registrar">
        </form>
  </div>
</body>
</html>