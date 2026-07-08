-- =====================================================
--  DATOS DE PRUEBA
-- =====================================================
--USE inmocasa;

-- Tipos de inmueble
INSERT INTO
    tipos_inmueble (nombre, descripcion)
VALUES (
        'Casa',
        'Vivienda unifamiliar independiente'
    ),
    (
        'Apartamento',
        'Unidad habitacional en edificio'
    ),
    (
        'Local comercial',
        'Espacio destinado a actividades comerciales'
    ),
    (
        'Terreno',
        'Lote sin construcción'
    );

-- Propietarios
INSERT INTO
    propietarios (
        nombre,
        cedula,
        telefono,
        email,
        direccion
    )
VALUES (
        'Carlos Mendoza',
        '0912345678',
        '0991234567',
        'carlos.mendoza@gmail.com',
        'Av. 9 de Octubre 123, Guayaquil'
    ),
    (
        'María Torres',
        '0923456789',
        '0987654321',
        'maria.torres@gmail.com',
        'Cdla. Kennedy Norte, Guayaquil'
    ),
    (
        'José Rodríguez',
        '0934567890',
        '0976543210',
        'jose.rodriguez@gmail.com',
        'Urdesa Central, Guayaquil'
    );

-- Inquilinos
INSERT INTO
    inquilinos (
        nombre,
        cedula,
        telefono,
        email,
        direccion,
        referencia
    )
VALUES (
        'Ana Suárez',
        '0945678901',
        '0965432109',
        'ana.suarez@gmail.com',
        'Cdla. Alborada, Guayaquil',
        'Empleada estable'
    ),
    (
        'Luis Pérez',
        '0956789012',
        '0954321098',
        'luis.perez@gmail.com',
        'Cdla. Sauces, Guayaquil',
        'Comerciante independiente'
    ),
    (
        'Patricia Vélez',
        '0967890123',
        '0943210987',
        'patricia.velez@gmail.com',
        'Miraflores, Guayaquil',
        'Docente universitaria'
    );

-- Propiedades
INSERT INTO
    propiedades (
        direccion,
        precio,
        metros2,
        descripcion,
        estado,
        tipo_id,
        propietario_id
    )
VALUES (
        'Cdla. Kennedy Norte, Mz. 5 Villa 3',
        450.00,
        120.00,
        'Casa de 3 dormitorios con garage',
        'Arrendada',
        1,
        1
    ),
    (
        'Av. Francisco de Orellana, Edificio Torre Azul, Piso 8',
        380.00,
        85.00,
        'Apartamento moderno con vista al mar',
        'Disponible',
        2,
        2
    ),
    (
        'Urdesa Central, Calle Ficus 210',
        800.00,
        60.00,
        'Local comercial en zona de alta afluencia',
        'Arrendada',
        3,
        3
    ),
    (
        'Vía a la Costa Km. 12',
        200.00,
        500.00,
        'Terreno plano con escritura',
        'Disponible',
        4,
        1
    );

-- Contratos
INSERT INTO
    contratos (
        propiedad_id,
        inquilino_id,
        fecha_inicio,
        fecha_fin,
        monto_mensual,
        estado,
        observaciones
    )
VALUES (
        1,
        1,
        '2025-01-01',
        '2026-01-01',
        450.00,
        'Activo',
        'Contrato anual renovable'
    ),
    (
        3,
        2,
        '2025-03-01',
        '2026-03-01',
        800.00,
        'Activo',
        'Incluye servicios básicos'
    );

-- Pagos
INSERT INTO
    pagos (
        contrato_id,
        fecha_pago,
        monto,
        mes_correspondiente,
        estado
    )
VALUES (
        1,
        '2025-01-05',
        450.00,
        'Enero 2025',
        'Pagado'
    ),
    (
        1,
        '2025-02-04',
        450.00,
        'Febrero 2025',
        'Pagado'
    ),
    (
        1,
        '2025-03-03',
        450.00,
        'Marzo 2025',
        'Pagado'
    ),
    (
        1,
        NULL,
        450.00,
        'Abril 2025',
        'Pendiente'
    ),
    (
        2,
        '2025-03-05',
        800.00,
        'Marzo 2025',
        'Pagado'
    ),
    (
        2,
        '2025-04-06',
        800.00,
        'Abril 2025',
        'Pagado'
    ),
    (
        2,
        NULL,
        800.00,
        'Mayo 2025',
        'Pendiente'
    );

-- Usuarios
-- contraseña de todos: Admin123@ (ya encriptada con password_hash)
INSERT INTO
    usuarios (
        nombre,
        email,
        password,
        rol,
        propietario_id,
        inquilino_id
    )
VALUES (
        'Edwin Admin',
        'admin@inmocasa.com',
        '$2y$10$VEPWGACH9crOwFxiD3fXweTHldi2YbfPyQgk4HXwGdLa725/mN6o2',
        'Admin',
        NULL,
        NULL
    ),
    (
        'Carlos Mendoza',
        'carlos.mendoza@gmail.com',
        '$2y$10$VEPWGACH9crOwFxiD3fXweTHldi2YbfPyQgk4HXwGdLa725/mN6o2',
        'Propietario',
        1,
        NULL
    ),
    (
        'Ana Suárez',
        'ana.suarez@gmail.com',
        '$2y$10$VEPWGACH9crOwFxiD3fXweTHldi2YbfPyQgk4HXwGdLa725/mN6o2',
        'Inquilino',
        NULL,
        1
    );