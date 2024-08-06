<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar y ejecutar la consulta SQL para verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE nombre = ? AND contrasena = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verificar si el usuario ya tiene una sesión activa
        if ($user['status'] === 'loggedOn') {
            echo 'Usuario ya conectado en otro dispositivo o navegador. No es posible iniciar sesión.';
        } else {
            // Actualizar el estado del usuario a loggedOn en la base de datos
            $updateSql = "UPDATE usuarios SET status = 'loggedOn' WHERE nombre = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("s", $username);
            $updateStmt->execute();
            $updateStmt->close();

            // Almacenar el nombre de usuario en la sesión
            $_SESSION['username'] = $username;
            $_SESSION['tipo_usuario'] = $user['tipo'];

            // Redirigir según el tipo de usuario
            switch ($user['tipo']) {
                case 'admin':
                    header('Location: UIadmin.php');
                    exit();
                case 'alumno':
                    header('Location: UIalumno.php');
                    exit();
                case 'docente':
                    header('Location: UIdocente.php');
                    exit();
                default:
                    echo 'Tipo de usuario desconocido.';
                    break;
            }
        }
    } else {
        echo 'Nombre de usuario o contraseña incorrectos.';
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Redirigir al formulario de inicio de sesión si se accede directamente a login.php
    header('Location: index.php');
    exit();
}
?>
