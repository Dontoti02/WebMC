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

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_orden = $_POST['numero_orden'];
    $cantidad = $_POST['cantidad'];
    $denominacion = $_POST['denominacion'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $color = $_POST['color'];
    $serie = $_POST['serie'];
    $estado_conservacion = $_POST['estado_conservacion'];
    $observaciones = $_POST['observaciones'];
    
    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO equipos_tecnologicos 
                          (numero_orden, cantidad, denominacion, marca, modelo, color, serie, estado_conservacion, observaciones, usuario_registro) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssssssi", $numero_orden, $cantidad, $denominacion, $marca, $modelo, $color, $serie, $estado_conservacion, $observaciones, $usuario_id);
    
    if ($stmt->execute()) {
        $mensaje_exito = "Equipo registrado correctamente";
    } else {
        $mensaje_error = "Error al registrar el equipo: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Equipos | IE Miguel Cortés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            color: var(--primary-dark);
            font-size: 1.25rem;
            font-weight: 500;
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
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>IE Miguel Cortés</h2>
                <p style="font-size: 0.85rem; opacity: 0.8;">Sistema de Gestión</p>
            </div>

            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </div>
                <div class="nav-item active">
                    <a href="equipos_tecnologicos.php">
                        <i class="fas fa-laptop"></i>
                        <span>Registro de Equipos</span>
                    </a>
                </div>
                <div class="nav-item">
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
                <h1>Registro de Equipos Tecnológicos</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
                </div>
            </header>

            <!-- Mensajes de éxito/error -->
            <?php if (isset($mensaje_exito)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $mensaje_exito; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($mensaje_error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $mensaje_error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <div class="card">
                <div class="card-header">
                    <h2>Datos del Equipo Tecnológico</h2>
                    <a href="dashboard.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Volver al Inicio
                    </a>
                </div>
                
                <form action="equipos_tecnologicos.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="numero_orden">N° de Orden</label>
                            <input type="text" id="numero_orden" name="numero_orden" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" id="cantidad" name="cantidad" class="form-control" required min="1">
                        </div>
                        
                        <div class="form-group">
                            <label for="denominacion">Denominación</label>
                            <input type="text" id="denominacion" name="denominacion" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" id="marca" name="marca" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input type="text" id="modelo" name="modelo" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="text" id="color" name="color" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="serie">Número de Serie</label>
                            <input type="text" id="serie" name="serie" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado_conservacion">Estado de Conservación</label>
                            <select id="estado_conservacion" name="estado_conservacion" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="excelente">Excelente</option>
                                <option value="bueno">Bueno</option>
                                <option value="regular">Regular</option>
                                <option value="malo">Malo</option>
                                <option value="pésimo">Pésimo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" placeholder="Ingrese cualquier observación adicional sobre el equipo..."></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <a href="dashboard.php" class="btn btn-outline">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn">
                            <i class="fas fa-save"></i> Guardar Equipo
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>