<!-- menu.php -->
<div class="status-circle"></div>
<div class="sidebar">
    <a href="UIdocente.php"><h2>Menú Docente</h2></a>
    <ul>
            <li><a href="perfil_usuario.php">Perfil</a></li>
            <li><a href="gestionar_cursos.php">Gestionar Cursos</a></li>
            <li><a href="gestionar_tareas.php">Gestionar Tareas</a></li>
            <li><a href="enviar_mensajes.php">Enviar Mensajes</a></li>
            <li><a href="logout.php" class="logout-btn">Cerrar Sesión</a></li>
    </ul>
</div>

<style>
    .sidebar {
        background-color: #1a535c; /* Color del menú lateral */
        color: white; /* Color del texto en blanco */
        width: 13%; /* Ancho del menú lateral */
        height: 100vh; /* Altura completa de la ventana */
        padding-top: 20px;
        position: fixed; /* Fijar el menú en su posición */
        left: 0; /* Alinear el menú en la esquina izquierda */
        top: 0; /* Alinear el menú en la esquina superior */
        overflow-y: auto; /* Añadir desplazamiento vertical si es necesario */
        z-index: 1000; /* Asegurar que el menú esté por encima de otros elementos */
        text-align: center; /* Centrar el texto dentro del menú */
    }

    .sidebar h2 {
        margin-bottom: 30px; /* Espacio inferior entre el título y los elementos de la lista */
        color: white; /* Color del texto en blanco */
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 15px;
        text-align: center; /* Centrar el texto dentro de cada elemento de la lista */
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s;
    }

    .sidebar ul li a:hover {
        background-color: #4e5d6c; /* Color de fondo al pasar el cursor */
    }

    .content {
        margin-left: 250px; /* Dejar espacio para el menú lateral */
        padding: 20px;
        width: calc(100% - 250px);
        height: 100%;
        overflow-y: auto;
        position: relative; /* Para que el estado del círculo se posicione correctamente */
    }

    .content h1 {
        color: #1a535c; /* Color del texto */
        margin-bottom: 20px;
    }

    .content p {
        color: #4e5d6c;
    }

</style>
