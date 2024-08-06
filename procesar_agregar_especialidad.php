<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];

    // Verificar si la especialidad ya existe
    $sql_check = "SELECT * FROM especialidades WHERE nombre = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si la especialidad ya existe, redirigir de nuevo al formulario con un mensaje de error
        header('Location: agregar_especialidades.php?error=exists');
        $stmt_check->close();
        $conn->close();
        exit();
    }

    $stmt_check->close();

    // Preparar y ejecutar la consulta SQL para insertar la nueva especialidad
    $sql = "INSERT INTO especialidades (nombre) VALUES (?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $nombre);
    if ($stmt->execute()) {
        // Redirigir a la página de administrador
        header('Location: UIadmin.php');
        exit();
    } else {
        echo "Error al agregar la especialidad: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Redirigir si se intenta acceder al script directamente
    header('Location: agregar_especialidades.php');
    exit();
}
?>
