<?php
session_start();
require 'config.php';

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$nombre_usuario = $_SESSION['username'];
$sql_id_usuario = "SELECT idUsuario FROM usuarios WHERE nombre = ?";
$stmt_id_usuario = $conn->prepare($sql_id_usuario);
$stmt_id_usuario->bind_param("s", $nombre_usuario);
$stmt_id_usuario->execute();
$result_id_usuario = $stmt_id_usuario->get_result();

if ($result_id_usuario->num_rows > 0) {
    $row = $result_id_usuario->fetch_assoc();
    $idUsuario = $row['idUsuario'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fields = [
            'nombreCompleto', 'correo', 'pronombres', 'sexo', 'fecha_nacimiento', 
            'nivel_educacion', 'direccion', 'telefono', 'departamento'
        ];
        $somethingUpdated = false;

        foreach ($fields as $field) {
            if (!empty($_POST[$field])) {
                $sql = "UPDATE usuarios SET $field = ? WHERE idUsuario = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $_POST[$field], $idUsuario);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $somethingUpdated = true;
                }
                $stmt->close();
            }
        }

        if (!empty($_FILES['foto']['tmp_name'])) {
            // Obtener datos de la imagen
            $foto_tmp = $_FILES['foto']['tmp_name'];
            $foto_tipo = $_FILES['foto']['type'];

            // Leer datos de la imagen
            $foto_contenido = file_get_contents($foto_tmp);

            // Actualizar la columna foto_perfil en la base de datos
            $sql_update_foto = "UPDATE usuarios SET foto_perfil = ?, foto_tipo = ? WHERE idUsuario = ?";
            $stmt_update_foto = $conn->prepare($sql_update_foto);
            $stmt_update_foto->bind_param("ssi", $foto_contenido, $foto_tipo, $idUsuario);
            $stmt_update_foto->execute();
            $stmt_update_foto->close();
            $somethingUpdated = true;
        }

        if ($somethingUpdated) {
            // Redirigir al usuario a perfil_alumno.php después de la actualización
            header('Location: perfil_usuario.php');
            exit();
        } else {
            echo "No se realizaron actualizaciones.";
        }
    }
} else {
    echo "No se encontró ningún usuario con el nombre de usuario proporcionado.";
}

$stmt_id_usuario->close();
$conn->close();
?>
