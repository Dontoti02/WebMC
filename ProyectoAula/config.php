<?php
// config.php
$host = 'localhost';
$db   = 'bd_aula';
$user = 'root';  // Reemplaza con tu usuario de MySQL
$pass = '';  // Reemplaza con tu contraseña
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Registrar el error y mostrar un mensaje más amigable
    error_log('Error de conexión: ' . $e->getMessage());
    die('Error al conectar con la base de datos. Por favor intente más tarde.');
}