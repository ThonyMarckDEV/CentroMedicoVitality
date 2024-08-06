<?php
// Incluir el archivo de configuración que inicia la sesión
require 'config.php';

// Verificar si la sesión está iniciada
if (isset($_SESSION['username'])) {
    // Obtener el nombre de usuario de la sesión
    $username = $_SESSION['username'];

    // Actualizar el estado del usuario a loggedOff en la base de datos
    $sql = "UPDATE usuarios SET status = 'loggedOff' WHERE nombre = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    // Redirigir al formulario de inicio de sesión
    header('Location: index.php');
    exit();
} else {
    // Si no hay sesión iniciada, mostrar un mensaje de error o redirigir a alguna otra página
    echo "No hay sesión iniciada.";
    // O redirigir a alguna otra página
    // header('Location: alguna_pagina.php');
    exit();
}
?>
