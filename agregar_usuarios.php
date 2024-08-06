<?php
session_start();
include 'menu_admin.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuarios</title>
    <link rel="stylesheet" href="agregar_usuario_styles.css">
</head>
<body>
    <div class="container">
        <h2>Agregar Usuarios</h2>
        <?php
        // Mostrar mensaje de error si el usuario ya existe
        if (isset($_GET['error']) && $_GET['error'] == 'exists') {
            echo "<div class='error-message'>El nombre de usuario ya existe. Por favor, elija otro nombre.</div><br>";
        }
        ?>
        <form id="userForm" action="procesar_agregar_usuario.php" method="POST">
            <label for="nombre">Nombre de Usuario:</label>
            <input type="text" id="nombre" name="nombre" required minlength="7"><br>
            <div id="nombreMessage" class="validation-message"></div><br>
            
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required minlength="8"><br>
            <div id="passwordMessage" class="validation-message"></div><br>

            <label for="especialidad">Especialidad:</label>
            <select id="especialidad" name="especialidad" required>
                <?php
                require 'config.php';

                $sql = "SELECT nombre FROM especialidades";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['nombre'] . "'>" . $row['nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay especialidades disponibles</option>";
                }

                $conn->close();
                ?>
            </select><br><br>

            <label for="tipo">Tipo de Usuario:</label>
            <select id="tipo" name="tipo">
                <option value="admin">Admin</option>
                <option value="alumno">Alumno</option>
                <option value="docente">Docente</option>
            </select><br><br>

            <button type="button" onclick="validateForm()">Agregar Usuario</button>
        </form>
    </div>
    <script>
        function validateForm() {
            var nombre = document.getElementById("nombre").value;
            var contrasena = document.getElementById("contrasena").value;
            var nombreMessage = document.getElementById("nombreMessage");
            var passwordMessage = document.getElementById("passwordMessage");
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            
            nombreMessage.textContent = "";
            passwordMessage.textContent = "";

            if (nombre.length < 7) {
                nombreMessage.textContent = "El nombre de usuario debe tener al menos 7 caracteres.";
            }
            
            if (contrasena.length < 8 || !regex.test(contrasena)) {
                passwordMessage.textContent = "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, un número y un símbolo especial.";
            }
            
            if (nombre.length >= 7 && contrasena.length >= 8 && regex.test(contrasena)) {
                document.getElementById("userForm").submit();
            }
        }
    </script>
</body>
</html>
