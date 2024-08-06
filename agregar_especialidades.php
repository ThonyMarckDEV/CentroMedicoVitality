<?php
session_start();
include 'menu_admin.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Especialidades</title>
    <link rel="stylesheet" href="agregar_especialidades_styles.css">
</head>
<body>
    <div class="container">
        <h2>Agregar Especialidad</h2>
        <?php
        // Mostrar mensaje de error si la especialidad ya existe
        if (isset($_GET['error']) && $_GET['error'] == 'exists') {
            echo "<div class='error-message'>La especialidad ya existe. Por favor, elija otro nombre.</div><br>";
        }
        ?>
        <form action="procesar_agregar_especialidad.php" method="POST">
            <label for="nombre">Nombre de Especialidad:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>

            <button type="submit">Agregar Especialidad</button>
        </form>
    </div>
</body>
</html>
