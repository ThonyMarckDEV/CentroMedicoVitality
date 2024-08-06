<?php include 'menu_docente.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Tarea</title>
    <link rel="stylesheet" href="ver_tareas_styles.css">
</head>
<body>
    <?php
    require_once 'config.php';

    // Verificar si se recibió el ID de la tarea
    if (isset($_GET['id'])) {
        $taskId = $_GET['id'];
        
        // Consulta SQL para obtener la tarea específica
        $sql_tarea = "SELECT t.idTarea, u.nombre AS nombre_alumno, t.fecha_subida, t.archivo_nombre, t.archivo_contenido
                      FROM tareas_alumnos t 
                      INNER JOIN usuarios u ON t.idUsuario = u.idUsuario 
                      WHERE t.idTarea = ?";
        $stmt_tarea = $conn->prepare($sql_tarea);
        $stmt_tarea->bind_param("i", $taskId);
        $stmt_tarea->execute();
        $result_tarea = $stmt_tarea->get_result();

        // Verificar si se encontró la tarea
        if ($result_tarea->num_rows === 1) {
            $row_tarea = $result_tarea->fetch_assoc();
            // Mostrar la tarea y formulario para calificar
            echo "<div class='container'>";
            echo "<h2>Tarea</h2>";
            echo "<p>Alumno: " . $row_tarea['nombre_alumno'] . "</p>";
            echo "<p>Fecha Subida: " . $row_tarea['fecha_subida'] . "</p>";
            
           // Mostrar archivos
            echo "<div class='details'>";
            echo "<h3>Archivos:</h3>";
            if (!empty($row_tarea['archivo_nombre']) && !empty($row_tarea['archivo_contenido'])) {
                $archivo_nombre = htmlspecialchars($row_tarea['archivo_nombre']);
                $archivo_contenido = $row_tarea['archivo_contenido'];
            
                // Obtener la extensión del archivo
                $extension = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
            
                // Mostrar el contenido del archivo según su tipo
                if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {
                    echo "<img src='data:image/jpeg;base64," . base64_encode($archivo_contenido) . "' alt='$archivo_nombre' style='max-width: 700px;'><br>";
                } elseif (in_array($extension, array('mp4', 'webm', 'ogg'))) {
                    echo "<video controls>";
                    echo "<source src='data:video/mp4;base64," . base64_encode($archivo_contenido) . "' type='video/mp4'>";
                    echo "Tu navegador no soporta el elemento de video.";
                    echo "</video><br>";
                } elseif ($extension === 'pdf') {
                    echo "<iframe src='data:application/pdf;base64," . base64_encode($archivo_contenido) . "' width='600' height='400'></iframe><br>";
                } elseif ($extension === 'mp3') {
                    echo "<audio controls>";
                    echo "<source src='data:audio/mp3;base64," . base64_encode($archivo_contenido) . "' type='audio/mp3'>";
                    echo "Tu navegador no soporta el elemento de audio.";
                    echo "</audio><br>";
                } else {
                    echo "Archivo no compatible: $archivo_nombre";
                }
            
                // Botón de descarga
                echo "<a href='data:application/octet-stream;base64," . base64_encode($archivo_contenido) . "' download='$archivo_nombre' class='download-button'>Descargar tarea</a><br>";
            } else {
                echo "No hay archivos para esta tarea.";
            }
            echo "</div>"; // Cierre de la sección para mostrar archivos


            // Formulario para calificar la tarea
            echo "<form action='procesar_calificar_tarea.php' method='post'>"; // Cambiado a POST
            echo "<label for='grade'>Calificación:</label>";
            echo "<input type='number' id='grade' name='grade' min='0' max='20' step='0.1' required>";
            echo "<input type='hidden' name='taskId' value='" . $taskId . "'>";
            echo "<input type='submit' value='Calificar'>";
            echo "</form>";
            echo "</div>"; // Cierre del contenedor
        } else {
            echo "Error: No se encontró la tarea.";
        }
    } else {
        echo "Error: ID de tarea no especificado.";
    }
    ?>
</body>
</html>
