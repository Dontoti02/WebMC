<?php
session_start();

// Mostrar mensaje de cierre de sesión exitoso
if (isset($_GET['logout'])) {
    $_SESSION['success'] = "Has cerrado sesión correctamente";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | IE Miguel Cortés del Castillo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles_login.css">
    <style>
        /* Estilo adicional para mensajes de error */
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .success-message {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h2>I.E Miguel Cortés del Castillo</h2>
        <p>Ingrese sus credenciales para acceder al sistema</p>
        
        <?php
        // Mostrar mensajes de error/success
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        
        <form action="validacion_login.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="username" name="username" class="form-control" 
                       placeholder="Ingrese su usuario" required
                       value="<?php echo isset($_SESSION['login_username']) ? htmlspecialchars($_SESSION['login_username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="password" name="password" class="form-control" 
                       placeholder="Ingrese su contraseña" required>
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>
        
        <div class="links">
            <a href="recuperar_contrasena.php"><i class="fas fa-key"></i> Olvidé mi contraseña</a>
            <a href="registro.php"><i class="fas fa-user-plus"></i> Crear nueva cuenta</a>
        </div>
        
        <div class="footer">
            &copy; <?php echo date('Y'); ?> IE Miguel Cortés del Castillo. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>