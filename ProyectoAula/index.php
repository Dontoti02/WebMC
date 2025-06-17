<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // Redirige si ya está logueado
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - IE Miguel Cortés del Castillo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

        .login-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
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

        .input-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-size: 14px;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 14px 16px;
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

        .btn-login {
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
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .links-container {
            display: flex;
            justify-content: center;
            /* Centrar el contenido */
            margin-top: 20px;
        }

        .link {
            color: var(--primary-color);
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s;
            font-weight: 500;
        }

        .link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--dark-gray);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--medium-gray);
        }

        .divider::before {
            margin-right: 16px;
        }

        .divider::after {
            margin-left: 16px;
        }

        .btn-secondary {
            width: 100%;
            padding: 12px;
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: rgba(46, 125, 50, 0.05);
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: var(--dark-gray);
            line-height: 1.6;
        }

        .error {
            color: red;
            margin-top: 20px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
                margin: 0 16px;
            }

            h1 {
                font-size: 22px;
            }

            .links-container {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <!-- Reemplaza con el logo real de la institución -->
            <img src="images/logo.png" alt="Logo IE Miguel Cortés del Castillo">
        </div>

        <h1>Bienvenido</h1>
        <p class="subtitle">Ingrese sus credenciales para acceder al sistema educativo</p>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="validar_login.php" method="post">
            <div class="input-group">
                <label for="username">Usuario o correo electrónico</label>
                <input type="text" id="username" name="username" placeholder="usuario@institucion.edu.pe" required>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                        <span id="toggle-icon" class="fas fa-eye"></span>
                    </button>

                </div>
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>

            <div class="links-container">
                <a href="solicitar_restablecimiento.html" class="link">¿Olvidaste tu contraseña?</a>
            </div>

            <div class="divider">o</div>

            <a href="registro.php" class="link">Crear nueva cuenta</a>
        </form>

        <div class="footer">
            <p>IE Miguel Cortés del Castillo © 2025 - Todos los derechos reservados</p>
            <p>Sistema de Gestión Educativa Integral</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Cambiar el icono
                if (type === 'password') {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                    togglePassword.setAttribute('aria-label', 'Mostrar contraseña');
                } else {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                    togglePassword.setAttribute('aria-label', 'Ocultar contraseña');
                }
            });
        });
    </script>

</body>

</html>