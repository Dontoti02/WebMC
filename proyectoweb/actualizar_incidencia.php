<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar si se recibió el ID de la incidencia
if (!isset($_POST['incidencia_id'])) {
    echo "ID de incidencia no proporcionado.";
    exit();
}

$incidencia_id = $_POST['incidencia_id'];
$titulo = $_POST['titulo'];
$categoria = $_POST['categoria'];
$ubicacion = $_POST['ubicacion'];
$descripcion = $_POST['descripcion'];
$prioridad = $_POST['prioridad'];

// Actualizar los datos de la incidencia
$stmt = $conn->prepare("UPDATE incidencias SET titulo = ?, categoria = ?, ubicacion = ?, descripcion = ?, prioridad = ? WHERE id = ?");
$stmt->bind_param("sssssi", $titulo, $categoria, $ubicacion, $descripcion, $prioridad, $incidencia_id);

if ($stmt->execute()) {
    header("Location: mis_incidencias.php?mensaje=Incidencia actualizada correctamente.");
} else {
    echo "Error al actualizar la incidencia: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
