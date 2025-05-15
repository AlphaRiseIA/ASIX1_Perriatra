function validaUsuario() {
            let usuario = document.getElementById("usuario").value.trim();
            let errorUsuario = document.getElementById("errorUsuario");

            if (usuario.length < 3) {
                errorUsuario.textContent = "El usuario debe tener al menos 3 caracteres.";
                errorUsuario.style.color = "red";
                return false;
            } else {
                errorUsuario.textContent = "";
                return true;
            }
        }

function validaPassword() {
    let password = document.getElementById("password").value.trim();
    let errorPassword = document.getElementById("errorPassword");

    if (password.length < 8) {
        errorPassword.textContent = "La contraseña debe tener al menos 8 caracteres.";
        errorPassword.style.color = "red";
        return false;
    }
    if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
        errorPassword.textContent = "La contraseña debe tener al menos una minúscula y una mayúscula.";
        errorPassword.style.color = "red";
        return false;
    }
    errorPassword.textContent = "";
    return true;
}
function validaNombre() {
    const nombreInput = document.getElementById("nombre");
    const errorSpan = document.getElementById("errorNombre");
    
    const nombre = nombreInput.value.trim();
    errorSpan.textContent = "";
    
    if (nombre === "") {
        errorSpan.textContent = "El nombre es obligatorio";
        return false;
    }
    
    if (nombre.length < 2) {
        errorSpan.textContent = "El nombre debe tener al menos 2 caracteres";
        return false;
    }
    
    // Validar que solo contenga letras y espacios
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) {
        errorSpan.textContent = "El nombre solo debe contener letras";
        return false;
    }
    
    return true;
}
function validaConfirmPassword() {
    let password = document.getElementById("password").value.trim();
    let confirmPassword = document.getElementById("confirmPassword").value.trim();
    let errorConfirmPassword = document.getElementById("errorConfirmPassword");

    if (password !== confirmPassword) {
        errorConfirmPassword.textContent = "Las contraseñas no coinciden.";
        errorConfirmPassword.style.color = "red";
        return false;
    }
    errorConfirmPassword.textContent = "";
    return true;
}
function validaSalario() {
    // Obtenemos el campo de entrada y el span para mostrar errores
    const salarioInput = document.getElementById("salario");
    const errorSpan = document.getElementById("errorSalario");
    
    // Obtenemos el valor
    const salario = salarioInput.value;
    
    // Limpiamos cualquier mensaje de error anterior
    errorSpan.textContent = "";
    
    // Validación básica
    if (salario === "") {
        errorSpan.textContent = "El campo salario no puede estar vacío";
        return false;
    }
    
    // Comprobamos que sea un número positivo
    if (isNaN(salario) || parseFloat(salario) <= 0) {
        errorSpan.textContent = "Introduce un número positivo";
        return false;
    }
    
    return true;
}
function validaTelefono() {
    let telefono = document.getElementById("telefono").value.trim();
    let errorTelefono = document.getElementById("errorTelefono");
    // Verifica que el campo no esté vacío
    if (telefono === "") {
        errorTelefono.textContent = "El campo teléfono es obligatorio.";
        errorTelefono.style.color = "red";
        return false;
    }
    // Verifica que solo contenga números
    if (isNaN(telefono)) {
        errorTelefono.textContent = "El teléfono solo debe contener números.";
        errorTelefono.style.color = "red";
        return false;
    }
    // Verifica que tenga exactamente 9 dígitos
    if (telefono.length !== 9) {
        errorTelefono.textContent = "El teléfono debe tener exactamente 9 dígitos.";
        errorTelefono.style.color = "red";
        return false;
    }
    // Si todo está bien, limpiamos el error
    errorTelefono.textContent = "";
    return true;
}
function validarFormulario() {
    let usuarioValido = validaUsuario();
    let emailValido = validaEmail();
    let passwordValido = validaPassword();
    let confirmPasswordValido = validaConfirmPassword();
    let telefonoValido = validaTelefono(); // ← nueva validación

    if (!usuarioValido || !emailValido || !passwordValido || !confirmPasswordValido || !telefonoValido) {
        return false;
    }
    return true;
}
// Muestra un mensaje de error en el elemento con el ID indicado
function mostrarError(idElemento, mensaje) {
  const spanError = document.getElementById(idElemento);
  if (spanError) {
    spanError.textContent = mensaje;
  }
}

