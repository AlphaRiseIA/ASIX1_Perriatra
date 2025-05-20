<?php
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../procesos/sesion/Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manual del Panel de Administración - Veterinaria</title>
  <link rel="stylesheet" href="../css/manual.css">

  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header>
    <div class="container">
      <div class="logo">
        <i class="fas fa-paw"></i>
      </div>
      <h1>Manual del Panel de Administración de la Veterinaria</h1>
    </div>
  </header>
  <a href="../index.php" class="btn-volver">⟵ Volver al inicio</a>
  <nav>
    <div class="container">
      <ul class="nav-list">
        <li class="nav-item"><a href="#introduccion" class="nav-link active">Introducción</a></li>
        <li class="nav-item"><a href="#entidades" class="nav-link">Entidades</a></li>
        <li class="nav-item"><a href="#faq" class="nav-link">FAQ</a></li>
      </ul>
    </div>
  </nav>

  <main>
    <div class="container">
      <section id="introduccion">
        <h2>Introducción al Panel de Administración</h2>
        <div class="card">
          <div class="card-body">
            <p>Bienvenido al manual completo del Panel de Administración de la Veterinaria. Este panel ha sido diseñado para facilitar la gestión integral de la clínica veterinaria, permitiéndote administrar de manera eficiente toda la información relacionada con:</p>
            
          <div class="grid">
              <div class="feature-card">
                <div class="feature-icon">
                  <span class="fa-stack fa-2x">
                    <i class="fas fa-otter"></i> 
                </div>
                <h3 class="feature-title">Especies</h3>
                <p>Gestiona los tipos de animales atendidos en la clínica.</p>
              </div>
              <div class="feature-card">
              <div class="feature-icon">
                  <i class="fas fa-paw"></i>
                </div>
                <h3 class="feature-title">Razas</h3>
                <p>Gestiona los tipos de razas específicas.</p>
              </div>

              <div class="feature-card">
                <div class="feature-icon">
                  <i class="fas fa-stethoscope"></i>
                </div>
                <h3 class="feature-title">Especialidades</h3>
                <p>Gestiona las especialidades que tiene tu veterinaria.</p>
              </div>
              
              <div class="feature-card">
                <div class="feature-icon">
                  <i class="fas fa-user-md"></i>
                </div>
                <h3 class="feature-title">Veterinarios</h3>
                <p>Administra la información de los profesionales y sus especialidades.</p>
              </div>
              
              <div class="feature-card">
                <div class="feature-icon">
                  <i class="fas fa-user"></i>
                </div>
                <h3 class="feature-title">Propietarios</h3>
                <p>Gestiona los datos de contacto de los dueños de las mascotas.</p>
              </div>
              
              <div class="feature-card">
                <div class="feature-icon">
                  <i class="fas fa-dog"></i>
                </div>
                <h3 class="feature-title">Mascotas</h3>
                <p>Lleva un registro detallado de cada paciente animal y su historial.</p>
              </div>
            </div>
            
            <div class="alert alert-info">
              <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
              <div>
                Este panel requiere iniciar sesión con credenciales válidas. Si no estás autenticado, serás redirigido automáticamente a la página de login.
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="entidades">
        <h2>Entidades del Sistema y sus Relaciones</h2>
        
        <p>El sistema está compuesto por diversas entidades interrelacionadas. Es fundamental entender cómo se conectan entre sí para realizar un registro correcto de la información.</p><br>
        
        <div class="card">
          <div class="card-header">
            <i class="fas fa-sitemap"></i> Jerarquía de Entidades
          </div>
          <div class="card-body">
            
            <div class="entity-card">
              <div class="entity-header">
                <span>Especie</span>
                <span class="badge badge-info">Independiente</span>
              </div>
              <div class="entity-body">
                <p><strong>Descripción:</strong> Categoría básica de animal (Ej: Canino, Felino, Ave, etc.)</p>
                <p><strong>Campos principales:</strong> ID y Nombre de la especie/p>
                <p><strong>Dependencias:</strong> Ninguna - <span class="optional-tag">Es una entidad base</span></p>
                <p><strong>Entidades dependientes:</strong> Raza</p>
              </div>
            </div>
            
            <div class="entity-card">
              <div class="entity-header">
                <span>Raza</span>
                <span class="badge badge-warning">Dependiente</span>
              </div>
              <div class="entity-body">
                <p><strong>Descripción:</strong> Variedad específica dentro de una especie (Ej: Labrador, Siamés, etc.)</p>
                <p><strong>Campos principales:</strong> ID, Nombre de la raza, ID_Especie</p>
                <p><strong>Dependencias:</strong> <span class="required-tag">Requiere una Especie registrada</span></p>
                <p><strong>Entidades dependientes:</strong> Mascota</p>
              </div>
            </div>
            
            <div class="entity-card">
              <div class="entity-header">
                <span>Especialidad</span>
                <span class="badge badge-info">Independiente</span>
              </div>
              <div class="entity-body">
                <p><strong>Descripción:</strong> Área de especialización veterinaria (Ej: Cardiología, Dermatología, etc.)</p>
                <p><strong>Campos principales:</strong> ID, Nombre de la especialidad</p>
                <p><strong>Dependencias:</strong> Ninguna - <span class="optional-tag">Es una entidad base</span></p>
                <p><strong>Entidades dependientes:</strong> Veterinario</p>
              </div>
            </div>
            
            <div class="entity-card">
              <div class="entity-header">
                <span>Veterinario</span>
                <span class="badge badge-warning">Dependiente</span>
              </div>
              <div class="entity-body">
                <p><strong>AVISO!</strong>Registrar un Veterinario es una tarea exclusiva del administrador</p>
                <p><strong>Descripción:</strong> Profesional que brinda atención médica a las mascotas</p>
                <p><strong>Campos principales:</strong> ID, Nombre, Telf, ID_Especialidad, Fecha_contrato, Salario</p>
                <p><strong>Dependencias:</strong> <span class="required-tag">Requiere una Especialidad registrada</span></p>
                <p><strong>Entidades dependientes:</strong> Ninguna directamente en este esquema</p>
              </div>
            </div>
            
            <div class="entity-card">
              <div class="entity-header">
                <span>Propietario</span>
                <span class="badge badge-info">Independiente</span>
              </div>
              <div class="entity-body">
                <p><strong>Descripción:</strong> Dueño o persona responsable de la mascota</p>
                <p><strong>Campos principales:</strong> DNI, Nombre, Dirección, Teléfono, Email</p>
                <p><strong>Dependencias:</strong> Ninguna - <span class="optional-tag">Es una entidad base</span></p>
                <p><strong>Entidades dependientes:</strong> Mascota</p>
              </div>
            </div>
            
            <div class="entity-card">
              <div class="entity-header">
                <span>Mascota</span>
                <span class="badge badge-danger">Triple Dependencia</span>
              </div>
              <div class="entity-body">
                <p><strong>Descripción:</strong> Animal paciente registrado en la clínica</p>
                <p><strong>Campos principales:</strong> Chip, Nombre, ID_Raza, DNI_Propietario, Fecha de nacimiento, Sexo, Veterinario Asignado</p>
                <p><strong>Dependencias:</strong> <span class="required-tag">Requiere una Raza y un Propietario registrados</span></p>
              </div>
            </div>
            
            <div class="alert alert-warning">
              <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
              <div>
                <strong>Importante:</strong> Respetar estas dependencias es crucial para mantener la integridad de los datos. El sistema podría impedir registros que no cumplan con estas relaciones.
              </div>
            </div>
          </div>
        </div>
      </section>

