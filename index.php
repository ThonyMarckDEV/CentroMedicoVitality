<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index_style.css">
</head>
<body>
    <div class="background-image"></div>
    <div class="container">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <form action="login.php" method="post">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <div id="password-message" style="color: red; font-size: 12px;"></div>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
