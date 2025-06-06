<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Verificar si la columna foto_perfil existe, si no, agregarla
$columnExists = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'foto_perfil'");
if ($columnExists->num_rows == 0) {
    $conn->query("ALTER TABLE usuarios ADD foto_perfil VARCHAR(255) DEFAULT NULL");
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$tipo_mensaje = '';

// Procesar subida de foto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_perfil'])) {
    $target_dir = "uploads/";

    // Crear directorio si no existe
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = strtolower(pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION));
    $new_filename = 'perfil_' . $usuario_id . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    $uploadOk = 1;

    // Verificar si el archivo es una imagen real
    if ($_FILES["foto_perfil"]["tmp_name"]) {
        $check = getimagesize($_FILES["foto_perfil"]["tmp_name"]);
        if ($check === false) {
            $mensaje = "El archivo no es una imagen válida.";
            $tipo_mensaje = 'error';
            $uploadOk = 0;
        }
    }

    // Verificar el tamaño del archivo (2MB máximo)
    if ($_FILES["foto_perfil"]["size"] > 2000000) {
        $mensaje = "El archivo es demasiado grande. Máximo 2MB.";
        $tipo_mensaje = 'error';
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $mensaje = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $tipo_mensaje = 'error';
        $uploadOk = 0;
    }

    // Verificar si no hay errores de subida
    if ($_FILES["foto_perfil"]["error"] !== UPLOAD_ERR_OK) {
        $mensaje = "Error al subir el archivo.";
        $tipo_mensaje = 'error';
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Obtener la foto actual para eliminarla
        $stmt = $conn->prepare("SELECT foto_perfil FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario_actual = $result->fetch_assoc();
        $foto_anterior = $usuario_actual['foto_perfil'];
        $stmt->close();

        if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target_file)) {
            // Actualizar la base de datos
            $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
            $stmt->bind_param("si", $target_file, $usuario_id);

            if ($stmt->execute()) {
                // Eliminar la foto anterior si existe
                if ($foto_anterior && file_exists($foto_anterior) && $foto_anterior !== $target_file) {
                    unlink($foto_anterior);
                }

                $mensaje = "Foto de perfil actualizada correctamente.";
                $tipo_mensaje = 'success';
            } else {
                $mensaje = "Error al actualizar la base de datos.";
                $tipo_mensaje = 'error';
                // Eliminar el archivo subido si no se pudo guardar en BD
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }
            $stmt->close();
        } else {
            $mensaje = "Error al subir el archivo.";
            $tipo_mensaje = 'error';
        }
    }
}

