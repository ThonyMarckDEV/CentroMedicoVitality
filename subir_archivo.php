<?php
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se ha subido un archivo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        // Obtener los datos del formulario
        $clase_id = $_POST['clase'];
        $archivo_nombre = $_FILES['archivo']['name'];
        $archivo_tipo = $_FILES['archivo']['type'];
        $archivo_contenido = file_get_contents($_FILES['archivo']['tmp_name']);

        // Incluir el archivo de configuración de la base de datos
        require 'config.php';

        // Preparar y ejecutar la consulta para insertar el archivo en la tabla
        $sql = "INSERT INTO archivos (nombre, tipo, contenido, id_clase) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $archivo_nombre, $archivo_tipo, $archivo_contenido, $clase_id);
        
        if ($stmt->execute()) {
            // Redirigir si la inserción fue exitosa
            header('Location: UIdocente.php');
            exit();
        } else {
            // Manejar el error si la inserción falla
            echo "Error al agregar el archivo a la base de datos: " . $stmt->error;
        }
    } else {
        // Manejar el error si no se subió ningún archivo o si ocurrió un error durante la subida
        echo "Error al subir el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Material - Subir Archivo</title>
    <link rel="stylesheet" href="agregar_material_styles.css">
</head>
<body>
    <div class="container">
        <h2>Subir Archivo</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="clase" value="<?php echo $_GET['clase']; ?>">
            <label for="archivo">Seleccione el Archivo:</label>
            <!-- Remover el filtro para aceptar cualquier tipo de archivo -->
            <input type="file" name="archivo" id="archivo" accept="*/*">
            <br><br>
            <input type="submit" value="Subir Archivo">
        </form>
    </div>
</body>
</html>
