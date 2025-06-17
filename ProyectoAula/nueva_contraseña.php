<?php
session_start();
if (!isset($_SESSION['reset_email'])) {
    header("Location: solicitar_restablecimiento.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Establecer Nueva Contraseña</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f9f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #2d3e2d;
        }
        
        h2 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 400;
        }
        
        form {
            background-color: white;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #4a6350;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #c8e6c9;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            transition: border 0.3s ease;
            box-sizing: border-box;
        }
        
        input[type="password"]:focus {
            outline: none;
            border-color: #2e7d32;
        }
        
        button {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        
        button:hover {
            background-color: #1b5e20;
        }
        
        .password-requirements {
            font-size: 0.8rem;
            color: #6b8a6b;
            margin-top: -1rem;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 480px) {
            form {
                padding: 1.5rem;
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <form action="guardar_nueva_contra.php" method="post">
        <h2>Establecer nueva contraseña</h2>
        <label for="password">Nueva contraseña:</label>
        <input type="password" name="password" required>
        <p class="password-requirements">Mínimo 8 caracteres, incluyendo mayúsculas y números</p>
        <button type="submit">Guardar nueva contraseña</button>
    </form>
</body>
</html>