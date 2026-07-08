# InmoCasa
> Sistema web de gestión inmobiliaria desarrollado con PHP, MySQL y patrón MVC

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## Descripción

InmoCasa es una aplicación web que permite gestionar propiedades, contratos, pagos, propietarios e inquilinos de una inmobiliaria. Desarrollada como proyecto universitario para la materia de Desarrollo de Aplicaciones Web.

---

## Integrantes

| Nombre | Módulo |
|--------|--------|
| Edwin | Contratos, Pagos, Usuarios y Autenticación |
| Tony | Propietarios |
| Damian | Propiedades |
| Nagua | Inquilinos y Tipos de Inmueble |

---

## Funcionalidades

- Autenticación con roles — Admin, Propietario e Inquilino
- CRUD completo de propiedades, tipos de inmueble, propietarios, inquilinos, contratos y pagos
- Dashboard con contadores y alertas de contratos próximos a vencer
- Historial de pagos por inquilino
- Filtros y buscadores en todas las tablas
- Validaciones en frontend (JavaScript) y backend (PHP)

---

## Tecnologías

- PHP 8.0+
- MySQL 8.0+
- HTML5 + CSS3 + JavaScript
- Font Awesome 6.5
- Patrón MVC

---

## Estructura del proyecto

```
InmoCasa/
├── public/
│   ├── index.php
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── validaciones.js
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ContratoController.php
│   │   ├── PagoController.php
│   │   ├── UsuarioController.php
│   │   ├── PropiedadController.php
│   │   ├── TipoInmuebleController.php
│   │   ├── PropietarioController.php
│   │   └── InquilinoController.php
│   ├── models/
│   │   ├── Contrato.php
│   │   ├── Pago.php
│   │   ├── Usuario.php
│   │   ├── Propiedad.php
│   │   ├── TipoInmueble.php
│   │   ├── Propietario.php
│   │   └── Inquilino.php
│   └── views/
│       ├── layouts/
│       │   ├── header.php
│       │   ├── footer.php
│       │   └── dashboard.php
│       ├── auth/
│       │   └── login.php
│       ├── contratos/
│       ├── pagos/
│       ├── usuarios/
│       ├── propiedades/
│       ├── tipos/
│       ├── propietarios/
│       └── inquilinos/
├── config/
│   ├── database.php
│   └── database.example.php
├── database/
│   ├── inmocasa.sql
│   └── datos_prueba.sql
└── README.md
```

---

## Instalación local

### Requisitos
- XAMPP / Laragon / WAMP (PHP 8.0+ y MySQL)
- Navegador web

### Pasos

**1. Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/InmoCasa.git
```

**2. Mover a la carpeta de tu servidor local**
```bash
# XAMPP
mv InmoCasa /xampp/htdocs/

# Laragon
mv InmoCasa /laragon/www/
```

**3. Crear la base de datos**
- Abre phpMyAdmin en `http://localhost/phpmyadmin`
- Importa el archivo `database/inmocasa.sql`
- Opcionalmente importa `database/datos_prueba.sql` para tener datos de prueba

**4. Configurar la conexión**
```bash
cp config/database.example.php config/database.php
```
Edita `config/database.php` con tus credenciales:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'inmocasa');
define('DB_PORT', 3306);
```

**5. Abrir en el navegador**
```
http://localhost/InmoCasa/public/index.php
```

---

## Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@inmocasa.com | Admin123@ |
| Propietario | carlos.mendoza@gmail.com | Admin123@ |
| Inquilino | ana.suarez@gmail.com | Admin123@ |

---

## Despliegue en Render

1. Crear cuenta en [render.com](https://render.com)
2. Conectar el repositorio de GitHub
3. Crear un nuevo **Web Service**
4. Configurar las variables de entorno de la base de datos
5. Deploy automático desde la rama `main`

---

## Licencia

Proyecto universitario — Desarrollo de Aplicaciones Web 2026
