<?php 
include 'menu_docente.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clases</title>
    <link rel="stylesheet" href="gestionar_clases_styles.css">
</head>
<body>
    <div class="container">
        <h2>CLASES:</h2>
        <div class="clases-container">
            <?php
            // Incluir archivo de conexión
            require_once 'config.php';

            // Verificar si se proporcionó un ID de curso en la URL
            if (isset($_GET['curso_id'])) {
                // Obtener el ID del curso de la URL
                $curso_id = $_GET['curso_id'];

                // Consulta para obtener las clases relacionadas con el curso
                $sql = "SELECT * FROM clases WHERE idCurso = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $curso_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Mostrar información de las clases con enlaces que incluyen la ID de la clase
                        echo "<div class='clase-box'><a href='gestionar_actividades.php?clase_id=" . htmlspecialchars($row["id_clase"]) . "'>" . htmlspecialchars($row["nombre"]) . "</a></div>";
                    }
                } else {
                    echo "<div>No se encontraron clases para este curso.</div>";
                }

                // Cerrar la declaración
                $stmt->close();
            } else {
                echo "<div>No se proporcionó un ID de curso.</div>";
            }

            // Cerrar conexión
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