</section>
  <section id="faq" style="width:100%; background: var(--white); padding: 2rem 1rem;">
  <div class="container-faq">
    <h2>❓ Preguntas Frecuentes (FAQ)</h2>
    <div class="card"><div class="card-body">
      <div class="faq-item">
        <button class="faq-question">¿Cómo corrijo un error? <span class="icon">+</span></button>
        <div class="faq-answer"><p>Usa "Editar" en la tabla.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">¿Puedo eliminar sin afectar otros datos? <span class="icon">+</span></button>
        <div class="faq-answer"><p>Verifica dependencias antes.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">¿Cómo registro una craza? <span class="icon">+</span></button>
        <div class="faq-answer"><p>Ve a "Razas" y selecciona la especie, luego escribe el nombre de la raza.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">¿Dónde configuro usuarios? <span class="icon">+</span></button>
        <div class="faq-answer"><p>En "Veterinarios" dentro del panel, pero esta funcion es exclusiva del administrador.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-question">¿Editar datos? <span class="icon">+</span></button>
        <div class="faq-answer"><p>Usa editar dentro del registro de la tabla de la entidad que quieras cambiar.</p></div>
      </div>
    </div></div>
  </div>
  <div class="container-faq"><a href="./FaQ.pdf">Ver pdf de ayuda</a> </div>
</section>
<footer class="footer-content"> 
  <p style="margin-top: 3rem;">© 2025 Panel Veterinaria - Todos los derechos reservados.</p>
</footer>

</body>
<script>
  document.querySelectorAll(".faq-question").forEach(button => {
    button.addEventListener("click", () => {
      const answer = button.nextElementSibling;
      const icon = button.querySelector(".icon");
      answer.style.display = (answer.style.display === "block") ? "none" : "block";
      icon.textContent = (answer.style.display === "block") ? "−" : "+";
    });
  });
</script>

</html>

