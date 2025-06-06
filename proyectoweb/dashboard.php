<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php"); // Cambiar a login.php, no a dashboard.php
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

// Obtener estadísticas básicas
$total_equipos = 0;
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM equipos_tecnologicos WHERE usuario_registro = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $total_equipos = $row['total'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | IE Miguel Cortés</title>
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
            font-size: 1.8rem;
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

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .welcome-section h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        /* Cards */
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
            margin-bottom: 1rem;
        }

        .card-header h3 {
            color: var(--primary-dark);
            font-size: 1.2rem;
            font-weight: 500;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.primary {
            background-color: var(--primary);
        }

        .stat-icon.info {
            background-color: var(--info);
        }

        .stat-icon.warning {
            background-color: var(--warning);
        }

        .stat-content h4 {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-dark);
            margin-bottom: 0.25rem;
        }

        .stat-content p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .quick-action {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none;
            color: var(--text);
        }

        .quick-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .quick-action i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }

        .quick-action h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--primary-dark);
        }

        .quick-action p {
            font-size: 0.85rem;
            color: var(--text-light);
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
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
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
                <div class="nav-item active">
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
                <h1>Panel de Control</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
                </div>
            </header>

            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2>¡Bienvenido, <?php echo htmlspecialchars($usuario['nombre_completo']); ?>!</h2>
                <p>Gestiona los equipos tecnológicos y las incidencias de la institución educativa desde este panel.</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="stat-content">
                        <h4><?php echo $total_equipos; ?></h4>
                        <p>Equipos Registrados</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h4>0</h4>
                        <p>Incidencias Activas</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h4>0</h4>
                        <p>Pendientes de Revisión</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3>Acciones Rápidas</h3>
                </div>
                
                <div class="quick-actions">
                    <a href="equipos_tecnologicos.php" class="quick-action">
                        <i class="fas fa-laptop"></i>
                        <h4>Registrar Equipo</h4>
                        <p>Agregar un nuevo equipo tecnológico al inventario</p>
                    </a>
                    
                    <a href="nueva_incidencia.php" class="quick-action">
                        <i class="fas fa-plus-circle"></i>
                        <h4>Nueva Incidencia</h4>
                        <p>Reportar un problema o incidente técnico</p>
                    </a>
                    
                    <a href="mis_incidencias.php" class="quick-action">
                        <i class="fas fa-list-alt"></i>
                        <h4>Ver Incidencias</h4>
                        <p>Consultar el estado de tus reportes</p>
                    </a>
                    
                    <a href="gestion.php" class="quick-action">
                        <i class="fas fa-cogs"></i>
                        <h4>Gestión</h4>
                        <p>Administrar equipos e incidencias</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>