<?php
// Incluir el archivo de configuración
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si se han enviado los campos necesarios
    if (isset($_POST['usuario_id']) && isset($_POST['actividad_id']) && isset($_FILES['archivo'])) {
        $usuario_id = $_POST['usuario_id'];
        $actividad_id = $_POST['actividad_id'];
        $archivo = $_FILES['archivo'];

        // Procesar la subida del archivo
        $nombre_archivo = $archivo['name'];
        $tipo_archivo = $archivo['type'];
        $contenido_archivo = file_get_contents($archivo['tmp_name']);

        // Insertar la tarea en la base de datos
        $sql = "INSERT INTO tareas_alumnos (idUsuario, idActividad, archivo_nombre, archivo_tipo, archivo_contenido) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissb", $usuario_id, $actividad_id, $nombre_archivo, $tipo_archivo, $null);
        $stmt->send_long_data(4, $contenido_archivo);

        if ($stmt->execute()) {
              header("Location: UIalumno.php");
              exit();
        } else {
            echo "Error al subir la tarea: " . $conn->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error: Campos necesarios no enviados.";
    }
} else {
    echo "Error: Método de solicitud no permitido.";
}

// Cerrar la conexión
$conn->close();
?>
