-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS bd_aula;
USE bd_aula;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni CHAR(8) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    email VARCHAR(100) NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE,
    rol ENUM('administrador', 'docente', 'estudiante', 'apoderado') DEFAULT 'estudiante'
);

-- Tabla para verificación de cuentas (opcional)
CREATE TABLE verificacion_cuentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token_verificacion VARCHAR(255) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_verificacion TIMESTAMP NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de logs de acceso (opcional)
CREATE TABLE logs_acceso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    direccion_ip VARCHAR(45),
    navegador VARCHAR(255),
    sistema_operativo VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear un usuario administrador inicial (opcional)
-- INSERT INTO usuarios (nombres, apellidos, dni, email, usuario, password, rol)
-- VALUES ('Admin', 'Sistema', '00000000', 'admin@institucion.edu.pe', 'admin', SHA2('admin123', 256), 'administrador');