<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Material - Seleccione el Curso</title>
    <link rel="stylesheet" href="agregar_material_styles.css">
</head>
<body>
    <div class="container">
        <h2>Seleccione el Curso</h2>
        <form action="listar_clases.php" method="get">
            <label for="curso">Seleccione el Curso:</label>
            <select name="curso" id="curso">
                <?php
                require_once 'config.php';

                // Verificar si el usuario está logeado
                if (!isset($_SESSION['username'])) {
                    header('Location: login.php');
                    exit;
                }

                // Obtener el nombre del usuario logeado
                $username = $_SESSION['username'];

                // Consulta para obtener la especialidad del docente logeado
                $especialidad_sql = "SELECT especialidad FROM usuarios WHERE nombre = ?";
                $stmt = $conn->prepare($especialidad_sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $docente_especialidad = $row['especialidad'];
                } else {
                    echo "No se encontró la especialidad para el usuario logeado.";
                    exit;
                }

                // Consulta para obtener los cursos según la especialidad del docente
                $sql = "SELECT DISTINCT
                            C.idCurso,
                            C.nombre AS 'Curso'
                        FROM
                            cursos C
                        JOIN
                            especialidades E ON C.idEspecialidad = E.idEspecialidad
                        WHERE
                            E.nombre = ?
                        ORDER BY
                            C.nombre";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $docente_especialidad);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row["idCurso"]) . "'>" . htmlspecialchars($row["Curso"]) . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay cursos disponibles</option>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </select>
            <br><br>
            <input type="submit" value="Continuar">
        </form>
    </div>
</body>
</html>
