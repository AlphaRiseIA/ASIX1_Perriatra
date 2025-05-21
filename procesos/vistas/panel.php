<?php
include '../conn/conectarse.php';
include '../conn/conexion.php';
session_start();
if (!isset($_SESSION['nombre_u'])) {
    header("Location: ../sesion/Login.php");
    exit();
}

$result = mysqli_query($conn, "
    SELECT id_ayuda, nombre, email, asunto, mensaje, fecha_envio, estado
    FROM form_ayuda
    ORDER BY fecha_envio DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Solicitudes de Ayuda</title>
    <link rel="stylesheet" href="../../css/styles.css"> 
    <script src="../../script/script.js"></script>
    <style>
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: bold;
            text-transform: capitalize;
            color: #fff;
            font-size: 0.9em;
        }
        .badge-pendiente {
            background-color: #e74c3c; /* rojo */
        }
        .badge-en-revision {
            background-color: #f39c12; /* naranja */
        }
        .badge-solucionada {
            background-color: #2ecc71; /* verde */
        }
    </style>
</head>

<a href="../../index.php" class="btn-volver">⟵ Volver al inicio</a>
<body>
    <h1>Solicitudes de Ayuda</h1>

    <table class="tabla-vet">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Asunto</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                $estado = $row['estado'];
                // Asignar clase según estado
                switch ($estado) {
                    case 'pendiente':
                        $claseEstado = 'badge badge-pendiente';
                        break;
                    case 'en revision':
                        $claseEstado = 'badge badge-en-revision';
                        break;
                    case 'solucionada':
                        $claseEstado = 'badge badge-solucionada';
                        break;
                    default:
                        $claseEstado = 'badge';
                }
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id_ayuda'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['asunto'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= nl2br(htmlspecialchars($row['mensaje'], ENT_QUOTES, 'UTF-8')) ?></td>
                <td><?= htmlspecialchars($row['fecha_envio'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><span class="<?= $claseEstado ?>"><?= htmlspecialchars($estado) ?></span></td>
                <td>
                    <a href="../updates/update_incidencias.php?id=<?= urlencode($row['id_ayuda']) ?>" class="viewT" name="viewT">Ver</a>
                </td>
            </tr>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
                <td colspan="8">No hay solicitudes de ayuda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
