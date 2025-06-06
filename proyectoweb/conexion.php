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
?>