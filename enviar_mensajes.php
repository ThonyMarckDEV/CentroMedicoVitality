<?php
// Incluir el archivo de configuración
require_once 'config.php';


// Obtener el nombre de usuario del docente que inició sesión
$username = $_SESSION['username'];

// Consulta SQL para obtener la id del usuario por nombre
$sql_usuario = "SELECT idUsuario, especialidad FROM usuarios WHERE nombre = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $username);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

// Obtener el ID del docente y su especialidad
if ($result_usuario->num_rows === 1) {
    $docente = $result_usuario->fetch_assoc();
    $docente_id = $docente['idUsuario'];
    $especialidad_docente = $docente['especialidad'];
} else {
    // Manejar el caso en el que no se encuentre el docente
    echo 'Error: No se pudo encontrar el docente.';
    exit();
}

// Consulta SQL para obtener los cursos del docente por su especialidad
$sql_cursos = "SELECT DISTINCT c.idCurso, c.nombre AS nombre_curso 
              FROM cursos c
              JOIN especialidades e ON c.idEspecialidad = e.idEspecialidad
              WHERE e.nombre = ?";
$stmt_cursos = $conn->prepare($sql_cursos);
$stmt_cursos->bind_param("s", $especialidad_docente);
$stmt_cursos->execute();
$result_cursos = $stmt_cursos->get_result();

// Preparar un array para almacenar los cursos
$cursos = array();
while($row = $result_cursos->fetch_assoc()) {
    $cursos[] = $row;
}

// Cerrar conexión al final del script
$conn->close();
?>

<?php 
include 'menu_docente.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Mensajes</title>
    <link rel="stylesheet" href="enviar_mensajes_styles.css">
</head>
<body>
    <div class="container">
        <h2>Enviar Mensajes</h2>
        <form action="procesar_mensaje.php" method="POST">
            <label for="curso">Seleccionar Curso:</label>
            <select id="curso" name="curso" required>
                <option value="">Seleccione un curso</option>
                <?php
                foreach ($cursos as $curso) {
                    echo "<option value='" . htmlspecialchars($curso["idCurso"]) . "'>" . htmlspecialchars($curso["nombre_curso"]) . "</option>";
                }
                ?>
            </select><br><br>
            <label for="mensaje">Mensaje:</label><br>
            <textarea id="mensaje" name="mensaje" rows="4" cols="50" required></textarea><br><br>
            <button type="submit">Enviar Mensaje</button>
        </form>
    </div>
</body>
</html>
