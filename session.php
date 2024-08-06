<?php
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Redirigir al usuario al formulario de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}
?>