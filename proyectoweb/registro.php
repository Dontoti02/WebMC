<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | IE Miguel Cortés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles_registro.css">
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2>Crear Nueva Cuenta</h2>
        
        <form id="registerForm" action="procesar_registro.php" method="POST">
            <div class="form-group">
                <label for="nombre_completo">Nombre Completo</label>
                <i class="fas fa-user input-icon"></i>
                <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" placeholder="Ej: Juan Pérez" required>
                <div id="nombre-error" class="error-message"></div>
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" id="email" name="email" class="form-control" placeholder="Ej: usuario@escuela.edu" required>
                <div id="email-error" class="error-message"></div>
            </div>
            
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <i class="fas fa-id-card input-icon"></i>
                <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" placeholder="Ej: juan123" required>
                <div id="usuario-error" class="error-message"></div>
            </div>
            
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Mínimo 8 caracteres" required minlength="8">
                <div id="password-error" class="error-message"></div>
            </div>
            
            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                <i class="fas fa-lock input-icon"></i>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control" placeholder="Repita su contraseña" required>
                <div id="confirm-error" class="error-message"></div>
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-user-plus"></i> Registrarse
            </button>
        </form>
        
        <div class="links">
            <a href="index.php"><i class="fas fa-sign-in-alt"></i> ¿Ya tienes cuenta? Inicia Sesión</a>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            // Validación de contraseñas coincidentes
            const password = document.getElementById('contrasena').value;
            const confirmPassword = document.getElementById('confirmar_contrasena').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                document.getElementById('confirm-error').textContent = 'Las contraseñas no coinciden';
                document.getElementById('confirm-error').style.display = 'block';
            } else {
                document.getElementById('confirm-error').style.display = 'none';
            }
            
            // Puedes agregar más validaciones aquí si es necesario
        });
    </script>
</body>
</html>