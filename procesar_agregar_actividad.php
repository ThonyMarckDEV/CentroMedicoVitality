<?php
// Incluir archivo de conexión
require_once 'config.php';

// Verificar si se enviaron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $clase_id = $_POST['clase_id'];


    // Verificar si ya existe una actividad para esta clase
    $sql_check = "SELECT * FROM actividades WHERE id_clase = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $clase_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si ya existe una actividad, actualizarla
        $sql = "UPDATE actividades SET titulo = ?, descripcion = ?, fecha = ? WHERE id_clase = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $titulo, $descripcion, $fecha, $clase_id);
    } else {
        // Si no existe una actividad, insertarla
        $sql = "INSERT INTO actividades (titulo, descripcion, fecha, id_clase) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $titulo, $descripcion, $fecha, $clase_id);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a gestionar_actividades.php
        header("Location: gestionar_actividades.php?clase_id=" . $clase_id);
        exit(); // Asegúrate de detener la ejecución del script después de la redirección
    } else {
        echo "Error al actualizar la actividad: " . $conn->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "No se recibieron datos del formulario.";
}
?>
