<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require 'config.php';


// Obtener datos del usuario actual
$user_id = $_SESSION['user_id'];

$query = "SELECT nombres, apellidos FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: logout.php');
    exit;
}

$nombres = $user['nombres'];
$apellidos = $user['apellidos'];
$iniciales = substr($nombres, 0, 1) . substr($apellidos, 0, 1);
?>

<!-- HTML SOLO SI ESTÁ LOGUEADO -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IE Teniente Miguel Cortés</title>
    <link rel="stylesheet" href="css/estilo1.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-container">
            <img src="images/logo.png" alt="Logo IE Teniente Miguel Cortés" class="logo">
        </div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="welcome.php" class="nav-link"><i class="fas fa-home"></i><span>Inicio</span></a></li>
            <li class="nav-item"><a href="perfil.php" class="nav-link"><i class="fas fa-user"></i><span>Perfil</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-tasks"></i><span>Actividades</span></a></li>
            <li class="nav-item"><a href="cursos.php" class="nav-link"><i class="fas fa-book"></i><span>Cursos</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-star"></i><span>Calificaciones</span></a></li>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-book-open"></i><span>Biblioteca Virtual</span></a></li>
            <li class="nav-item"><a href="logout.php" onclick="logout()" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>Cerrar Sesión</span></a>
            </li>
        </ul>
    </aside>

    <!-- Contenido dinámico -->
    <main class="main-content" id="contenido-dinamico"></main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const links = document.querySelectorAll(".nav-link");

            links.forEach(link => {
                link.addEventListener("click", function(e) {
                    const href = this.getAttribute("href");
                    if (href === "#" || href === "") {
                        e.preventDefault();
                        return;
                    }

                    e.preventDefault();

                    fetch(href)
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("contenido-dinamico").innerHTML = data;
                            links.forEach(l => l.classList.remove("active"));
                            this.classList.add("active");
                        })
                        .catch(() => {
                            document.getElementById("contenido-dinamico").innerHTML = "<p>Error al cargar el contenido.</p>";
                        });
                });
            });

            // Cargar cursos por defecto
            fetch('welcome.php')
                .then(res => res.text())
                .then(html => document.getElementById('contenido-dinamico').innerHTML = html);
        });

        async function logout() {
            try {
                const response = await fetch('logout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    // Redirige al login después de cerrar sesión
                    window.location.href = 'index.php';
                } else {
                    console.error('Error al cerrar sesión');
                }
            } catch (error) {
                console.error('Error de red:', error);
            }
        }
    </script>
</body>

</html>