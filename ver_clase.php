<?php
session_start();

// Incluir el archivo de configuración
require_once 'config.php';

// Verificar si existe una sesión activa
if (!isset($_SESSION['username'])) {
    // Si no hay sesión activa, redirigir al formulario de inicio de sesión
    header('Location: login.php');
    exit();
}

// Obtener el nombre de usuario del alumno que inició sesión
$username = $_SESSION['username'];

// Consulta SQL para obtener la id del usuario por nombre
$sql = "SELECT idUsuario FROM usuarios WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Obtener el ID del usuario
if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    $usuario_id = $usuario['idUsuario'];
} else {
    // Manejar el caso en el que no se encuentre el usuario
    echo 'Error: No se pudo encontrar el usuario.';
    exit();
}

// Verificar si se proporcionó un ID de clase en la URL
if (isset($_GET['clase_id'])) {
    // Obtener el ID de la clase de la URL
    $clase_id = $_GET['clase_id'];

    // Consulta para obtener las actividades relacionadas con la clase
    $sql_actividades = "SELECT * FROM actividades WHERE id_clase = ?";
    $stmt_actividades = $conn->prepare($sql_actividades);
    $stmt_actividades->bind_param("i", $clase_id);
    $stmt_actividades->execute();
    $result_actividades = $stmt_actividades->get_result();

    if ($result_actividades->num_rows > 0) {
        // Preparar un array para almacenar las actividades
        $actividades = array();
        while ($row = $result_actividades->fetch_assoc()) {
            $actividades[] = $row;
        }
    } else {
        echo "No hay actividades para esta clase.";
    }

    // Consulta para obtener los archivos relacionados con la clase
    $sql_archivos = "SELECT * FROM archivos WHERE id_clase = ?";
    $stmt_archivos = $conn->prepare($sql_archivos);
    $stmt_archivos->bind_param("i", $clase_id);
    $stmt_archivos->execute();
    $result_archivos = $stmt_archivos->get_result();

    if ($result_archivos->num_rows > 0) {
        // Preparar un array para almacenar los archivos
        $archivos = array();
        while ($row = $result_archivos->fetch_assoc()) {
            $archivos[] = $row;
        }
    } else {
        echo "No hay archivos para esta clase.";
    }

    // Consulta para obtener las tareas revisadas del alumno para las actividades de la clase
    $sql_tareas_revisadas = "SELECT * FROM tareas_revisadas WHERE idUsuario = ? AND idActividad IN (SELECT idActividad FROM actividades WHERE id_clase = ?)";
    $stmt_tareas_revisadas = $conn->prepare($sql_tareas_revisadas);
    $stmt_tareas_revisadas->bind_param("ii", $usuario_id, $clase_id);
    $stmt_tareas_revisadas->execute();
    $result_tareas_revisadas = $stmt_tareas_revisadas->get_result();

    // Preparar un array para almacenar las tareas revisadas
    $tareas_revisadas = array();
    while ($row = $result_tareas_revisadas->fetch_assoc()) {
        $tareas_revisadas[$row['idActividad']] = $row;
    }

    // Validar si hay actividades y si la fecha de entrega no ha pasado
    $actividades_validas = array();
    $hoy = date("Y-m-d");

    foreach ($actividades as $actividad) {
        if ($actividad["fecha"] >= $hoy) {
            $actividades_validas[] = $actividad;
        }
    }

    $permitir_subida = !empty($actividades_validas);

} else {
    // Manejar el caso en el que no se reciba el parámetro clase_id
    echo 'Error: Falta el parámetro clase_id.';
    exit();
}
?>

<?php 
include 'menu_alumno.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Clase</title>
    <link rel="stylesheet" href="ver_clase_styles.css">
