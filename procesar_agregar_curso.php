<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $especialidad_id = $_POST['especialidad'];
    $cupos = 30; // Valor por defecto

    // Verificar si el curso ya existe
    $sql_check = "SELECT * FROM cursos WHERE nombre = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si el curso ya existe, redirigir de nuevo al formulario con un mensaje de error
        header('Location: agregar_cursos.php?error=exists');
        $stmt_check->close();
        $conn->close();
        exit();
    }

    $stmt_check->close();

    // Preparar y ejecutar la consulta SQL para insertar el nuevo curso
    $sql_curso = "INSERT INTO cursos (nombre, idEspecialidad, cupos) VALUES (?, ?, ?)";
    $stmt_curso = $conn->prepare($sql_curso);
    if (!$stmt_curso) {
        die("Error en la preparación de la consulta del curso: " . $conn->error);
    }
    $stmt_curso->bind_param("sii", $nombre, $especialidad_id, $cupos);
    if ($stmt_curso->execute()) {
        // Obtener el ID del curso recién insertado
        $curso_id = $stmt_curso->insert_id;

        // Preparar y ejecutar la consulta SQL para insertar las clases asociadas al nuevo curso
        $sql_clases = "INSERT INTO clases (nombre, idCurso) VALUES (?, ?)";
        $stmt_clases = $conn->prepare($sql_clases);
        if (!$stmt_clases) {
            die("Error en la preparación de la consulta de las clases: " . $conn->error);
        }
        
        // Insertar tres clases asociadas al nuevo curso
        $clases = ["Clase 1", "Clase 2", "Clase 3"];
        foreach ($clases as $clase) {
            $stmt_clases->bind_param("si", $clase, $curso_id);
            if ($stmt_clases->execute()) {
                // Obtener el ID de la clase recién insertada
                $clase_id = $stmt_clases->insert_id;

                // Preparar y ejecutar la consulta SQL para insertar una actividad asociada a la nueva clase
                $sql_actividad = "INSERT INTO actividades (titulo, descripcion, fecha, id_clase) VALUES (?, ?, ?, ?)";
                $stmt_actividad = $conn->prepare($sql_actividad);
                if (!$stmt_actividad) {
                    die("Error en la preparación de la consulta de la actividad: " . $conn->error);
                }
                
                // Insertar la actividad asociada a la clase
                $titulo_actividad = ""; // Título vacío
                $descripcion_actividad = ""; // Descripción vacía
                $fecha_actividad = null; // Fecha vacía

                $stmt_actividad->bind_param("sssi", $titulo_actividad, $descripcion_actividad, $fecha_actividad, $clase_id);
                if (!$stmt_actividad->execute()) {
                    echo "Error al agregar la actividad: " . $stmt_actividad->error;
                    exit(); // Salir del script en caso de error
                }
            } else {
                echo "Error al agregar la clase: " . $stmt_clases->error;
                exit(); // Salir del script en caso de error
            }
        }

        // Redirigir a la página de administrador
        header('Location: UIadmin.php');
        exit();
    } else {
        echo "Error al agregar el curso: " . $stmt_curso->error;
    }

    // Cerrar las declaraciones y la conexión
    $stmt_curso->close();
    $stmt_clases->close();
    $stmt_actividad->close();
    $conn->close();
} else {
    // Redirigir si se intenta acceder al script directamente
    header('Location: agregar_cursos.php');
    exit();
}
?>
