<?php
// Configuración de conexión
$host = 'localhost';
$db = 'ie_miguel_cortes';
$user = 'root';
$pass = ''; // cambia por tu contraseña real si tienes
$charset = 'utf8mb4';

// Conexión PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $nueva_contrasena = $_POST['nueva_contrasena'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($nueva_contrasena)) {
        $mensaje = "<div class='mensaje error'>Correo o contraseña inválidos.</div>";
    } else {
        // Buscar usuario
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Encriptar contraseña y actualizar
            $hash = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
            $update = $pdo->prepare("UPDATE usuarios SET contrasena_hash = ? WHERE id = ?");
            $update->execute([$hash, $usuario['id']]);
            $mensaje = "<div class='mensaje exito'>Contraseña actualizada con éxito.</div>";
        } else {
            $mensaje = "<div class='mensaje error'>Correo no encontrado.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <style>
        :root {
            --verde-primario: #2e7d32;
            --verde-secundario: #4caf50;
            --verde-claro: #e8f5e9;
            --verde-enlace: #1b5e20;
            --gris-texto: #333;
            --gris-borde: #ddd;
            --blanco: #ffffff;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--verde-claro);
            color: var(--gris-texto);
            line-height: 1.6;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .contenedor {
            background-color: var(--blanco);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        h2 {
            color: var(--verde-primario);
            margin-bottom: 24px;
            text-align: center;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--verde-primario);
        }
        
        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gris-borde);
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s ease;
        }
        
        input:focus {
            border-color: var(--verde-secundario);
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        
        button {
            background-color: var(--verde-primario);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-bottom: 15px;
        }
        
        button:hover {
            background-color: var(--verde-secundario);
        }
        
        .mensaje {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .exito {
            background-color: #e8f5e9;
            color: var(--verde-primario);
            border: 1px solid #a5d6a7;
        }
        
        .error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        
        .volver {
            display: inline-block;
            color: var(--verde-enlace);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
            border: 1px solid var(--verde-primario);
        }
        
        .volver:hover {
            background-color: var(--verde-claro);
            color: var(--verde-primario);
        }
        
        .volver-container {
            text-align: center;
            margin-top: 10px;
        }
        
        @media (max-width: 600px) {
            .contenedor {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2>Recuperar Contraseña</h2>
        
        <?php if (!empty($mensaje)) echo $mensaje; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="nueva_contrasena">Nueva contraseña:</label>
                <input type="password" name="nueva_contrasena" required>
            </div>
            
            <button type="submit">Cambiar Contraseña</button>
            
            <div class="volver-container">
                <a href="index.php" class="volver">← Volver al inicio</a>
            </div>
        </form>
    </div>
</body>
</html>