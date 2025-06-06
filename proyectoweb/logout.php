<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    require_once 'conexion.php';
    
    try {
        $stmt = $conn->prepare("INSERT INTO auditoria_accesos (usuario_id, direccion_ip, user_agent, accion, exito) 
                               VALUES (?, ?, ?, 'logout', 1)");
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
        $stmt->bind_param("iss", $_SESSION['usuario_id'], $ip, $user_agent);
        $stmt->execute();
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        error_log("Error al registrar cierre de sesión: " . $e->getMessage());
    }
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: index.php?logout=1");
exit();
?>