<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

$usuario_id = $_SESSION['usuario_id'];

// Obtener nombre del usuario
$stmt = $conn->prepare("SELECT nombre_completo, nombre_usuario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Obtener las incidencias reportadas por el usuario
$incidencias = [];
$stmt = $conn->prepare("SELECT i.id, i.titulo, i.categoria, i.prioridad, i.estado, i.fecha_creacion, e.denominacion 
                        FROM incidencias i 
                        LEFT JOIN equipos_tecnologicos e ON i.equipo_id = e.id 
                        WHERE i.usuario_id = ? 
                        ORDER BY i.fecha_creacion DESC");
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
    <title>Mis Incidencias | IE Miguel Cortés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-dark: #1b5e20;
            --bg: #f5f9f6;
            --card-bg: #fff;
            --border: #e0e0e0;
            --text: #333;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            font-family: 'Segoe UI', sans-serif;
            color: var(--text);
        }

        .container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

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

        .logo h2 { font-size: 1.1rem; }

        .nav-menu {
            display: flex;
            flex-direction: column;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
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
        }

        .main-content {
            grid-column: 2;
            padding: 2rem;
            margin-left: 240px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
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
        }

        .card {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background-color: var(--primary);
            color: white;
        }

        table th, table td {
            padding: 0.75rem;
            border: 1px solid var(--border);
            text-align: left;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .estado-Pendiente {
            color: #ff9800;
            font-weight: bold;
        }

        .estado-Resuelto {
            color: #4caf50;
            font-weight: bold;
        }

        .estado-En\ Proceso {
            color: #2196f3;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
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
            <div class="nav-item"><a href="dashboard.php"><i class="fas fa-home"></i>Inicio</a></div>
            <div class="nav-item"><a href="equipos_tecnologicos.php"><i class="fas fa-laptop"></i>Registro de Equipos</a></div>
            <div class="nav-item"><a href="nueva_incidencia.php"><i class="fas fa-plus"></i>Nueva Incidencia</a></div>
            <div class="nav-item active"><a href="mis_incidencias.php"><i class="fas fa-list"></i>Mis Incidencias</a></div>
            <div class="nav-item"><a href="gestion.php"><i class="fas fa-tasks"></i>Gestión</a></div>
            <div class="nav-item"><a href="perfil.php"><i class="fas fa-user"></i>Perfil</a></div>
            <div class="nav-item"><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Cerrar Sesión</a></div>
        </nav>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <header class="header">
            <h1>Mis Incidencias</h1>
            <div class="user-info">
                <div class="user-avatar"><?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?></div>
                <span><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
            </div>
        </header>

        <div class="card">
            <h2>Historial de Incidencias</h2>

            <?php if (empty($incidencias)): ?>
                <p>No ha reportado ninguna incidencia todavía.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Equipo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incidencias as $i => $inc): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($inc['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($inc['categoria']); ?></td>
                                <td><?php echo htmlspecialchars($inc['prioridad']); ?></td>
                                <td class="estado-<?php echo str_replace(' ', '\\ ', $inc['estado']); ?>">
                                    <?php echo htmlspecialchars($inc['estado']); ?>
                                </td>
                                <td><?php echo $inc['denominacion'] ?? 'N/A'; ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($inc['fecha_creacion'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
