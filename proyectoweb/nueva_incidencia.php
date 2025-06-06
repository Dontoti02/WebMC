<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Obtener el nombre del usuario
$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT nombre_completo, nombre_usuario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Verificar que el usuario existe
if (!$usuario) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Obtener lista de equipos para el select
$equipos = [];
$stmt = $conn->prepare("SELECT id, CONCAT(denominacion, ' - ', marca, ' ', modelo) as equipo_info FROM equipos_tecnologicos ORDER BY denominacion");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $equipos[] = $row;
}
$stmt->close();

// Variables para mensajes
$mensaje_exito = '';
$mensaje_error = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos recibidos
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $prioridad = $_POST['prioridad'];
    $categoria = $_POST['categoria'];
    $equipo_id = !empty($_POST['equipo_id']) ? $_POST['equipo_id'] : null;
    $ubicacion = trim($_POST['ubicacion']);
    
    // Validaciones básicas
    $errores = [];
    
    if (empty($titulo)) {
        $errores[] = "El título es obligatorio";
    }
    
    if (empty($descripcion)) {
        $errores[] = "La descripción es obligatoria";
    }
    
    if (empty($prioridad) || !in_array($prioridad, ['Baja', 'Media', 'Alta'])) {
        $errores[] = "Debe seleccionar una prioridad válida";
    }
    
    if (empty($categoria)) {
        $errores[] = "Debe seleccionar una categoría";
    }
    
    if (empty($ubicacion)) {
        $errores[] = "La ubicación es obligatoria";
    }
    
    // Si hay errores, mostrarlos
    if (!empty($errores)) {
        $mensaje_error = "Errores encontrados:<br>" . implode("<br>", $errores);
    } else {
        // Insertar en la base de datos
        try {
            $stmt = $conn->prepare("INSERT INTO incidencias 
                                  (titulo, descripcion, prioridad, categoria, equipo_id, ubicacion, usuario_id, estado, fecha_creacion) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente', NOW())");
            
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("ssssisi", $titulo, $descripcion, $prioridad, $categoria, $equipo_id, $ubicacion, $usuario_id);
            
            if ($stmt->execute()) {
                $incidencia_id = $conn->insert_id;
                $mensaje_exito = "¡Incidencia reportada correctamente! Número de seguimiento: #" . str_pad($incidencia_id, 6, '0', STR_PAD_LEFT);
                
                // Limpiar el formulario después del éxito
                $_POST = array();
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $mensaje_error = "Error al reportar la incidencia: " . $e->getMessage();
            error_log("Error en nueva_incidencia.php: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Incidencia | IE Miguel Cortés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Mantener los mismos estilos del archivo original */
        :root {
            --primary: #2e7d32;
            --primary-light: #4caf50;
            --primary-dark: #1b5e20;
            --text: #333;
            --text-light: #666;
            --bg: #f5f9f6;
            --card-bg: #fff;
            --border: #e0e0e0;
            --success: #4caf50;
            --warning: #ff9800;
            --danger: #f44336;
            --info: #2196f3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--primary-dark);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            width: 240px;
            height: 100vh;
        }

        .logo {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }

        .logo h2 {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .nav-item.active {
            background-color: var(--primary);
            border-left: 3px solid white;
        }

        .nav-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }

        /* Main Content */
        .main-content {
            grid-column: 2;
            padding: 2rem;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: var(--primary-dark);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Card */
        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            color: var(--primary-dark);
            font-size: 1.25rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .card-header p {
            color: var(--text-light);
            margin: 0;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.25rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary-light);
            color: white;
        }

        /* Alertas */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f1aeb5;
        }

        /* Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        /* Priority indicator */
        .priority-indicator {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .priority-baja {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .priority-media {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .priority-alta {
            background-color: #ffebee;
            color: #d32f2f;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                grid-column: 1;
                padding: 1.5rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column-reverse;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>IE Miguel Cortés</h2>
                <p>Sistema de Gestión</p>
            </div>

            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="equipos_tecnologicos.php">
                        <i class="fas fa-laptop"></i>
                        <span>Registro de Equipos</span>
                    </a>
                </div>
                <div class="nav-item active">
                    <a href="nueva_incidencia.php">
                        <i class="fas fa-plus"></i>
                        <span>Nueva Incidencia</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="mis_incidencias.php">
                        <i class="fas fa-list"></i>
                        <span>Mis Incidencias</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="gestion.php">
                        <i class="fas fa-tasks"></i>
                        <span>Gestión</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="perfil.php">
                        <i class="fas fa-user"></i>
                        <span>Perfil</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>Nueva Incidencia</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
                </div>
            </header>

            <!-- Mensajes -->
            <?php if (!empty($mensaje_exito)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $mensaje_exito; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensaje_error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $mensaje_error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="card">
                <div class="card-header">
                    <h2>Reportar Incidencia</h2>
                    <p>Complete la información para reportar un problema técnico</p>
                </div>

                <div class="card-body">
                    <form action="nueva_incidencia.php" method="POST">
                        <div class="form-group">
                            <label for="titulo">Título de la Incidencia *</label>
                            <input type="text" id="titulo" name="titulo" class="form-control" 
                                   placeholder="Resuma brevemente el problema" 
                                   value="<?php echo isset($_POST['titulo']) ? htmlspecialchars($_POST['titulo']) : ''; ?>" 
                                   required maxlength="255">
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="categoria">Categoría *</label>
                                <select id="categoria" name="categoria" class="form-control" required>
                                    <option value="">Seleccione una categoría</option>
                                    <option value="Hardware" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Hardware') ? 'selected' : ''; ?>>Hardware</option>
                                    <option value="Software" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Software') ? 'selected' : ''; ?>>Software</option>
                                    <option value="Red/Internet" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Red/Internet') ? 'selected' : ''; ?>>Red/Internet</option>
                                    <option value="Impresora" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Impresora') ? 'selected' : ''; ?>>Impresora</option>
                                    <option value="Proyector" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Proyector') ? 'selected' : ''; ?>>Proyector</option>
                                    <option value="Audio/Video" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Audio/Video') ? 'selected' : ''; ?>>Audio/Video</option>
                                    <option value="Otro" <?php echo (isset($_POST['categoria']) && $_POST['categoria'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="prioridad">Prioridad *</label>
                                <select id="prioridad" name="prioridad" class="form-control" required>
                                    <option value="">Seleccione prioridad</option>
                                    <option value="Baja" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'Baja') ? 'selected' : ''; ?>>Baja</option>
                                    <option value="Media" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'Media') ? 'selected' : ''; ?>>Media</option>
                                    <option value="Alta" <?php echo (isset($_POST['prioridad']) && $_POST['prioridad'] == 'Alta') ? 'selected' : ''; ?>>Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="equipo_id">Equipo Relacionado</label>
                                <select id="equipo_id" name="equipo_id" class="form-control">
                                    <option value="">No relacionado con equipo específico</option>
                                    <?php foreach ($equipos as $equipo): ?>
                                        <option value="<?php echo $equipo['id']; ?>" 
                                                <?php echo (isset($_POST['equipo_id']) && $_POST['equipo_id'] == $equipo['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($equipo['equipo_info']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ubicacion">Ubicación *</label>
                                <input type="text" id="ubicacion" name="ubicacion" class="form-control" 
                                       placeholder="Ej: Aula 201, Sala de profesores, etc." 
                                       value="<?php echo isset($_POST['ubicacion']) ? htmlspecialchars($_POST['ubicacion']) : ''; ?>" 
                                       required maxlength="255">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción Detallada *</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" 
                                      placeholder="Describa el problema con el mayor detalle posible. Incluya pasos para reproducir el error, mensajes de error específicos, y cualquier información relevante." 
                                      required><?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?></textarea>
                        </div>

                        <div class="form-actions">
                            <a href="dashboard.php" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn">
                                <i class="fas fa-paper-plane"></i>
                                Reportar Incidencia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mostrar indicador de prioridad
        document.getElementById('prioridad').addEventListener('change', function() {
            const value = this.value;
            const existing = document.querySelector('.priority-indicator');
            if (existing) existing.remove();

            if (value) {
                const indicator = document.createElement('div');
                indicator.className = `priority-indicator priority-${value.toLowerCase()}`;
                indicator.innerHTML = `<i class="fas fa-flag"></i> Prioridad ${value}`;
                this.parentNode.appendChild(indicator);
            }
        });

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const categoria = document.getElementById('categoria').value;
            const prioridad = document.getElementById('prioridad').value;
            const ubicacion = document.getElementById('ubicacion').value.trim();

            let errores = [];

            if (!titulo) errores.push('El título es obligatorio');
            if (!descripcion) errores.push('La descripción es obligatoria');
            if (!categoria) errores.push('Debe seleccionar una categoría');
            if (!prioridad) errores.push('Debe seleccionar una prioridad');
            if (!ubicacion) errores.push('La ubicación es obligatoria');

            if (errores.length > 0) {
                e.preventDefault();
                alert('Errores encontrados:\n' + errores.join('\n'));
                return false;
            }
        });

        // Auto-guardar borrador (opcional)
        function guardarBorrador() {
            const formData = {
                titulo: document.getElementById('titulo').value,
                descripcion: document.getElementById('descripcion').value,
                categoria: document.getElementById('categoria').value,
                prioridad: document.getElementById('prioridad').value,
                equipo_id: document.getElementById('equipo_id').value,
                ubicacion: document.getElementById('ubicacion').value
            };
            
            // Guardar en localStorage del navegador
            localStorage.setItem('borrador_incidencia', JSON.stringify(formData));
        }

        // Cargar borrador si existe
        function cargarBorrador() {
            const borrador = localStorage.getItem('borrador_incidencia');
            if (borrador) {
                const data = JSON.parse(borrador);
                if (confirm('Se encontró un borrador guardado. ¿Desea cargarlo?')) {
                    document.getElementById('titulo').value = data.titulo || '';
                    document.getElementById('descripcion').value = data.descripcion || '';
                    document.getElementById('categoria').value = data.categoria || '';
                    document.getElementById('prioridad').value = data.prioridad || '';
                    document.getElementById('equipo_id').value = data.equipo_id || '';
                    document.getElementById('ubicacion').value = data.ubicacion || '';
                }
            }
        }

        // Limpiar borrador después de envío exitoso
        <?php if (!empty($mensaje_exito)): ?>
        localStorage.removeItem('borrador_incidencia');
        <?php endif; ?>

        // Eventos para auto-guardar
        document.addEventListener('DOMContentLoaded', function() {
            cargarBorrador();
            
            // Auto-guardar cada 30 segundos
            setInterval(guardarBorrador, 30000);
            
            // Guardar al salir de cada campo
            const campos = ['titulo', 'descripcion', 'categoria', 'prioridad', 'equipo_id', 'ubicacion'];
            campos.forEach(campo => {
                document.getElementById(campo).addEventListener('blur', guardarBorrador);
            });
        });
    </script>
</body>
</html>