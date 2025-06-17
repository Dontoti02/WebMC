<?php
// config.php (debe estar en el mismo directorio)
function conectarDB() {
    $host = "localhost";
    $usuario = "root";
    $contrasena = ""; // Generalmente vacío en XAMPP
    $base_datos = "bd_aula"; // Reemplaza esto con el nombre real

    $conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    return $conexion;
}

// Procesamiento del formulario
$mensaje = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validaciones básicas
    if (empty($nombres)) $errores[] = "Nombres es requerido";
    if (empty($apellidos)) $errores[] = "Apellidos es requerido";
    if (!preg_match('/^[0-9]{8}$/', $dni)) $errores[] = "DNI debe tener 8 dígitos";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Email no válido";
    if (strlen($password) < 8) $errores[] = "Contraseña debe tener al menos 8 caracteres";
    if ($password !== $confirmPassword) $errores[] = "Las contraseñas no coinciden";

    if (empty($errores)) {
        $conexion = conectarDB();

        
        // Verificar si usuario/email/dni ya existen
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE dni = ? OR email = ? OR usuario = ?");
        $stmt->bind_param("sss", $dni, $email, $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errores[] = "El DNI, email o usuario ya están registrados";
        } else {
            // Hash de la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar nuevo usuario
            $insert = $conexion->prepare("INSERT INTO usuarios 
                (nombres, apellidos, dni, telefono, email, usuario, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("sssssss", $nombres, $apellidos, $dni, $telefono, $email, $usuario, $passwordHash);
            
            if ($insert->execute()) {
                $mensaje = "Registro exitoso. Ahora puedes iniciar sesión.";
                // Limpiar variables para no mostrar datos en el formulario
                $nombres = $apellidos = $dni = $telefono = $email = $usuario = '';
            } else {
                $errores[] = "Error al registrar. Intente nuevamente.";
            }
            $insert->close();
        }
        $stmt->close();
        $conexion->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - IE Miguel Cortés del Castillo</title>
    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-light: #4caf50;
            --primary-dark: #1b5e20;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #757575;
            --white: #ffffff;
            --error-color: #d32f2f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            background-color: var(--light-gray);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(to bottom right, #f1f8e9, #e8f5e9);
        }

        .register-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 480px;
            padding: 40px;
            text-align: center;
            border-top: 4px solid var(--primary-color);
        }

        .logo {
            margin-bottom: 24px;
        }

        .logo img {
            max-width: 140px;
            height: auto;
        }

        h1 {
            color: var(--primary-dark);
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--dark-gray);
            margin-bottom: 32px;
            font-size: 15px;
            line-height: 1.5;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .input-group {
            margin-bottom: 16px;
            text-align: left;
            position: relative;
        }

        .input-group.full-width {
            grid-column: span 1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-size: 14px;
            font-weight: 500;
        }

        label.required::after {
            content: " *";
            color: var(--error-color);
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--medium-gray);
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: var(--light-gray);
        }

        input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
            background-color: var(--white);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--dark-gray);
            background: none;
            border: none;
            font-size: 18px;
            padding: 4px;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 16px;
            letter-spacing: 0.5px;
        }

        .btn-register:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-link {
            margin-top: 24px;
            font-size: 14px;
            color: var(--dark-gray);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: var(--dark-gray);
            line-height: 1.6;
        }

        /* Estilos minimalistas para mensajes */
        .mensaje {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
        }
        
        .mensaje-exito {
            color: var(--primary-dark);
            background-color: #e8f5e9;
        }
        
        .mensaje-error {
            color: var(--error-color);
            background-color: #ffebee;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="images/logo.png" alt="Logo IE Miguel Cortés del Castillo">
        </div>

        <h1>Crear nueva cuenta</h1>
        <p class="subtitle">Complete el formulario para registrarse en el sistema</p>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje mensaje-exito"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="mensaje mensaje-error">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="registerForm" action="registro.php" method="post">
            <div class="form-grid">
                <div class="input-group">
                    <label for="nombres" class="required">Nombres</label>
                    <input type="text" id="nombres" name="nombres" placeholder="Ingrese sus nombres" 
                           value="<?php echo htmlspecialchars($nombres ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label for="apellidos" class="required">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese sus apellidos" 
                           value="<?php echo htmlspecialchars($apellidos ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label for="dni" class="required">DNI</label>
                    <input type="text" id="dni" name="dni" placeholder="Ingrese su DNI" pattern="[0-9]{8}" 
                           value="<?php echo htmlspecialchars($dni ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Ingrese su teléfono"
                           value="<?php echo htmlspecialchars($telefono ?? ''); ?>">
                </div>

                <div class="input-group full-width">
                    <label for="email" class="required">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@institucion.edu.pe" 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label for="usuario" class="required">Usuario</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Cree un nombre de usuario" 
                           value="<?php echo htmlspecialchars($usuario ?? ''); ?>" required>
                </div>

                <div class="input-group">
                    <label for="password" class="required">Contraseña</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Cree una contraseña" required>
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña" data-target="password">
                            <span class="toggle-icon">👁️</span>
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="confirmPassword" class="required">Confirmar contraseña</label>
                    <div class="password-container">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Repita su contraseña" required>
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña" data-target="confirmPassword">
                            <span class="toggle-icon">👁️</span>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register">Registrarse</button>

            <div class="login-link">
                ¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a>
            </div>
        </form>

        <div class="footer">
            <p>IE Miguel Cortés del Castillo © 2023 - Todos los derechos reservados</p>
            <p>Sistema de Gestión Educativa Integral</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para mostrar/ocultar contraseña
            document.querySelectorAll('.toggle-password').forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('.toggle-icon');

                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    if (type === 'password') {
                        icon.textContent = '👁️';
                        this.setAttribute('aria-label', 'Mostrar contraseña');
                    } else {
                        icon.textContent = '🙈';
                        this.setAttribute('aria-label', 'Ocultar contraseña');
                    }
                });
            });
        });
    </script>
</body>
</html>