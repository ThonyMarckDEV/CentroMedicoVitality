<?php
require_once 'config.php'; // Incluir la conexión a la base de datos

// Verificar si se recibió la calificación y el ID de la tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["grade"]) && isset($_POST["taskId"])) {
    $grade = $_POST["grade"];
    $taskId = $_POST["taskId"];

    // Validar la calificación para asegurarse de que esté dentro del rango permitido
    if ($grade < 0 || $grade > 20) {
        echo "Error: La calificación debe estar entre 0 y 20.";
        exit();
    }

    // Iniciar una transacción para asegurar la consistencia de los datos
    $conn->begin_transaction();

    try {
        // Actualizar la tabla tareas_alumnos con la calificación
        $sql_update_grade = "UPDATE tareas_alumnos SET nota = ? WHERE idTarea = ?";
        $stmt_update_grade = $conn->prepare($sql_update_grade);
        $stmt_update_grade->bind_param("di", $grade, $taskId);
        $stmt_update_grade->execute();

        // Copiar el registro a la tabla tareas_revisadas
        $sql_copy_task = "INSERT INTO tareas_revisadas (idTarea,idUsuario,idActividad,nota,archivo_nombre,archivo_tipo,archivo_contenido,fecha_subida)
                          SELECT idTarea,idUsuario,idActividad,nota,archivo_nombre,archivo_tipo,archivo_contenido,fecha_subida FROM tareas_alumnos WHERE idTarea = ?";
        $stmt_copy_task = $conn->prepare($sql_copy_task);
        $stmt_copy_task->bind_param("i", $taskId);
        $stmt_copy_task->execute();

        // Eliminar la tarea de la tabla tareas_alumnos
        $sql_delete_task = "DELETE FROM tareas_alumnos WHERE idTarea = ?";
        $stmt_delete_task = $conn->prepare($sql_delete_task);
        $stmt_delete_task->bind_param("i", $taskId);
        $stmt_delete_task->execute();

        // Confirmar la transacción
        $conn->commit();

        // Redirigir de vuelta a la página principal o a donde sea apropiado
        header("Location: gestionar_tareas.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error al procesar la tarea: " . $e->getMessage();
    }

    // Cerrar las declaraciones
    $stmt_update_grade->close();
    $stmt_copy_task->close();
    $stmt_delete_task->close();
} else {
    // Si no se proporcionaron los parámetros necesarios, mostrar un mensaje de error o redirigir a alguna página apropiada
    echo "Error: Parámetros incorrectos.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
