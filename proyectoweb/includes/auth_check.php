<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
// Assuming conexion.php is in the parent directory relative to this include.
// Adjust path if conexion.php location is different relative to files including this.
require_once __DIR__ . '/../conexion.php';

// Obtener el nombre del usuario
$usuario_id = $_SESSION['usuario_id'];
$stmt_auth_check = $conn->prepare("SELECT id, nombre_completo, nombre_usuario, rol FROM usuarios WHERE id = ?");
$stmt_auth_check->bind_param("i", $usuario_id);
$stmt_auth_check->execute();
$result_auth_check = $stmt_auth_check->get_result();
$usuario_actual = $result_auth_check->fetch_assoc(); // Use a unique variable name
$stmt_auth_check->close();

if (!$usuario_actual) {
    // Handle case where user details are not found, maybe logout
    header("Location: logout.php?error=user_not_found");
    exit();
}
?>
