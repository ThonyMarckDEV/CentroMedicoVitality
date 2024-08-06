<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si se ha proporcionado un ID de curso válido en la URL
if (isset($_GET['curso_id'])) {
    $curso_id = $_GET['curso_id'];

    // Consulta SQL para obtener las clases asociadas a un curso específico
    $sql = "SELECT * FROM clases WHERE idCurso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron clases asociadas al curso
    if ($result->num_rows > 0) {
?>

<?php 
include 'menu_alumno.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clases del Curso</title>
    <link rel="stylesheet" href="clases_curso_styles.css">
</head>
<body>
    <div class="container">
        <h2>Clases del Curso</h2>
        <div class="clases-container">
            <!-- PHP para mostrar las clases asociadas al curso -->
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="clase-box">
                    <h3><?php echo $row['nombre']; ?></h3>
                    <!-- Modificar el enlace para incluir la ID de la clase -->
                    <a href="ver_clase.php?clase_id=<?php echo $row['id_clase']; ?>">Ver Clase</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
        <?php
    } else {
        echo "No se encontraron clases para este curso.";
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    $conn->close();
} else {
    // Redirigir si no se proporcionó un ID de curso válido
    header('Location: cursos_alumno.php');
    exit();
}
?>
