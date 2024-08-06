<?php
// Incluir el archivo de configuración
require_once 'config.php';

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibieron los datos necesarios
   if (isset($_POST['curso']) && isset($_POST['mensaje'])) {
    // Obtener el ID del curso y el texto del mensaje
    $curso_id = $_POST['curso'];
    $texto = $_POST['mensaje'];

        // Obtener la ID del usuario (docente) que envía el mensaje y su especialidad

        $username = $_SESSION['username'];
        $sql_usuario = "SELECT idUsuario, especialidad FROM usuarios WHERE nombre = ?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("s", $username);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        if ($result_usuario->num_rows === 1) {
            $usuario = $result_usuario->fetch_assoc();
            $usuario_id = $usuario['idUsuario'];
            $especialidad_docente = $usuario['especialidad'];
        } else {
            echo 'Error: No se pudo encontrar el usuario.';
            exit();
        }

        // Insertar el mensaje en la tabla de mensajes
        $sql_insertar_mensaje = "INSERT INTO mensajes (idDocente, especialidad, idCurso, texto) VALUES (?, ?, ?, ?)";
        $stmt_insertar_mensaje = $conn->prepare($sql_insertar_mensaje);
        $stmt_insertar_mensaje->bind_param("isis", $usuario_id, $especialidad_docente, $curso_id, $texto);

        if ($stmt_insertar_mensaje->execute()) {
            // Éxito al insertar el mensaje
            header('Location: UIdocente.php');
            exit();
        } else {
            // Error al insertar el mensaje
            echo 'Error: No se pudo enviar el mensaje.';
            exit();
        }
    } else {
        echo 'Error: Falta información del formulario.';
        exit();
    }
} else {
    echo 'Error: Método de solicitud no permitido.';
    exit();
}
?>
