<?php
require 'config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $contrasena = $_POST['contrasena'];
    $especialidad = $_POST['especialidad'];
    $tipo = $_POST['tipo'];
    $status = 'loggedOff'; // Puedes cambiar este valor según tus necesidades

    // Verificar si el nombre de usuario ya existe
    $sql_check = "SELECT * FROM usuarios WHERE nombre = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si el usuario ya existe, redirigir de nuevo al formulario con un mensaje de error
        header('Location: agregar_usuarios.php?error=exists');
        $stmt_check->close();
        $conn->close();
        exit();
    }

    $stmt_check->close();

    // Preparar y ejecutar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, contrasena, especialidad, tipo, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("sssss", $nombre, $contrasena, $especialidad, $tipo, $status);
    if ($stmt->execute()) {
        // Redirigir a la interfaz de administrador
        header('Location: UIadmin.php');
        exit();
    } else {
        echo "Error al agregar el usuario: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Redirigir si se intenta acceder al script directamente
    header('Location: agregar_usuarios.php');
    exit();
}
?>