// Limpia el mensaje de error del elemento con el ID indicado
function limpiarError(idElemento) {
  const spanError = document.getElementById(idElemento);
  if (spanError) {
    spanError.textContent = '';
  }
}
function validarDNI() {
  const dni = document.getElementById("dni").value.trim();
  if (!dni || isNaN(dni) || dni.length < 7 || dni.length > 10) {
    mostrarError("errorDNI", "El DNI debe ser un número válido.");
    errorTelefono.style.color = "red";
    return false;
  }
  limpiarError("errorDNI");
  return true;
}

function validarNombrePropietario() {
  const nombre = document.getElementById("nombre_prop").value.trim();
  if (!nombre || nombre.length > 55) {
    mostrarError("errorNombreProp", "Nombre obligatorio (máx. 55 caracteres).");
    return false;
  }
  limpiarError("errorNombreProp");
  return true;
}
function validarEmail() {
        const email = document.getElementById('email');
        const valor = email.value.trim();
        const regex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

        if (!regex.test(valor)) {
            mostrarError('errorEmail', "Introduce un correo electrónico válido.");
            return false;
        }
        limpiarError('errorEmail');
        return true;
}
function validarDireccion() {
  const direccion = document.getElementById("direccion").value.trim();

  if (direccion.length < 5) {
    mostrarError("errorDir", "La dirección debe tener al menos 5 caracteres.");
    return false;
  }

  limpiarError("errorDir");
  return true;
}


// Validar Chip
function validarChip() {
  const chip = document.getElementById("chip").value.trim();
  if (!chip || isNaN(chip) || !Number.isInteger(Number(chip))) {
    mostrarError("errorChip", "El chip debe ser un número entero.");
    return false;
  }
  limpiarError("errorChip");
  return true;
}

function validarGenero() {
  const genero = document.getElementById("genero").value;
  if (!["M", "F"].includes(genero)) {
    mostrarError("errorGenero", "Debes seleccionar un género válido.");
    return false;
  }
  limpiarError("errorGenero");
  return true;
}

function validarEspecie() {
  const especie = document.getElementById("especie").value.trim();
  if (!especie) {
    mostrarError("errorEspecie", "La especie es obligatoria.");
    return false;
  }
  limpiarError("errorEspecie");
  return true;
}

function validar_raza() {
  const raza = document.getElementById("raza").value.trim();
  if (!raza) {
    mostrarError("error_raza", "La raza es obligatoria.");
    return false;
  }
  limpiarError("error_raza");
  return true;
}

function validarPropietario() {
  const propietario = document.getElementById("propietario").value;
  if (!propietario) {
    mostrarError("errorPropietario", "Selecciona un propietario.");
    return false;
  }
  limpiarError("errorPropietario");
  return true;
}

function validarVeterinario() {
  const vet = document.getElementById("vet").value;
  if (!vet) {
    mostrarError("errorVet", "Selecciona un veterinario.");
    return false;
  }
  limpiarError("errorVet");
  return true;
}
function validarFechaNacimiento() {
    const input = document.getElementById('fecha_nacimiento');
    const error = document.getElementById('errorDate');
    const valorFecha = new Date(input.value);
    const hoy = new Date();

    if (input.value === '') {
      error.textContent = "La fecha de nacimiento es obligatoria.";
      input.style.borderColor = "red";
      return false;
    }

    if (isNaN(valorFecha.getTime())) {
      error.textContent = "Formato de fecha no válido.";
      input.style.borderColor = "red";
      return false;
    }

    if (valorFecha > hoy) {
      error.textContent = "La fecha no puede ser futura.";
      input.style.borderColor = "red";
      return false;
    }

    error.textContent = "";
    input.style.borderColor = "green";
    return true;
  }

    function validaEspecialidad() {
        const especie = document.getElementById("id_esp").value;
        const errorEspecie = document.getElementById("error");

        if (especie === "") {
            errorEspecie.textContent = "Debes seleccionar una especie.";
            return false;
        } else {
            errorEspecie.textContent = "";
            return true;
        }
    }

    function validarFormulario() {
        const nombreValido = validaNombre();
        const especieValida = validaEspecialidad();
        return nombreValido && especieValida;
    }
  
function cargarRazasPorEspecie(idEspecie) {
    if (!idEspecie) return;

    fetch(`../ajax/obtener_razas.php?id_especie=${idEspecie}`)
        .then(res => res.json())
        .then(data => {
            const razaSelect = document.getElementById('raza');
            razaSelect.innerHTML = '<option value="" disabled selected>-- Selecciona una raza --</option>';

            if (data.length === 0) {
                razaSelect.innerHTML += '<option disabled>No hay razas disponibles</option>';
                return;
            }

            data.forEach(raza => {
                const option = document.createElement('option');
                option.value = raza.id_r;
                option.textContent = raza.nombre_r;
                razaSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar razas:', error);
        });
}

