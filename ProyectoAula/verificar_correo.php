<?php
$conexion = new mysqli("localhost", "root", "", "bd_aula");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$email = trim($_POST['email']);

$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    session_start();
    $_SESSION['reset_email'] = $email;
    header("Location: nueva_contraseña.php");
} else {
    echo "Correo no encontrado. <a href='solicitar_restablecimiento.html'>Intentar de nuevo</a>";
}

$stmt->close();
$conexion->close();
?>
