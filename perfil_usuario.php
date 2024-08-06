<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

$nombre_usuario = $_SESSION['username'];

// Consultar el tipo de usuario
$sql_tipo_usuario = "SELECT tipo FROM usuarios WHERE nombre = ?";
$stmt_tipo_usuario = $conn->prepare($sql_tipo_usuario);
$stmt_tipo_usuario->bind_param("s", $nombre_usuario);
$stmt_tipo_usuario->execute();
$result_tipo_usuario = $stmt_tipo_usuario->get_result();

if ($result_tipo_usuario->num_rows > 0) {
    $tipo_usuario = $result_tipo_usuario->fetch_assoc()['tipo'];

    // Incluir el menú correspondiente según el tipo de usuario
    if ($tipo_usuario == 'alumno') {
        include 'menu_alumno.php';
    } elseif ($tipo_usuario == 'docente') {
        include 'menu_docente.php';
    } else {
        // Opcional: Manejar otros tipos de usuario si existen
        echo "Tipo de usuario no reconocido.";
    }
} else {
    echo "No se encontró ningún usuario con el nombre de usuario proporcionado.";
    exit();
}

$stmt_tipo_usuario->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="perfil_alumno_styles.css">
</head>
<body>
    <div class="container">
        <h2>Perfil de Usuario</h2>
        <form id="perfilForm" method="POST" action="procesar_perfil_usuario.php" enctype="multipart/form-data">
            <div class="perfil-info">
                <div class="perfil-foto">
                    <label for="foto">Foto de perfil:</label><br>
                    <?php
                        // Mostrar la foto de perfil si está disponible
                        require 'config.php';
                        $sql_foto_perfil = "SELECT foto_perfil, foto_tipo FROM usuarios WHERE nombre = ?";
                        $stmt_foto_perfil = $conn->prepare($sql_foto_perfil);
                        $stmt_foto_perfil->bind_param("s", $nombre_usuario);
                        $stmt_foto_perfil->execute();
                        $result_foto_perfil = $stmt_foto_perfil->get_result();

                        if ($result_foto_perfil->num_rows > 0) {
                            $row_foto_perfil = $result_foto_perfil->fetch_assoc();
                            $foto_perfil = $row_foto_perfil['foto_perfil'];
                            $foto_tipo = $row_foto_perfil['foto_tipo'];
                            $foto_base64 = base64_encode($foto_perfil);
                            $imagen_src = "data:image/" . $foto_tipo . ";base64," . $foto_base64;
                            echo "<img src='$imagen_src' alt='Foto de perfil' class='profile-pic'>";
                        }
                        
                        $stmt_foto_perfil->close();
                        $conn->close();
                    ?>
                    <input type="file" id="foto" name="foto" accept="image/*"><br>
                </div>
                <div class="perfil-datos">
                    <?php
                        // Verificar si el usuario ha iniciado sesión
                        if (!isset($_SESSION['username'])) {
                            header("Location: login.php");
                            exit();
                        }
                        $nombre_usuario = $_SESSION['username'];
                        require 'config.php';
                        $sql_datos_usuario = "SELECT nombreCompleto, correo, pronombres, sexo, fecha_nacimiento, nivel_educacion, direccion, telefono, departamento FROM usuarios WHERE nombre = ?";
                        $stmt_datos_usuario = $conn->prepare($sql_datos_usuario);
                        $stmt_datos_usuario->bind_param("s", $nombre_usuario);
                        $stmt_datos_usuario->execute();
                        $result_datos_usuario = $stmt_datos_usuario->get_result();

                        if ($result_datos_usuario->num_rows > 0) {
                            $datos_usuario = $result_datos_usuario->fetch_assoc();
                            foreach ($datos_usuario as $campo => $valor) {
                                echo "<label for='$campo'>$campo:</label><br>";
                                echo "<input type='text' id='$campo' name='$campo' value='$valor' readonly>";
                                echo "<button type='button' onclick='habilitarEdicion(\"$campo\")' class='editarBtn'>Editar</button><br>";
                            }
                        } else {
                            echo "No se encontró ningún usuario con el nombre de usuario proporcionado.";
                        }
                        $stmt_datos_usuario->close();
                        $conn->close();
                    ?>
                </div>
            </div>
            <button type="submit">Actualizar perfil</button>
        </form>
    </div>

    <script>
        function habilitarEdicion(campoId) {
            var campo = document.getElementById(campoId);
            campo.readOnly = false;
            campo.focus();
        }
    </script>
</body>
</html>
