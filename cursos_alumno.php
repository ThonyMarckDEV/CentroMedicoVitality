<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si existe una sesión activa
if (!isset($_SESSION['username'])) {
    // Si no hay sesión activa, redirigir al formulario de inicio de sesión
    header('Location: login.php');
    exit();
}

// Obtener el nombre de usuario del alumno que inició sesión
$username = $_SESSION['username'];

// Consulta SQL para obtener los cursos matriculados por el alumno
$sql = "SELECT cursos.idCurso AS id_curso, cursos.nombre AS nombre_curso
        FROM matricula_alumnos
        INNER JOIN cursos ON matricula_alumnos.idCurso = cursos.idCurso
        INNER JOIN usuarios ON matricula_alumnos.idUsuario= usuarios.idUsuario
        WHERE usuarios.nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php 
include 'menu_alumno.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos del Alumno</title>
    <link rel="stylesheet" href="cursos_alumno_styles.css">
</head>
<body>
    <div class="container">
        <h2>Cursos Matriculados</h2>
        <div class="cursos-container">
            <!-- PHP para mostrar los cursos matriculados -->
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="curso-box">
                    <a href="clases_curso.php?curso_id=<?php echo $row['id_curso']; ?>">
                        <?php echo $row['nombre_curso']; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="cursos_alumno_script.js"></script>
</body>
</html>

<?php
// Cerrar la conexión y liberar los recursos
$stmt->close();
$conn->close();
?>
