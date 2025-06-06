<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Obtener el ID de la incidencia de la URL
$incidencia_id = $_GET['id'] ?? null;

if (!$incidencia_id) {
    echo "ID de incidencia no proporcionado.";
    exit();
}

// Obtener los datos de la incidencia
$stmt = $conn->prepare("SELECT * FROM incidencias WHERE id = ?");
$stmt->bind_param("i", $incidencia_id);
$stmt->execute();
$result = $stmt->get_result();
$incidencia = $result->fetch_assoc();
$stmt->close();

if (!$incidencia) {
    echo "Incidencia no encontrada.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Incidencia | IE Miguel Cortés</title>
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
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --radius: 8px;
            --accent-blue: #1976d2;
            --accent-orange: #f57c00;
            --accent-red: #d32f2f;
            --accent-purple: #7b1fa2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f9f6 0%, #e8f5e8 100%);
            color: var(--text);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            color: var(--primary);
            font-size: 2rem;
            margin: 0;
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent-blue), var(--accent-orange), var(--accent-red));
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.2);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .descripcion-group {
            grid-column: 1 / -1;
        }

        .submit-group {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 1rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            min-width: 200px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
        }

        .priority-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 0.5rem;
        }

        .priority-baja {
            background: #c8e6c9;
            color: #2e7d32;
        }

        .priority-media {
            background: #fff3e0;
            color: #f57c00;
        }

        .priority-alta {
            background: #ffecb3;
            color: #ff8f00;
        }

        .priority-urgente {
            background: #ffcdd2;
            color: #d32f2f;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .card {
                padding: 1rem;
            }

            .header h2 {
                font-size: 1.5rem;
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .form-group {
            animation: slideInUp 0.5s ease-out;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.4s;
        }

        .form-group:nth-child(5) {
            animation-delay: 0.5s;
        }

        .form-group:nth-child(6) {
            animation-delay: 0.6s;
        }

        .btn-submit:active {
            animation: pulse 0.2s ease-in-out;
        }

        .form-indicator {
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: var(--radius);
            background: linear-gradient(45deg, var(--primary), var(--accent-blue), var(--accent-orange));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .form-group:focus-within .form-indicator {
            opacity: 0.1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Editar Incidencia</h2>
        </div>

        <div class="card">
            <form action="actualizar_incidencia.php" method="post">
                <input type="hidden" name="incidencia_id" value="<?php echo $incidencia['id']; ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <div class="form-indicator"></div>
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo isset($incidencia['titulo']) ? htmlspecialchars($incidencia['titulo']) : ''; ?>" placeholder="Ingrese el título de la incidencia">
                    </div>

                    <div class="form-group">
                        <div class="form-indicator"></div>
                        <label for="categoria">Categoria:</label>
                        <select id="categoria" name="categoria">
                            <option value="Hardware" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'Hardware') ? 'selected' : ''; ?>>Hardware</option>
                            <option value="Software" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'Software') ? 'selected' : ''; ?>>Software</option>
                            <option value="Red/Internet" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'Red/Internet') ? 'selected' : ''; ?>>Red/Internet</option>
                            <option value="Impresora" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'Impresora') ? 'selected' : ''; ?>>Impresora</option>
                            <option value="Proyector" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'Proyector') ? 'selected' : ''; ?>>Proyector</option>
                            <option value="Audio/Video" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'Audio/video') ? 'selected' : ''; ?>>Audio/Video</option>
                            <option value="otro" <?php echo (isset($incidencia['categoria']) && $incidencia['categoria'] === 'otro') ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="form-indicator"></div>
                        <label for="ubicacion">Ubicación:</label>
                        <input type="text" id="ubicacion" name="ubicacion" value="<?php echo isset($incidencia['ubicacion']) ? htmlspecialchars($incidencia['ubicacion']) : ''; ?>" placeholder="Ej: Aula 201, Laboratorio, Patio principal">
                    </div>

                    <div class="form-group">
                        <div class="form-indicator"></div>
                        <label for="prioridad">Prioridad:</label>
                        <select id="prioridad" name="prioridad">
                            <option value="baja" <?php echo (isset($incidencia['prioridad']) && $incidencia['prioridad'] === 'baja') ? 'selected' : ''; ?>>Baja</option>
                            <option value="media" <?php echo (isset($incidencia['prioridad']) && $incidencia['prioridad'] === 'media') ? 'selected' : ''; ?>>Media</option>
                            <option value="alta" <?php echo (isset($incidencia['prioridad']) && $incidencia['prioridad'] === 'alta') ? 'selected' : ''; ?>>Alta</option>
                            <option value="urgente" <?php echo (isset($incidencia['prioridad']) && $incidencia['prioridad'] === 'urgente') ? 'selected' : ''; ?>>Urgente</option>
                        </select>
                    </div>

                    <div class="form-group descripcion-group">
                        <div class="form-indicator"></div>
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" placeholder="Describa detalladamente la incidencia..."><?php echo isset($incidencia['descripcion']) ? htmlspecialchars($incidencia['descripcion']) : ''; ?></textarea>
                    </div>

                    <div class="submit-group">
                        <button type="submit" class="btn-submit">Actualizar Incidencia</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="text"], textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            });
        });
    </script>
</body>
</html>
