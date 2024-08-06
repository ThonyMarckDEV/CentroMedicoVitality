<?php
session_start(); // Asegúrate de que las sesiones están habilitadas
include 'menu_docente.php';
require_once 'config.php'; // Archivo de conexión a la base de datos

// Verificar si el usuario está logeado
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Obtener el nombre del usuario logeado
$username = $_SESSION['username'];

// Consulta para obtener la especialidad del docente logeado
$especialidad_sql = "SELECT especialidad FROM usuarios WHERE nombre = ?";
$stmt = $conn->prepare($especialidad_sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $docente_especialidad = $row['especialidad'];
} else {
    echo "No se encontró la especialidad para el usuario logeado.";
    exit;
}

// Consulta para obtener los cursos según la especialidad del docente
$sql = "SELECT DISTINCT C.idCurso, C.nombre 
                    FROM
                    cursos C
                    JOIN
                    especialidades E ON C.idEspecialidad = E.idEspecialidad
                    JOIN
                     usuarios U ON E.nombre = U.especialidad
                    WHERE
                     U.especialidad = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $docente_especialidad);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Cursos</title>
    <link rel="stylesheet" href="gestionar_cursos_styles.css">
</head>
<body>
    <div class="container">
        <h2>CURSOS:</h2>
        <div class="curso-buttons">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Construir el enlace con el ID del curso como parámetro
                    echo "<a href='gestionar_clases.php?curso_id=" . $row["idCurso"] . "' class='curso-button'>" . $row["nombre"] . "</a>";
                }
            } else {
                echo "No se encontraron cursos para la especialidad de $docente_especialidad";
            }

            // Cerrar conexión
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
