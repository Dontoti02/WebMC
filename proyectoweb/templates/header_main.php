<?php
// Expects $usuario_actual to be available from auth_check.php
// Expects $page_title to be defined in the script including this header.
if (!isset($page_title)) {
    $page_title = 'IE Miguel Cortés';
}
?>
<header class="header">
    <h1><?php echo htmlspecialchars($page_title); ?></h1>
    <div class="user-info">
        <div class="user-avatar">
            <?php echo strtoupper(substr($usuario_actual['nombre_completo'], 0, 1)); ?>
        </div>
        <span><?php echo htmlspecialchars($usuario_actual['nombre_usuario']); ?></span>
    </div>
</header>
