<?php
session_start();
require_once 'conexion.php'; // Asegúrate de que este archivo contiene la conexión a la BD

// Verificar si se enviaron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar los datos del formulario
    $nombre_usuario = trim($_POST['username']);
    $contrasena = $_POST['password'];

    // Validar que no estén vacíos
    if (empty($nombre_usuario) || empty($contrasena)) {
        $_SESSION['error'] = "Por favor ingrese ambos campos";
        header("Location: index.php");
        exit();
    }

    // Preparar la consulta para buscar el usuario
    $stmt = $conn->prepare("SELECT id, nombre_completo, nombre_usuario, contrasena_hash FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contrasena, $usuario['contrasena_hash'])) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            
            // Obtener los roles del usuario
            $stmt_roles = $conn->prepare("SELECT r.nombre FROM usuario_roles ur JOIN roles r ON ur.rol_id = r.id WHERE ur.usuario_id = ?");
            $stmt_roles->bind_param("i", $usuario['id']);
            $stmt_roles->execute();
            $roles_result = $stmt_roles->get_result();
            
            $roles = [];
            while ($rol = $roles_result->fetch_assoc()) {
                $roles[] = $rol['nombre'];
            }
            $_SESSION['roles'] = $roles;
            
            // Registrar el acceso
            $stmt_acceso = $conn->prepare("INSERT INTO auditoria_accesos (usuario_id, direccion_ip, user_agent, exito) VALUES (?, ?, ?, 1)");
            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
            $stmt_acceso->bind_param("iss", $usuario['id'], $ip, $user_agent);
            $stmt_acceso->execute();
            
            // Redirigir al dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Registrar intento fallido
            $stmt_acceso = $conn->prepare("INSERT INTO auditoria_accesos (usuario_id, direccion_ip, user_agent, exito) VALUES (?, ?, ?, 0)");
            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
            $stmt_acceso->bind_param("iss", $usuario['id'], $ip, $user_agent);
            $stmt_acceso->execute();
            
            $_SESSION['error'] = "Credenciales incorrectas";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado";
        header("Location: index.php");
        exit();
    }
} else {
    // Si no es POST, redirigir al login
    header("Location: index.php");
    exit();
}
?>