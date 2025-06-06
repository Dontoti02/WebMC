<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso | IE Miguel Cortés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="CSS/reg_ex.css">
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>¡Registro Exitoso!</h2>
        <p class="success-message">Tu cuenta ha sido creada correctamente. Por favor verifica tu correo electrónico para activar tu cuenta.</p>
        <a href="dashboard.php" class="btn">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </a>
    </div>
</body>
</html>