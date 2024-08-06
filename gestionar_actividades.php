<?php 
include 'menu_docente.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades de Clase</title>
    <link rel="stylesheet" href="gestiona_actividades_styles.css">
</head>
<body>
    <div class="container">
        <h2>ACTIVIDADES DE CLASE</h2>
        <?php
        // Incluir archivo de conexión
        require_once 'config.php';

        // Verificar si se proporcionó un ID de clase en la URL
        if (isset($_GET['clase_id'])) {
            // Obtener el ID de la clase de la URL
            $clase_id = $_GET['clase_id'];

            // Consulta para obtener las actividades relacionadas con la clase
            $sql = "SELECT * FROM actividades WHERE id_clase = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $clase_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Mostrar recuadro para cada actividad con opciones de editar y borrar
                    echo "<div class='actividad-box'>";
                    echo "<h3>" . htmlspecialchars($row["titulo"]) . "</h3>";
                    echo "<p>" . htmlspecialchars($row["descripcion"]) . "</p>";
                    echo "<p>Fecha: " . htmlspecialchars($row["fecha"]) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "No se encontraron actividades para esta clase.";
            }

            // Cerrar declaración y conexión
            $stmt->close();
        } else {
            echo "No se proporcionó un ID de clase.";
        }

        $conn->close();
        ?>

        <h2>Agregar Actividad</h2>
        <form action="procesar_agregar_actividad.php" method="post">
            <input type="hidden" name="clase_id" value="<?php echo htmlspecialchars($_GET['clase_id']); ?>">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required><br>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea><br>
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" min="<?php echo date('Y-m-d'); ?>" required><br>
            <input type="submit" value="Asignar Actividad">
        </form>
        <h2>Agregar Material</h2>
        <a href="agregar_material.php"><button>AGREGAR MATERIAL</button></a>
    </div>
</body>
</html>