</head>
<body>
    
    <!-- Mostrar actividades -->
    <div class="container">
        <h2>Clase</h2>

        <div class="container">
            <h2>Detalles de la Clase</h2>
            <div class="row">
                <!-- Columna izquierda para actividades -->
                <div class="left-column">
                    <div class="details">
                        <h3>Actividades:</h3>
                        <?php
                        if (!empty($actividades)) {
                            foreach ($actividades as $actividad) {
                                echo "<div class='activity'>";
                                echo "<strong>Título:</strong> " . htmlspecialchars($actividad["titulo"]) . "<br>";
                                echo "<strong>Descripción:</strong> " . htmlspecialchars($actividad["descripcion"]) . "<br>";
                                echo "<strong>Fecha Entrega:</strong> " . htmlspecialchars($actividad["fecha"]) . "<br>";
                                echo "</div>";
                            }
                        } else {
                            echo "No hay actividades para esta clase.";
                        }
                        ?>
                    </div>
                </div>

                <!-- Columna derecha para el nuevo recuadro -->
                <div class="right-column">
                    <div class="details">
                        <h3>Estado:</h3>
                        <p>
                            <?php
                            if (!empty($actividades)) {
                                foreach ($actividades as $actividad) {
                                    $actividad_id = $actividad["idActividad"];
                                    $nota = isset($tareas_revisadas[$actividad_id]) ? $tareas_revisadas[$actividad_id]['nota'] : 'No calificado';
                                    echo "<div class='nota'><strong>Actividad:</strong> " . htmlspecialchars($actividad["titulo"]) . "<br>";
                                    echo "<strong>Nota:</strong> " . htmlspecialchars($nota) . "<br></div>";
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar archivos -->
        <div class="details">
            <h3>Archivos:</h3>
            <?php
            if (!empty($archivos)) {
                foreach ($archivos as $archivo) {
                    $archivo_nombre = htmlspecialchars($archivo["nombre"]);
                    $archivo_contenido = $archivo["contenido"];
                    echo "<div class='file'>";
                    echo "<button><a href='data:application/octet-stream;base64," . base64_encode($archivo_contenido) . "' download='$archivo_nombre'>Descargar</a></button><br>";

                    // Mostrar el contenido del archivo según su tipo
                    if (strpos($archivo_nombre, '.jpg') !== false || strpos($archivo_nombre, '.jpeg') !== false || strpos($archivo_nombre, '.png') !== false || strpos($archivo_nombre, '.gif') !== false) {
                        echo "<img src='data:image/jpeg;base64," . base64_encode($archivo_contenido) . "' alt='$archivo_nombre' style='max-width: 700px;'><br>";
                    } elseif (strpos($archivo_nombre, '.mp4') !== false || strpos($archivo_nombre, '.webm') !== false || strpos($archivo_nombre, '.ogg') !== false) {
                        echo "<video controls>";
                        echo "<source src='data:video/mp4;base64," . base64_encode($archivo_contenido) . "' type='video/mp4'>";
                        echo "Tu navegador no soporta el elemento de video.";
                        echo "</video><br>";
                    } elseif (strpos($archivo_nombre, '.pdf') !== false) {
                        echo "<iframe src='data:application/pdf;base64," . base64_encode($archivo_contenido) . "' width='100%' height='400'></iframe><br>";
                    } elseif (strpos($archivo_nombre, '.mp3') !== false) {
                        echo "<audio controls>";
                        echo "<source src='data:audio/mp3;base64," . base64_encode($archivo_contenido) . "' type='audio/mp3'>";
                        echo "Tu navegador no soporta el elemento de audio.";
                        echo "</audio><br>";
                    } else {
                        echo "Archivo no compatible: $archivo_nombre";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "No hay archivos para esta clase.";
            }
            ?>
        </div>

        <!-- Sección para subir tarea -->
        <div class="details">
            <h3>Subir Tarea:</h3>
            <?php if ($permitir_subida): ?>
            <form action="procesar_subida_tarea.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
                <select id="actividad_id" name="actividad_id">
                    <?php
                    foreach ($actividades_validas as $actividad) {
                        echo "<option value='" . htmlspecialchars($actividad["idActividad"]) . "'>" . htmlspecialchars($actividad["titulo"]) . "</option>";
                    }
                    ?>
                </select><br><br>
                <label for="archivo">Seleccionar archivo:</label>
                <input type="file" id="archivo" name="archivo" required><br><br>
                <button type="submit">Subir Tarea</button>
            </form>
            <?php else: ?>
            <p>No hay actividades disponibles para subir tareas o la fecha de entrega ha pasado.</p>
            <?php endif; ?>
        </div>

        <!-- Script para mostrar y eliminar tareas -->
        <script src="ver_clase_script.js"></script>
    </div>
</body>
</html>

<?php
// Cerrar conexión al final del script
$conn->close();
?>