// Obtener información del usuario actualizada
$stmt = $conn->prepare("SELECT nombre_completo, nombre_usuario, foto_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Obtener todos los usuarios registrados
$stmt = $conn->prepare("SELECT id, nombre_completo, nombre_usuario, foto_perfil FROM usuarios ORDER BY nombre_completo");
$stmt->execute();
$result = $stmt->get_result();
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | IE Miguel Cortés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #4caf50;
            --primary-dark: #1b5e20;
            --text: #333;
            --text-light: #666;
            --bg: #f5f9f6;
            --card-bg: #fff;
            --border: #e0e0e0;
            --success: #4caf50;
            --warning: #ff9800;
            --danger: #f44336;
            --info: #2196f3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--primary-dark);
            color: white;
            padding: 1.5rem 0;
            position: fixed;
            width: 240px;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }

        .logo h2 {
            font-size: 1.1rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .nav-item.active {
            background-color: var(--primary);
            border-left: 3px solid white;
        }

        .nav-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
        }

        /* Main Content */
        .main-content {
            grid-column: 2;
            padding: 2rem;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: var(--primary-dark);
            font-size: 1.8rem;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Messages */
        .message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .message.success {
            background-color: #e8f5e8;
            border: 1px solid var(--success);
            color: #2e7d32;
        }

        .message.error {
            background-color: #ffebee;
            border: 1px solid var(--danger);
            color: #c62828;
        }

        /* Profile Section */
        .profile-section {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 3rem;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: relative;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--primary-dark);
        }

        .profile-info p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .upload-form {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .upload-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .upload-form input[type="file"] {
            display: none;
        }

        .upload-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .upload-label:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .upload-btn {
            background-color: var(--success);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .upload-btn:hover {
            background-color: #388e3c;
            transform: translateY(-1px);
        }

        .upload-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .file-info {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        /* Users List */
        .users-list {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 2rem;
        }

        .users-list h2 {
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
            font-size: 1.5rem;
        }

        .user-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            transition: background-color 0.2s ease;
        }

        .user-card:hover {
            background-color: #f8f9fa;
        }

        .user-card:last-child {
            border-bottom: none;
        }

        .user-card-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .user-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-card-info h3 {
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
            color: var(--text);
        }

        .user-card-info p {
            color: var(--text-light);
            font-size: 1rem;
        }

        .user-card-actions {
            margin-left: auto;
        }

        .delete-btn {
            color: var(--danger);
            text-decoration: none;
            font-size: 1.2rem;
            transition: color 0.2s ease;
        }

        .delete-btn:hover {
            color: #b71c1c;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                grid-column: 1;
                padding: 1.5rem;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .upload-section {
                justify-content: center;
            }

            .user-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>IE Miguel Cortés</h2>
                <p style="font-size: 0.85rem; opacity: 0.8;">Sistema de Gestión</p>
            </div>

            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="dashboard.php">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="equipos_tecnologicos.php">
                        <i class="fas fa-laptop"></i>
                        <span>Registro de Equipos</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="nueva_incidencia.php">
                        <i class="fas fa-plus"></i>
                        <span>Nueva Incidencia</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="mis_incidencias.php">
                        <i class="fas fa-list"></i>
                        <span>Mis Incidencias</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="gestion.php">
                        <i class="fas fa-tasks"></i>
                        <span>Gestión</span>
                    </a>
                </div>
                <div class="nav-item active">
                    <a href="perfil.php">
                        <i class="fas fa-user"></i>
                        <span>Perfil</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>Mi Perfil</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php if (!empty($usuario['foto_perfil']) && file_exists($usuario['foto_perfil'])): ?>
                            <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <span><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></span>
                </div>
            </header>

            <!-- Messages -->
            <?php if (!empty($mensaje)): ?>
                <div class="message <?php echo $tipo_mensaje; ?>">
                    <i class="fas <?php echo $tipo_mensaje === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <!-- Profile Section -->
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php if (!empty($usuario['foto_perfil']) && file_exists($usuario['foto_perfil'])): ?>
                            <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>?<?php echo time(); ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <?php echo strtoupper(substr($usuario['nombre_completo'], 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($usuario['nombre_completo']); ?></h2>
                        <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($usuario['nombre_usuario']); ?></p>
                    </div>
                </div>

                <form class="upload-form" method="post" enctype="multipart/form-data" id="uploadForm">
                    <h3 style="margin-bottom: 1rem; color: var(--primary-dark);">
                        <i class="fas fa-camera"></i> Cambiar foto de perfil
                    </h3>
                    <div class="upload-section">
                        <label class="upload-label" for="foto_perfil">
                            <i class="fas fa-upload"></i>
                            <span id="labelText">Seleccionar imagen</span>
                        </label>
                        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" required>
                        <button type="submit" class="upload-btn" id="uploadBtn" disabled>
                            <i class="fas fa-save"></i> Guardar foto
                        </button>
                    </div>
                    <div class="file-info">
                        <small>Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB</small>
                        <div id="selectedFile" style="margin-top: 0.5rem; font-weight: 500;"></div>
                    </div>
                </form>
            </div>

            
        </main>
    </div>

    <script>
        // Manejo del archivo seleccionado
        document.getElementById('foto_perfil').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const uploadBtn = document.getElementById('uploadBtn');
            const selectedFile = document.getElementById('selectedFile');
            const labelText = document.getElementById('labelText');

            if (file) {
                // Validar tamaño
                if (file.size > 2000000) {
                    alert('El archivo es demasiado grande. Máximo 2MB.');
                    this.value = '';
                    return;
                }

                // Validar tipo
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipo de archivo no permitido. Use JPG, JPEG, PNG o GIF.');
                    this.value = '';
                    return;
                }

                uploadBtn.disabled = false;
                selectedFile.innerHTML = `<i class="fas fa-file-image"></i> ${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
                labelText.textContent = 'Cambiar imagen';
            } else {
                uploadBtn.disabled = true;
                selectedFile.innerHTML = '';
                labelText.textContent = 'Seleccionar imagen';
            }
        });

        // Confirmación antes de subir
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const file = document.getElementById('foto_perfil').files[0];
            if (file && !confirm('¿Estás seguro de que quieres cambiar tu foto de perfil?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
