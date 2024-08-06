<?php
if(isset($_GET['curso'])) {
    $curso_id = $_GET['curso'];
    require 'config.php';
    $sql = "SELECT id_clase, nombre FROM clases WHERE idCurso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header('Location: agregar_material.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Material - Seleccione la Clase</title>
    <link rel="stylesheet" href="agregar_material_styles.css">
</head>
<body>
    <div class="container">
        <h2>Seleccione la Clase</h2>
        <form action="subir_archivo.php" method="get">
            <input type="hidden" name="curso" value="<?php echo $curso_id; ?>">
            <label for="clase">Seleccione la Clase:</label>
            <select name="clase" id="clase">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id_clase"] . "'>" . $row["nombre"] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay clases disponibles para este curso</option>";
                }
                ?>
            </select>
            <br><br>
            <input type="submit" value="Continuar">
        </form>
    </div>
</body>
</html>
