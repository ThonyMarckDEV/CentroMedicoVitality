<?php
require_once 'config.php';


// Obtener el nombre de usuario del docente que inició sesión
$docente_username = $_SESSION['username'];

// Consulta SQL para obtener la especialidad del docente
$sql_especialidad = "SELECT especialidad FROM usuarios WHERE nombre = ?";
$stmt_especialidad = $conn->prepare($sql_especialidad);
$stmt_especialidad->bind_param("s", $docente_username);
$stmt_especialidad->execute();
$result_especialidad = $stmt_especialidad->get_result();

// Obtener el nombre de la especialidad del docente
if ($result_especialidad->num_rows === 1) {
    $row_especialidad = $result_especialidad->fetch_assoc();
    $especialidad_docente = $row_especialidad['especialidad'];

    // Consulta SQL para obtener todas las tareas de los alumnos en la especialidad del docente
    $sql_tareas_alumnos = "SELECT DISTINCT t.idTarea,t.idUsuario, a.nombre AS nombre_alumno,t.fecha_subida,t.nota
                            FROM tareas_alumnos t
                            JOIN matricula_alumnos m ON t.idUsuario = m.idUsuario
                            JOIN cursos c ON m.idCurso = c.idCurso
                            JOIN usuarios a ON t.idUsuario = a.idUsuario
                            JOIN especialidades e ON e.nombre = ? ";
    $stmt_tareas_alumnos = $conn->prepare($sql_tareas_alumnos);
    $stmt_tareas_alumnos->bind_param("s", $especialidad_docente);
    $stmt_tareas_alumnos->execute();
    $result_tareas_alumnos = $stmt_tareas_alumnos->get_result();
} else {
    // Manejar el caso en el que no se encuentre la especialidad del docente
    echo 'Error: No se pudo encontrar la especialidad del docente.';
    exit();
}
?>

<?php include 'menu_docente.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas de Alumnos por Especialidad</title>
    <link rel="stylesheet" href="gestionar_tareas_styles.css">
</head>
<body>
    <h1>Tareas de Alumnos por Especialidad: <?php echo $especialidad_docente; ?></h1>

   <div class="container">
    <?php
    if ($result_tareas_alumnos->num_rows > 0) {
        while ($row = $result_tareas_alumnos->fetch_assoc()) {
            echo "<div class='task'>";
            echo "<h3>Tarea</h3>";
            echo "<p>Alumno: " . $row['nombre_alumno'] . "</p>";
            echo "<p>Fecha Subida: " . $row['fecha_subida'] . "</p>";
            echo "<p>Nota: " . $row['nota'] . "</p>";
            // Agregar enlace a la página de ver tarea y calificar
            echo "<a href='ver_tareas.php?id=" . $row['idTarea'] . "'>Ver y Calificar</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No se encontraron tareas para la especialidad del docente.</p>";
    }
    ?>
</div>
</body>
</html>
<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
