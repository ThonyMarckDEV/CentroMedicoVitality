<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si se ha proporcionado un ID de clase válido en la URL
if (isset($_GET['clase_id'])) {
    $clase_id = $_GET['clase_id'];

    // Consulta SQL para obtener los archivos asociados a la clase específica
    $sql = "SELECT * FROM archivos WHERE id_clase = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $clase_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron archivos asociados a la clase
    if ($result->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Archivos de la Clase</title>
            <link rel="stylesheet" href="archivos_clase_styles.css">
        </head>
        <body>
            <div class="container">
                <h2>Archivos de la Clase</h2>
                <div class="archivos-container">
                    <!-- PHP para mostrar los archivos asociados a la clase -->
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="archivo-box">
                            <h3><?php echo $row['nombre']; ?></h3>
                            <a href="descargar_archivo.php?archivo_id=<?php echo $row['id']; ?>">Descargar</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "No se encontraron archivos para esta clase.";
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    $conn->close();
} else {
    // Redirigir si no se proporcionó un ID de clase válido
    header('Location: clases_curso.php');
    exit();
}
?>
