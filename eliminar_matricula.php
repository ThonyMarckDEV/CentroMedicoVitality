<?php
// Verificar si se recibió un ID de matrícula válido
if(isset($_POST['idMatricula']) && !empty($_POST['idMatricula'])) {
    // Incluir el archivo de configuración de la base de datos
    require 'config.php';

    // Obtener el ID de matrícula desde el formulario
    $idMatricula = $_POST['idMatricula'];

    // Consultar la información de la matrícula para obtener el ID del curso
    $sql_info_matricula = "SELECT idCurso FROM matricula_alumnos WHERE idMatricula = $idMatricula";
    $result_info_matricula = $conn->query($sql_info_matricula);

    // Verificar si se encontró la información de la matrícula
    if($result_info_matricula->num_rows == 1) {
        $row_info_matricula = $result_info_matricula->fetch_assoc();
        $idCurso = $row_info_matricula['idCurso'];

        // Eliminar la matrícula
        $sql_eliminar_matricula = "DELETE FROM matricula_alumnos WHERE idMatricula = $idMatricula";
        if($conn->query($sql_eliminar_matricula) === TRUE) {
            // Incrementar el cupo disponible en el curso correspondiente
            $sql_incrementar_cupo = "UPDATE cursos SET cupos = cupos + 1 WHERE idCurso = $idCurso";
            if($conn->query($sql_incrementar_cupo) === TRUE) {
                // Redireccionar a la página principal
                header("Location: matricular_alumnos.php");
                exit();
            } else {
                echo "Error al incrementar el cupo: " . $conn->error;
            }
        } else {
            echo "Error al eliminar la matrícula: " . $conn->error;
        }
    } else {
        echo "ID de matrícula no válido.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "ID de matrícula no recibido.";
}
?>
