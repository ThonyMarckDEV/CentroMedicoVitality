<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    // Si el usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit(); // Asegurar que el script se detenga después de la redirección
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docente - Centro de Salud</title>
    <link rel="stylesheet" href="docente_styles.css">
</head>
<body>
    <div class="status-circle"></div>
    <div class="sidebar">
        <h2>Menú Docente</h2>
        <ul>
            <li><a href="perfil_usuario.php">Perfil</a></li>
            <li><a href="gestionar_cursos.php">Gestionar Cursos</a></li>
            <li><a href="gestionar_tareas.php">Gestionar Tareas</a></li>
            <li><a href="enviar_mensajes.php">Enviar Mensajes</a></li>
            <li><a href="logout.php" class="logout-btn">Cerrar Sesión</a></li>
        </ul>
    </div>
   <div class="content">
        <h1>Bienvenido, Docente <?php echo $username; ?></h1>
        <p>Seleccione una opción del menú para comenzar.</p>
        <div class="message">
            <h2>Centro de Especialización Vitality</h2>
            <p>En Vitality, nos dedicamos a ofrecer una atención médica integral y especializada para cada uno de nuestros pacientes. Nuestro equipo de profesionales altamente cualificados y nuestras instalaciones de última generación nos permiten proporcionar tratamientos avanzados y personalizados para una amplia gama de necesidades de salud.</p>
            <p>Nos enfocamos en promover la salud y el bienestar a través de un enfoque holístico, que incluye no solo tratamientos médicos, sino también programas de prevención, rehabilitación y educación para la salud. En Vitality, creemos que cada paciente es único, y nos esforzamos por crear planes de cuidado que reflejen esta individualidad.</p>
            <p>Nuestras áreas de especialización incluyen cardiología, neurología, ortopedia, oncología y muchos más. Cada departamento está dirigido por expertos en su campo, que trabajan juntos para asegurar que nuestros pacientes reciban la mejor atención posible.</p>
            <p>Además, en Vitality estamos comprometidos con la innovación continua y la investigación médica, lo que nos permite estar a la vanguardia de los avances en medicina y ofrecer a nuestros pacientes los tratamientos más efectivos y modernos disponibles.</p>
            <p>Estamos orgullosos de nuestra reputación como líderes en atención médica especializada y nos esforzamos cada día por mantener y mejorar nuestros altos estándares de calidad y servicio. En Vitality, tu salud y bienestar son nuestra máxima prioridad.</p>
        </div>
        <img src="vitality_centro.jpg" alt="Centro de Especialización Vitality">
    </div>
</body>
</html>
