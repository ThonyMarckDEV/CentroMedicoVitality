<?php 
session_start();
include 'menu_admin.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cursos</title>
    <link rel="stylesheet" href="agregar_cursos_styles.css">
</head>
<body>
    <div class="container">
        <h2>Agregar Curso</h2>
        <?php
        // Mostrar mensaje de error si el curso ya existe
        if (isset($_GET['error']) && $_GET['error'] == 'exists') {
            echo "<div class='error-message'>El curso ya existe. Por favor, elija otro nombre.</div><br>";
        }
        ?>
        <form action="procesar_agregar_curso.php" method="POST">
            <label for="nombre">Nombre del Curso:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>
            
            <label for="especialidad">Especialidad:</label>
            <select id="especialidad" name="especialidad" required>
                <?php
                // Incluir el archivo de configuración
                require 'config.php';

                // Consulta para obtener las especialidades de la base de datos
                $sql = "SELECT idEspecialidad, nombre FROM especialidades";
                $result = $conn->query($sql);

                // Verificar si se encontraron resultados
                if ($result->num_rows > 0) {
                    // Iterar sobre los resultados y mostrar cada especialidad como una opción en el combobox
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['idEspecialidad'] . "'>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay especialidades disponibles</option>";
                }

                // Cerrar la conexión
                $conn->close();
                ?>
            </select><br><br>

            <button type="submit">Agregar Curso</button>
        </form>
    </div>
</body>
</html>
