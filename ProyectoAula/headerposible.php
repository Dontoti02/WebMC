<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Miguel Cortés</title>
    <style>
        body {
            margin: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f8f8;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 120px;
            background-color: #f5faf6;
            padding: 0 40px;
            box-sizing: border-box;
            border-bottom: 1px solid #e0eae1;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logo-img {
            height: 80px;
            width: auto;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .initials {
            font-size: 16px;
            font-weight: 300;
            letter-spacing: 3px;
            color: #2a5934;
            margin-bottom: 2px;
        }
        
        .mc {
            font-size: 36px;
            font-weight: 100;
            color: #1e4424;
            letter-spacing: 1px;
            line-height: 1;
        }
        
        .castilla {
            font-size: 11px;
            letter-spacing: 4px;
            color: #4a7855;
            margin-top: 3px;
            text-transform: uppercase;
        }
        
        .nav-menu {
            display: flex;
            gap: 30px;
        }
        
        .nav-item {
            color: #2a5934;
            text-decoration: none;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: color 0.3s;
            position: relative;
            padding-bottom: 5px;
        }
        
        .nav-item:hover {
            color: #1e4424;
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background-color: #1e4424;
            transition: width 0.3s;
        }
        
        .nav-item:hover::after {
            width: 100%;
        }
        
        .account-item {
            color: #7a9c7e;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="images/mc.png" alt="Logo MC" class="logo-img">
            <div class="logo-text">
                <div class="initials">I E</div>
                <div class="mc">Miguel Cortés del</div>
                <div class="castilla">CASTILLO</div>
            </div>
        </div>
        
        <nav class="nav-menu">
            <a href="dashboard.php" class="nav-item">Inicio</a>
            <a href="actividades.php" class="nav-item">Actividades</a>
            <a href="nosotros.php" class="nav-item">Sobre Nosotros</a>
            <a href="logout.php" class="nav-item account-item">Cerrar Sesión</a>
        </nav>
    </header>