<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Reemplaza con tu usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL
$dbname = "ie_miguel_cortes";

// Crear conexión
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Configurar el charset
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Inicializar variables y mensajes de error
$errors = [];
$success = false;

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $nombre_completo = htmlspecialchars(trim($_POST['nombre_completo']));
    $email = htmlspecialchars(trim($_POST['email']));
    $nombre_usuario = htmlspecialchars(trim($_POST['nombre_usuario']));
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    
    // Validaciones básicas
    if (empty($nombre_completo)) {
        $errors[] = "El nombre completo es requerido";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Por favor ingrese un correo electrónico válido";
    }
    
    if (empty($nombre_usuario)) {
        $errors[] = "El nombre de usuario es requerido";
    }
    
    if (strlen($contrasena) < 8) {
        $errors[] = "La contraseña debe tener al menos 8 caracteres";
    }
    
    if ($contrasena !== $confirmar_contrasena) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    // Verificar si el email o nombre de usuario ya existen
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR nombre_usuario = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }
            
            $stmt->bind_param("ss", $email, $nombre_usuario);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $errors[] = "El correo electrónico o nombre de usuario ya está en uso";
            }
            $stmt->close();
        } catch (Exception $e) {
            $errors[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
    
    // Si no hay errores, insertar el nuevo usuario
    if (empty($errors)) {
        try {
            // Hash de la contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            
            // Generar token de verificación
            $token_verificacion = bin2hex(random_bytes(32));
            
            // Insertar el usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, email, nombre_usuario, contrasena_hash, token_verificacion) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la inserción: " . $conn->error);
            }
            
            $stmt->bind_param("sssss", $nombre_completo, $email, $nombre_usuario, $contrasena_hash, $token_verificacion);
            
            if ($stmt->execute()) {
                $usuario_id = $stmt->insert_id;
                
                // Asignar rol de estudiante por defecto
                $rol_estudiante = 1; // Asumiendo que el ID del rol estudiante es 1
                $stmt_rol = $conn->prepare("INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)");
                if (!$stmt_rol) {
                    throw new Exception("Error al preparar la asignación de rol: " . $conn->error);
                }
                
                $stmt_rol->bind_param("ii", $usuario_id, $rol_estudiante);
                if (!$stmt_rol->execute()) {
                    throw new Exception("Error al asignar rol: " . $stmt_rol->error);
                }
                $stmt_rol->close();
                
                $success = true;
            } else {
                throw new Exception("Error al registrar el usuario: " . $stmt->error);
            }
            $stmt->close();
        } catch (Exception $e) {
            $errors[] = "Error al procesar el registro: " . $e->getMessage();
        }
    }
}

// Cerrar conexión
if (isset($conn) && $conn) {
    $conn->close();
}

// Redireccionar o mostrar mensajes
if ($success) {
    header("Location: registro_exitoso.php");
    exit();
} else {
    // Mostrar errores (podrías redirigir de vuelta al formulario con los errores)
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: registro.html");
    exit();
}
?>