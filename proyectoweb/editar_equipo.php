<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
require_once 'conexion.php';

// Obtener el ID del equipo de la URL
$equipo_id = $_GET['id'] ?? null;

if (!$equipo_id) {
    echo "ID de equipo no proporcionado.";
    exit();
}

// Obtener los datos del equipo
$stmt = $conn->prepare("SELECT * FROM equipos_tecnologicos WHERE id = ?");
if (!$stmt) {
    echo "Error en la preparación de la consulta: " . $conn->error;
    exit();
}

$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo "Error al obtener los resultados: " . $stmt->error;
    exit();
}

$equipo = $result->fetch_assoc();
$stmt->close();

if (!$equipo) {
    echo "Equipo no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo | IE Miguel Cortés</title>
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
            --shadow: 0 2px 8px rgba(0,0,0,0.1);
            --radius: 8px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            backdrop-filter: blur(10px);
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
            min-height: 100px;
            resize: vertical;
        }

        /* Estilos especiales para campos específicos */
        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Campo de observaciones ocupa todo el ancho */
        .observaciones-group {
            grid-column: 1 / -1;
        }

        /* Botón de envío */
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
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
        }

        /* Iconos para los campos */
        .form-group.with-icon {
            position: relative;
        }

        .form-group.with-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            z-index: 1;
        }

        /* Estados de conservación con colores */
        select[name="estado_conservacion"] option[value="excelente"] {
            background-color: #c8e6c9;
        }
        select[name="estado_conservacion"] option[value="bueno"] {
            background-color: #dcedc8;
        }
        select[name="estado_conservacion"] option[value="regular"] {
            background-color: #fff3e0;
        }
        select[name="estado_conservacion"] option[value="malo"] {
            background-color: #ffcdd2;
        }
        select[name="estado_conservacion"] option[value="pésimo"] {
            background-color: #ffebee;
        }

        /* Responsive */
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

        /* Animaciones */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: slideIn 0.3s ease-out;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }
        .form-group:nth-child(7) { animation-delay: 0.7s; }
        .form-group:nth-child(8) { animation-delay: 0.8s; }
        .form-group:nth-child(9) { animation-delay: 0.9s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-edit"></i> Editar Equipo Tecnológico</h2>
        </div>
        
        <div class="card">
            <form action="actualizar_equipo.php" method="post">
                <input type="hidden" name="equipo_id" value="<?php echo $equipo['id']; ?>">
                
                <div class="form-grid">
                    <div class="form-group with-icon">
                        <label for="numero_orden"><i class="fas fa-hashtag"></i> Número de Orden:</label>
                        <input type="text" id="numero_orden" name="numero_orden" value="<?php echo htmlspecialchars($equipo['numero_orden']); ?>" required>
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="cantidad"><i class="fas fa-calculator"></i> Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($equipo['cantidad']); ?>" required min="1">
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="denominacion"><i class="fas fa-tag"></i> Denominación:</label>
                        <input type="text" id="denominacion" name="denominacion" value="<?php echo htmlspecialchars($equipo['denominacion']); ?>" required>
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="marca"><i class="fas fa-trademark"></i> Marca:</label>
                        <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($equipo['marca']); ?>" required>
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="modelo"><i class="fas fa-code"></i> Modelo:</label>
                        <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($equipo['modelo']); ?>" required>
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="color"><i class="fas fa-palette"></i> Color:</label>
                        <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($equipo['color']); ?>" required>
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="serie"><i class="fas fa-barcode"></i> Serie:</label>
                        <input type="text" id="serie" name="serie" value="<?php echo htmlspecialchars($equipo['serie']); ?>" required>
                    </div>
                    
                    <div class="form-group with-icon">
                        <label for="estado_conservacion"><i class="fas fa-clipboard-check"></i> Estado de Conservación:</label>
                        <select id="estado_conservacion" name="estado_conservacion" required>
                            <option value="excelente" <?php echo $equipo['estado_conservacion'] === 'excelente' ? 'selected' : ''; ?>>Excelente</option>
                            <option value="bueno" <?php echo $equipo['estado_conservacion'] === 'bueno' ? 'selected' : ''; ?>>Bueno</option>
                            <option value="regular" <?php echo $equipo['estado_conservacion'] === 'regular' ? 'selected' : ''; ?>>Regular</option>
                            <option value="malo" <?php echo $equipo['estado_conservacion'] === 'malo' ? 'selected' : ''; ?>>Malo</option>
                            <option value="pésimo" <?php echo $equipo['estado_conservacion'] === 'pésimo' ? 'selected' : ''; ?>>Pésimo</option>
                        </select>
                    </div>
                    
                    <div class="form-group observaciones-group">
                        <label for="observaciones"><i class="fas fa-sticky-note"></i> Observaciones:</label>
                        <textarea id="observaciones" name="observaciones" placeholder="Ingrese observaciones adicionales sobre el equipo..."><?php echo htmlspecialchars($equipo['observaciones']); ?></textarea>
                    </div>
                    
                    <div class="submit-group">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Actualizar Equipo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>