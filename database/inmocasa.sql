-- =====================================================
--  InmoCasa - Base de datos
--  Sistema de gestión inmobiliaria
--  Integrantes: Edwin, Tony, Damian
-- =====================================================

CREATE DATABASE IF NOT EXISTS inmocasa CHARACTER SET utf8 COLLATE utf8_general_ci;
USE inmocasa;

-- =====================================================
--  TABLA: tipos_inmueble 
-- =====================================================
CREATE TABLE IF NOT EXISTS tipos_inmueble (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
--  TABLA: propietarios
-- =====================================================
CREATE TABLE IF NOT EXISTS propietarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(13) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    email VARCHAR(100) NOT NULL UNIQUE,
    direccion VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
--  TABLA: inquilinos 
-- =====================================================
CREATE TABLE IF NOT EXISTS inquilinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(13) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    email VARCHAR(100) NOT NULL UNIQUE,
    direccion VARCHAR(200),
    referencia VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
--  TABLA: propiedades 
-- =====================================================
CREATE TABLE IF NOT EXISTS propiedades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    direccion VARCHAR(200) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    metros2 DECIMAL(8,2) NOT NULL,
    descripcion TEXT,
    estado ENUM('Disponible', 'Arrendada', 'En venta', 'Vendida') DEFAULT 'Disponible',
    tipo_id INT NOT NULL,
    propietario_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_id) REFERENCES tipos_inmueble(id),
    FOREIGN KEY (propietario_id) REFERENCES propietarios(id)
);

-- =====================================================
--  TABLA: contratos 
-- =====================================================
CREATE TABLE IF NOT EXISTS contratos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    propiedad_id INT NOT NULL,
    inquilino_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    monto_mensual DECIMAL(10,2) NOT NULL,
    estado ENUM('Activo', 'Finalizado', 'Cancelado') DEFAULT 'Activo',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (propiedad_id) REFERENCES propiedades(id),
    FOREIGN KEY (inquilino_id) REFERENCES inquilinos(id)
);

-- =====================================================
--  TABLA: pagos 
-- =====================================================
CREATE TABLE IF NOT EXISTS pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contrato_id INT NOT NULL,
    fecha_pago DATE,
    monto DECIMAL(10,2) NOT NULL,
    mes_correspondiente VARCHAR(20) NOT NULL,
    estado ENUM('Pagado', 'Pendiente', 'Vencido') DEFAULT 'Pendiente',
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contrato_id) REFERENCES contratos(id)
);

-- =====================================================
--  INDICES 
-- =====================================================
-- propiedades: búsquedas frecuentes por estado y tipo
CREATE INDEX idx_propiedades_estado ON propiedades(estado);
CREATE INDEX idx_propiedades_tipo ON propiedades(tipo_id);
CREATE INDEX idx_propiedades_propietario ON propiedades(propietario_id);

-- propietarios e inquilinos: búsqueda por nombre
CREATE INDEX idx_propietarios_nombre ON propietarios(nombre);
CREATE INDEX idx_inquilinos_nombre ON inquilinos(nombre);

-- contratos: filtrar por estado, propiedad e inquilino
CREATE INDEX idx_contratos_estado ON contratos(estado);
CREATE INDEX idx_contratos_propiedad ON contratos(propiedad_id);
CREATE INDEX idx_contratos_inquilino ON contratos(inquilino_id);

-- pagos: filtrar por estado y contrato
CREATE INDEX idx_pagos_estado ON pagos(estado);
CREATE INDEX idx_pagos_contrato ON pagos(contrato_id);