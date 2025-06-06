<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo_id = $_POST['equipo_id'] ?? '';
    $numero_orden = $_POST['numero_orden'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $denominacion = $_POST['denominacion'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $color = $_POST['color'] ?? '';
    $serie = $_POST['serie'] ?? '';
    $estado_conservacion = $_POST['estado_conservacion'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';

    // Validar los datos (puedes agregar más validaciones según sea necesario)
    if (empty($equipo_id) || empty($numero_orden) || empty($cantidad) || empty($denominacion) || empty($marca) || empty($modelo) || empty($color) || empty($serie) || empty($estado_conservacion)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Actualizar los datos del equipo en la base de datos
    $stmt = $conn->prepare("UPDATE equipos_tecnologicos SET numero_orden = ?, cantidad = ?, denominacion = ?, marca = ?, modelo = ?, color = ?, serie = ?, estado_conservacion = ?, observaciones = ? WHERE id = ?");
    $stmt->bind_param("sisssssssi", $numero_orden, $cantidad, $denominacion, $marca, $modelo, $color, $serie, $estado_conservacion, $observaciones, $equipo_id);

    if ($stmt->execute()) {
        echo "Equipo actualizado correctamente.";
    } else {
        echo "Error al actualizar el equipo: " . $stmt->error;
    }

    $stmt->close();
}

// Redirigir de vuelta a la página de gestión
header("Location: gestion.php");
exit();
?>
