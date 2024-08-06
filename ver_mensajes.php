<?php
// Incluir el archivo de configuración
require_once 'config.php';

// Obtener el nombre de usuario del alumno que inició sesión
$username = $_SESSION['username'];

// Consulta SQL para obtener la especialidad del alumno por su nombre
$sql_especialidad = "SELECT especialidad FROM usuarios WHERE nombre = ?";
$stmt_especialidad = $conn->prepare($sql_especialidad);
$stmt_especialidad->bind_param("s", $username);
$stmt_especialidad->execute();
$result_especialidad = $stmt_especialidad->get_result();

if ($result_especialidad->num_rows === 1) {
    $alumno = $result_especialidad->fetch_assoc();
    $especialidad_alumno = $alumno['especialidad'];
} else {
    // Manejar el caso en el que no se encuentre el alumno
    echo 'Error: No se pudo encontrar al alumno.';
    exit();
}

// Consulta SQL para obtener los mensajes filtrados por la especialidad del alumno
$sql_mensajes = "SELECT  u.nombre AS Docente,m.texto AS Mensaje  FROM mensajes m JOIN usuarios u ON m.idDocente = u.idUsuario WHERE m.especialidad = ?";
$stmt_mensajes = $conn->prepare($sql_mensajes);
$stmt_mensajes->bind_param("s", $especialidad_alumno);
$stmt_mensajes->execute();
$result_mensajes = $stmt_mensajes->get_result();

// Cerrar conexión al final del script
$conn->close();
?>

<?php 
include 'menu_alumno.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Mensajes</title>
    <link rel="stylesheet" href="ver_mensajes_styles.css"> <!-- Reemplaza "styles.css" con la ruta de tu archivo de estilos -->
</head>
<body>
    <div class="mensajes-container">
        <?php if ($result_mensajes->num_rows > 0): ?>
            <?php while ($mensaje = $result_mensajes->fetch_assoc()): ?>
                <div class="mensaje">
                    <p>DOCENTE: <?php echo $mensaje['Docente']; ?></p>
                    <p>MENSAJE: <?php echo $mensaje['Mensaje']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay mensajes para mostrar.</p>
        <?php endif; ?>
    </div>
</body>
</html>
