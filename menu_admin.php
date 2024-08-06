<!-- menu_admin.php -->
<div class="status-circle"></div>
<div class="sidebar">
    <a href="UIadmin.php"><h2>Admin Centro de Salud</h2></a>
    <ul>
        <li><a href="agregar_usuarios.php">Agregar Usuarios</a></li>
        <li><a href="agregar_especialidades.php">Agregar Especialidades</a></li>
        <li><a href="agregar_cursos.php">Agregar Cursos</a></li>
        <li><a href="matricular_alumnos.php">Matricular Alumnos</a></li>
    </ul>
    <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
</div>

<style>
    .sidebar {
        background-color: #1a535c;
        color: white;
        width: 13%;
        height: 100vh;
        padding-top: 20px;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        z-index: 1000;
        text-align: center;
    }

    .sidebar h2 {
        margin-bottom: 30px;
        color: white;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 15px;
        text-align: center;
    }

    .sidebar ul li a {
        color: white;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s;
    }

    .sidebar ul li a:hover {
        background-color: #4e5d6c;
    }

    .content {
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
        height: 100%;
        overflow-y: auto;
        position: relative;
    }

    .content h1 {
        color: #1a535c;
        margin-bottom: 20px;
    }

    .content p {
        color: #4e5d6c;
    }

    .logout-btn {
        position: absolute;
        bottom: 20px;
        left: 10px;
        background-color: #ff4d4d;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .logout-btn:hover {
        background-color: #e60000;
    }
</style>
