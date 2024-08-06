<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si se ha proporcionado un ID de archivo válido en la URL
if (isset($_GET['archivo_id'])) {
    $archivo_id = $_GET['archivo_id'];

    // Consulta SQL para obtener la información del archivo
    $sql = "SELECT nombre, tipo, contenido FROM archivos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $archivo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el archivo
    if ($result->num_rows == 1) {
        // Obtener los datos del archivo
        $row = $result->fetch_assoc();
        $nombre = $row['nombre'];
        $tipo = $row['tipo'];
        $contenido = $row['contenido'];

        // Configurar las cabeceras para la descarga del archivo
        header("Content-Type: $tipo");
        header("Content-Disposition: attachment; filename='$nombre'");

        // Enviar el contenido del archivo al navegador
        echo $contenido;

        // Finalizar la ejecución del script
        exit();
    } else {
        echo "El archivo no existe.";
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    $conn->close();
} else {
    // Redirigir si no se proporcionó un ID de archivo válido
    header('Location: archivos_clase.php');
    exit();
}
?>
