<?php
$curso_titulo = "MATEMATICA";
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'contenido';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso_titulo); ?></title>
    <style>
        :root {
            --verde-primario: #2e7d32;
            --verde-secundario: #4caf50;
            --verde-claro: #e8f5e9;
            --texto-oscuro: #333;
            --texto-claro: #fff;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--texto-oscuro);
        }
        
        .curso-container {
            position: relative;
        }
        
        .cerrar-curso {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 2.5rem;
            color: var(--texto-claro);
            text-decoration: none;
            font-weight: bold;
            z-index: 10;
            transition: transform 0.3s ease;
        }
        
        .cerrar-curso:hover {
            transform: scale(1.2);
        }
        
        .curso-header {
            background-color: var(--verde-primario);
            color: var(--texto-claro);
            padding: 1.5rem 2rem 1.5rem 5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .curso-titulo {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 500;
            max-width: 80%;
        }
        
        .pestanas {
            display: flex;
            margin-top: 1.5rem;
            border-bottom: 1px solid var(--verde-secundario);
            flex-wrap: wrap;
        }
        
        .pestana {
            padding: 0.8rem 1.2rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            background-color: var(--verde-secundario);
            color: var(--texto-claro);
            text-decoration: none;
            border-radius: 5px 5px 0 0;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .pestana:hover {
            background-color: var(--verde-primario);
        }
        
        .pestana.activa {
            background-color: var(--verde-claro);
            color: var(--texto-oscuro);
            font-weight: 600;
        }
        
        .imagen-panoramica {
            width: 100%;
            height: 350px;
            object-fit: cover;
            display: block;
            border-bottom: 3px solid var(--verde-primario);
        }
        
        .elementos-curso {
            padding: 1rem 2rem;
            background-color: var(--verde-claro);
            margin-top: -5px;
        }
        
        .elementos-curso ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 1.5rem;
        }
        
        .elementos-curso li {
            font-weight: 500;
            color: var(--verde-primario);
        }

        /* Estilos para el acordeón */
        .accordion-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .accordion-item {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .accordion-header {
            width: 100%;
            padding: 15px 20px;
            text-align: left;
            background-color: var(--verde-secundario);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .accordion-header:hover {
            background-color: var(--verde-primario);
        }

        .accordion-header::after {
            content: '+';
            font-size: 20px;
        }

        .accordion-header.active::after {
            content: '-';
        }

        .accordion-content {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: white;
        }

        .accordion-content p {
            padding: 15px 0;
            margin: 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="curso-container">
        <a href="dashboard.php" class="cerrar-curso" title="Cerrar curso">×</a>
        
        <header class="curso-header">
            <h1 class="curso-titulo"><?php echo htmlspecialchars($curso_titulo); ?></h1>
            
            <nav class="pestanas">
                <a href="?pagina=contenido" class="pestana <?php echo ($pagina_actual == 'contenido') ? 'activa' : ''; ?>">Contenido</a>
                <a href="?pagina=calendario" class="pestana <?php echo ($pagina_actual == 'calendario') ? 'activa' : ''; ?>">Calendario</a>
                <a href="?pagina=anuncios" class="pestana <?php echo ($pagina_actual == 'anuncios') ? 'activa' : ''; ?>">Anuncios</a>
                <a href="?pagina=debates" class="pestana <?php echo ($pagina_actual == 'debates') ? 'activa' : ''; ?>">Debates</a>
                <a href="?pagina=calificaciones" class="pestana <?php echo ($pagina_actual == 'calificaciones') ? 'activa' : ''; ?>">Libro de calificaciones</a>
                <a href="?pagina=mensajes" class="pestana <?php echo ($pagina_actual == 'mensajes') ? 'activa' : ''; ?>">Mensajes</a>
                <a href="?pagina=grupos" class="pestana <?php echo ($pagina_actual == 'grupos') ? 'activa' : ''; ?>">Grupos</a>
                <a href="?pagina=logros" class="pestana <?php echo ($pagina_actual == 'logros') ? 'activa' : ''; ?>">Logros</a>
            </nav>
        </header>
        
        <img src="images/mate.jpg" alt="Imagen panorámica del curso" class="imagen-panoramica">
        
        <div class="accordion-container">
            <div class="accordion-item">
                <button class="accordion-header">INFORMACIÓN GENERAL</button>
                <div class="accordion-content">
                    <p>Contiene la presentación del docente, sílabo, cronograma de actividades, foro de presentación y documentos académicos que facilitan tu aprendizaje en la experiencia curricular.</p>
                </div>
            </div>

            <!-- <div class="accordion-item">
                <button class="accordion-header">ACTIVIDAD 1</button>
                <div class="accordion-content">
                    <p>Identifica y documenta los requerimientos funcionales y no funcionales del proyecto.</p>
                </div>
            </div>

            <div class="accordion-item">
                <button class="accordion-header">ACTIVIDAD 2</button>
                <div class="accordion-content">
                    <p>Elabora diagramas de flujo para los macroprocesos identificados en la unidad 1.</p>
                </div>
            </div>

            <div class="accordion-item">
                <button class="accordion-header">ACTIVIDAD 3</button>
                <div class="accordion-content">
                    <p>Desarrolla los casos de uso principales del sistema con sus respectivos actores y flujos.</p>
                </div>
            </div>

            <div class="accordion-item">
                <button class="accordion-header">ACTIVIDAD 4</button>
                <div class="accordion-content">
                    <p>Crea prototipos de baja y alta fidelidad para las interfaces principales del sistema.</p>
                </div>
            </div>

            <div class="accordion-item">
                <button class="accordion-header">ACTIVIDAD 5</button>
                <div class="accordion-content">
                    <p>Prepara y presenta el avance del proyecto con los documentos y artefactos generados.</p>
                </div>
            </div> -->
        </div>
        
    </div>
    
    <script>
        document.querySelectorAll('.accordion-header').forEach(button => {
            button.addEventListener('click', () => {
                const accordionItem = button.parentElement;
                const content = button.nextElementSibling;
                const isActive = button.classList.contains('active');
                
                // Cerrar todos los acordeones primero
                document.querySelectorAll('.accordion-item').forEach(item => {
                    item.querySelector('.accordion-header').classList.remove('active');
                    item.querySelector('.accordion-content').style.maxHeight = null;
                });
                
                // Abrir el actual si no estaba activo
                if (!isActive) {
                    button.classList.add('active');
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });

        // Abrir el primer acordeón por defecto
        document.querySelector('.accordion-item:first-child .accordion-header').click();
    </script>
</body>
</html>