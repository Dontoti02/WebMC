<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar si se ha enviado una acción
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'editar_equipo') {
        $equipo_id = $_POST['equipo'] ?? '';
        header("Location: editar_equipo.php?id=$equipo_id");
        exit();
    } elseif ($accion === 'eliminar_equipo') {
        $equipo_id = $_POST['equipo'] ?? '';
        $stmt = $conn->prepare("DELETE FROM equipos_tecnologicos WHERE id = ?");
        $stmt->bind_param("i", $equipo_id);
        if ($stmt->execute()) {
            echo "Equipo eliminado correctamente.";
        } else {
            echo "Error al eliminar el equipo.";
        }
        $stmt->close();
    } elseif ($accion === 'editar_incidencia') {
        $incidencia_id = $_POST['incidencia'] ?? '';
        header("Location: editar_incidencia.php?id=$incidencia_id");
        exit();
    } elseif ($accion === 'eliminar_incidencia') {
        $incidencia_id = $_POST['incidencia'] ?? '';
        $stmt = $conn->prepare("DELETE FROM incidencias WHERE id = ?");
        $stmt->bind_param("i", $incidencia_id);
        if ($stmt->execute()) {
            echo "Incidencia eliminada correctamente.";
        } else {
            echo "Error al eliminar la incidencia.";
        }
        $stmt->close();
    }
}

// Redirigir de vuelta a la página de gestión
header("Location: gestion.php");
exit();
?>
