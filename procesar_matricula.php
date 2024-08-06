<?php
// Incluir el archivo de configuración
require 'config.php';

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $alumno = $_POST['alumno'];
    $curso = $_POST['curso'];
    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];

    // Verificar si el alumno ya está matriculado en el curso
    $sql_verificar_matricula = "SELECT * FROM matricula_alumnos WHERE idUsuario = ? AND idCurso = ?";
    $stmt_verificar_matricula = $conn->prepare($sql_verificar_matricula);
    if (!$stmt_verificar_matricula) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_verificar_matricula->bind_param("ii", $alumno, $curso);
    $stmt_verificar_matricula->execute();
    $result_verificar_matricula = $stmt_verificar_matricula->get_result();

    if ($result_verificar_matricula->num_rows > 0) {
        // El alumno ya está matriculado en el curso, mostrar error y salir
        echo "Error: El alumno ya está matriculado en este curso.";
        exit();
    }

    // Si el alumno no está matriculado en el curso, proceder con la matrícula

    // Iniciar una transacción
    $conn->begin_transaction();

    // Realizar la consulta SQL para insertar la matrícula del alumno en el curso
    $sql_insert = "INSERT INTO matricula_alumnos (idUsuario, idCurso, fechaIni, fechaTer) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    if (!$stmt_insert) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt_insert->bind_param("iiss", $alumno, $curso, $fecha_ini, $fecha_fin);
    if (!$stmt_insert->execute()) {
        echo "Error al matricular al alumno: " . $stmt_insert->error;
        $conn->rollback(); // Revertir la transacción en caso de error
        exit();
    }

    // Actualizar la tabla de cursos para restar un cupo
    $sql_update = "UPDATE cursos SET cupos = cupos - 1 WHERE idCurso = ?";
    $stmt_update = $conn->prepare($sql_update);
    if (!$stmt_update) {
        echo "Error en la preparación de la consulta: " . $conn->error;
        $conn->rollback(); // Revertir la transacción en caso de error
        exit();
    }
    $stmt_update->bind_param("i", $curso);
    if (!$stmt_update->execute()) {
        echo "Error al actualizar el curso: " . $stmt_update->error;
        $conn->rollback(); // Revertir la transacción en caso de error
        exit();
    }

    // Confirmar la transacción
    $conn->commit();

    // Redirigir a la página de administrador
    header('Location: matricular_alumnos.php');
    exit();
} else {
    // Redirigir si se intenta acceder al script directamente
    header('Location: matricular_alumnos.php');
    exit();
}
?>
