<?php
// Expects $usuario_actual to be available from auth_check.php
// Expects $active_page to be defined in the script including this sidebar.
if (!isset($active_page)) {
    $active_page = ''; // Default to no active page
}
?>
<aside class="sidebar">
    <div class="logo">
        <h2>IE Miguel Cortés</h2>
        <p style="font-size: 0.85rem; opacity: 0.8;">Sistema de Gestión</p>
    </div>

    <nav class="nav-menu">
        <div class="nav-item <?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
        </div>
        <div class="nav-item <?php echo ($active_page == 'equipos') ? 'active' : ''; ?>">
            <a href="equipos_tecnologicos.php">
                <i class="fas fa-laptop"></i>
                <span>Registro de Equipos</span>
            </a>
        </div>
        <div class="nav-item <?php echo ($active_page == 'nueva_incidencia') ? 'active' : ''; ?>">
            <a href="nueva_incidencia.php">
                <i class="fas fa-plus"></i>
                <span>Nueva Incidencia</span>
            </a>
        </div>
        <div class="nav-item <?php echo ($active_page == 'mis_incidencias') ? 'active' : ''; ?>">
            <a href="mis_incidencias.php">
                <i class="fas fa-list"></i>
                <span>Mis Incidencias</span>
            </a>
        </div>
        <?php if (isset($usuario_actual['rol']) && $usuario_actual['rol'] == 'administrador'): ?>
        <div class="nav-item <?php echo ($active_page == 'gestion') ? 'active' : ''; ?>">
            <a href="gestion.php">
                <i class="fas fa-tasks"></i>
                <span>Gestión</span>
            </a>
        </div>
        <?php endif; ?>
        <div class="nav-item <?php echo ($active_page == 'perfil') ? 'active' : ''; ?>">
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
