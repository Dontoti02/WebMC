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

// Obtener equipos registrados por el usuario
$equipos = [];
$stmt = $conn->prepare("SELECT id, denominacion FROM equipos_tecnologicos WHERE usuario_registro = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $equipos[] = $row;
}
$stmt->close();

// Obtener incidencias registradas por el usuario
$incidencias = [];
$stmt = $conn->prepare("SELECT id, titulo FROM incidencias WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $incidencias[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión | IE Miguel Cortés</title>
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
            margin-left: 240px;
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
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border);
        }

        .card-header h3 {
            color: var(--primary-dark);
            font-size: 1.2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Checklist Styles */
        .checklist {
            list-style: none;
            padding: 0;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 0.75rem;
            background-color: #fafafa;
            transition: all 0.2s ease;
        }

        .checklist-item:hover {
            background-color: #f0f8f0;
            border-color: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(46, 125, 50, 0.1);
        }

        .checklist-content {
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checklist-text {
            font-weight: 500;
            color: var(--text);
        }

        .checklist-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 0.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
        }

        .edit-btn {
            background-color: var(--info);
            color: white;
        }

        .edit-btn:hover {
            background-color: #1976d2;
            transform: scale(1.05);
        }

        .delete-btn {
            background-color: var(--danger);
            color: white;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
            transform: scale(1.05);
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border);
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
                margin-left: 0;
            }

            .checklist-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .checklist-content {
                width: 100%;
            }

            .checklist-actions {
                align-self: flex-end;
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
                <div class="nav-item active">
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
            <header class="header">
                <h1>Gestión de Equipos e Incidencias</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                    </div>
                    <span><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
                </div>
            </header>

            <!-- Equipos Tecnológicos -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-laptop"></i>
                        Gestionar Equipos Tecnológicos
                    </h3>
                </div>
                
                <?php if (empty($equipos)): ?>
                    <div class="empty-state">
                        <i class="fas fa-laptop"></i>
                        <p>No tienes equipos registrados</p>
                        <small>Registra equipos para poder gestionarlos aquí</small>
                    </div>
                <?php else: ?>
                    <ul class="checklist">
                        <?php foreach ($equipos as $equipo): ?>
                            <li class="checklist-item">
                                <div class="checklist-content">
                                    <i class="fas fa-desktop checklist-icon"></i>
                                    <span class="checklist-text"><?php echo htmlspecialchars($equipo['denominacion']); ?></span>
                                </div>
                                <div class="checklist-actions">
                                    <form action="procesar_gestion.php" method="post" style="display: inline;">
                                        <input type="hidden" name="equipo" value="<?php echo $equipo['id']; ?>">
                                        <button type="submit" name="accion" value="editar_equipo" class="action-btn edit-btn" title="Editar equipo">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </form>
                                    <form action="procesar_gestion.php" method="post" style="display: inline;" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este equipo?');">
                                        <input type="hidden" name="equipo" value="<?php echo $equipo['id']; ?>">
                                        <button type="submit" name="accion" value="eliminar_equipo" class="action-btn delete-btn" title="Eliminar equipo">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Incidencias -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-exclamation-triangle"></i>
                        Gestionar Incidencias
                    </h3>
                </div>
                
                <?php if (empty($incidencias)): ?>
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>No tienes incidencias registradas</p>
                        <small>Registra incidencias para poder gestionarlas aquí</small>
                    </div>
                <?php else: ?>
                    <ul class="checklist">
                        <?php foreach ($incidencias as $incidencia): ?>
                            <li class="checklist-item">
                                <div class="checklist-content">
                                    <i class="fas fa-bug checklist-icon"></i>
                                    <span class="checklist-text"><?php echo htmlspecialchars($incidencia['titulo']); ?></span>
                                </div>
                                <div class="checklist-actions">
                                    <form action="procesar_gestion.php" method="post" style="display: inline;">
                                        <input type="hidden" name="incidencia" value="<?php echo $incidencia['id']; ?>">
                                        <button type="submit" name="accion" value="editar_incidencia" class="action-btn edit-btn" title="Editar incidencia">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </form>
                                    <form action="procesar_gestion.php" method="post" style="display: inline;" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta incidencia?');">
                                        <input type="hidden" name="incidencia" value="<?php echo $incidencia['id']; ?>">
                                        <button type="submit" name="accion" value="eliminar_incidencia" class="action-btn delete-btn" title="Eliminar incidencia">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>