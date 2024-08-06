<?php include 'menu_admin.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matricular Alumnos</title>
    <link rel="stylesheet" href="matricular_alumnos_styles.css">
</head>
<body>
    <h2>Matricular Alumnos</h2>
    <form action="procesar_matricula.php" method="POST">
        <label for="alumno">Alumno:</label>
        <select id="alumno" name="alumno" required>
            <!-- PHP para cargar dinámicamente los usuarios tipo alumno -->
            <?php
            // Incluir el archivo de configuración
            require 'config.php';

            // Consulta para obtener los usuarios tipo alumno
            $sql_alumnos = "SELECT idUsuario, nombre, especialidad FROM usuarios WHERE tipo = 'alumno'";
            $result_alumnos = $conn->query($sql_alumnos);

            // Verificar si se encontraron resultados
            if ($result_alumnos->num_rows > 0) {
                // Iterar sobre los resultados y mostrar cada alumno como una opción en el combobox
                while ($row_alumno = $result_alumnos->fetch_assoc()) {
                    echo "<option value='" . $row_alumno['idUsuario'] . "'>" . $row_alumno['nombre'] . "  "  . " (Especialidad: "  . $row_alumno['especialidad']. ")"  ."</option>";
                }
            } else {
                echo "<option value=''>No hay alumnos disponibles</option>";
            }
            ?>
        </select><br><br>

        <label for="curso">Curso:</label>
        <select id="curso" name="curso" required>
            <!-- Opciones de cursos -->
            <!-- PHP para cargar dinámicamente los cursos disponibles -->
            <?php
            // Consulta para obtener los cursos disponibles
            $sql_cursos = "SELECT idCurso, nombre, cupos FROM cursos WHERE cupos > 0";
            $result_cursos = $conn->query($sql_cursos);

            // Verificar si se encontraron resultados
            if ($result_cursos->num_rows > 0) {
                // Iterar sobre los resultados y mostrar cada curso como una opción en el combobox
                while ($row_curso = $result_cursos->fetch_assoc()) {
                    echo "<option value='" . $row_curso['idCurso'] . "'>" . $row_curso['nombre'] . " (Cupos disponibles: " . $row_curso['cupos'] . ")</option>";
                }
            } else {
                echo "<option value=''>No hay cursos disponibles</option>";
            }
            ?>
        </select><br><br>

        <label for="fecha_ini">Fecha de Inicio:</label>
        <input type="date" id="fecha_ini" name="fecha_ini" min="<?php echo date('Y-m-d'); ?>" required><br><br>

        <label for="fecha_fin">Fecha de Termino:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required><br><br>

        <button type="submit">Matricular Alumno</button>
    </form>

    <h2>Alumnos Matriculados</h2>
    <table>
        <thead>
            <tr>
                <th>ID Matrícula</th>
                <th>Alumno</th>
                <th>Curso</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Termino</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta para obtener los alumnos matriculados con nombres de alumnos y cursos
            $sql_matriculas = "SELECT matricula_alumnos.idMatricula,
                                usuarios.nombre AS nombre_alumno,
                                cursos.nombre AS nombre_curso, 
                                matricula_alumnos.fechaIni, 
                                matricula_alumnos.fechaTer
                                FROM matricula_alumnos
                                INNER JOIN usuarios ON matricula_alumnos.idUsuario = usuarios.idUsuario
                                INNER JOIN cursos ON matricula_alumnos.idCurso = cursos.idCurso";
            $result_matriculas = $conn->query($sql_matriculas);

            // Verificar si se encontraron resultados
            if ($result_matriculas->num_rows > 0) {
                // Iterar sobre los resultados y mostrar cada matrícula en la tabla
                while ($row_matricula = $result_matriculas->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_matricula['idMatricula'] . "</td>";
                    echo "<td>" . $row_matricula['nombre_alumno'] . "</td>";
                    echo "<td>" . $row_matricula['nombre_curso'] . "</td>";
                    echo "<td>" . $row_matricula['fechaIni'] . "</td>";
                    echo "<td>" . $row_matricula['fechaTer'] . "</td>";
                    echo "<td>
                            <form action='eliminar_matricula.php' method='POST'>
                                <input type='hidden' name='idMatricula' value='" . $row_matricula['idMatricula'] . "'>
                                <button type='submit'>Eliminar</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay alumnos matriculados</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="matricular_alumnos_script.js"></script>
</body>
</html>
