<?php
session_start();
if (!isset($_SESSION['reset_email'])) {
    header("Location: solicitar_restablecimiento.html");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "bd_aula");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$email = $_SESSION['reset_email'];

$stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $password, $email);
$stmt->execute();

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de actualización</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #2d3e2d;
        }
        
        .message-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .success-message {
            color: #2e7d32;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .error-message {
            color: #d32f2f;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .login-link {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #2e7d32;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .login-link:hover {
            background-color: #2e7d32;
            color: white;
        }
        
        @media (max-width: 480px) {
            .message-container {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="message-container">';

if ($stmt->affected_rows > 0) {
    echo '<p class="success-message">Contraseña actualizada correctamente.</p>
          <a href="index.php" class="login-link">Iniciar sesión</a>';
} else {
    echo '<p class="error-message">Error al actualizar contraseña.</p>';
}

echo '</div>
</body>
</html>';

$stmt->close();
$conexion->close();
session_destroy();
?>