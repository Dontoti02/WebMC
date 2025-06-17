<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales del usuario
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = ? OR email = ?');
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Actualizar el último acceso
        $stmt = $pdo->prepare('UPDATE usuarios SET ultimo_acceso = CURRENT_TIMESTAMP WHERE id = ?');
        $stmt->execute([$user['id']]);

        // Registrar el acceso en los logs (opcional)
        $stmt = $pdo->prepare('INSERT INTO logs_acceso (usuario_id, direccion_ip, navegador, sistema_operativo) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $user['id'],
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT'],
            php_uname('s') . ' ' . php_uname('r')
        ]);

        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['usuario'];
        $_SESSION['role'] = $user['rol'];

        // Redirigir a la página de inicio o dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Credenciales inválidas
        $error = 'Usuario o contraseña incorrectos.';
        // Redirigir de vuelta al formulario de inicio de sesión con el mensaje de error
        header('Location: index.php?error=' . urlencode($error));
        exit;
    }
}
?>
