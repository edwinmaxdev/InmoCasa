<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../public/index.php?action=login');
    exit();
}
$rol    = $_SESSION['rol'];
$nombre = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmoCasa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: #1a2e44;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .navbar-brand {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .navbar-brand span { color: #4da6ff; }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
        }

        .navbar-menu a {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 7px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .navbar-menu a:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }

        .navbar-menu a.active {
            background: rgba(77,166,255,0.15);
            color: #4da6ff;
        }

        .navbar-menu a i { font-size: 0.85rem; }

        /* Separador visual entre grupos de menú */
        .nav-divider {
            width: 1px;
            height: 20px;
            background: rgba(255,255,255,0.1);
            margin: 0 0.25rem;
        }

        /* Badge de rol */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .rol-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.7rem;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rol-Admin       { background: rgba(77,166,255,0.15); color: #4da6ff; }
        .rol-Propietario { background: rgba(34,197,94,0.15);  color: #22c55e; }
        .rol-Inquilino   { background: rgba(251,191,36,0.15); color: #fbbf24; }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.85);
            font-size: 0.875rem;
        }

        .navbar-user i { color: rgba(255,255,255,0.5); }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.85rem;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 7px;
            color: #f87171;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: rgba(239,68,68,0.2);
            color: #fca5a5;
        }

        /* Menú hamburguesa móvil */
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
        }

        /* Contenido principal */
        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Alertas globales */
        .alert-global {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-global.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; }
        .alert-global.error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        @media (max-width: 768px) {
            .hamburger { display: block; }
            .navbar-menu { display: none; }
            .navbar-menu.open {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 60px; left: 0; right: 0;
                background: #1a2e44;
                padding: 1rem;
                gap: 0.25rem;
            }
            .nav-divider { display: none; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="../../public/index.php?action=dashboard" class="navbar-brand">
        Inmo<span>Casa</span>
    </a>

    <ul class="navbar-menu" id="navMenu">

        <!-- Dashboard — todos los roles -->
        <li>
            <a href="../../public/index.php?action=dashboard"
               class="<?= ($_GET['action'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
        </li>

        <div class="nav-divider"></div>

        <?php if ($rol === 'Admin' || $rol === 'Propietario'): ?>
        <li>
            <a href="../../public/index.php?action=propiedades"
               class="<?= ($_GET['action'] ?? '') === 'propiedades' ? 'active' : '' ?>">
                <i class="fa-solid fa-house"></i> Propiedades
            </a>
        </li>
        <?php endif; ?>

        <?php if ($rol === 'Admin'): ?>
        <li>
            <a href="../../public/index.php?action=tipos"
               class="<?= ($_GET['action'] ?? '') === 'tipos' ? 'active' : '' ?>">
                <i class="fa-solid fa-tags"></i> Tipos
            </a>
        </li>

        <div class="nav-divider"></div>

        <li>
            <a href="../../public/index.php?action=propietarios"
               class="<?= ($_GET['action'] ?? '') === 'propietarios' ? 'active' : '' ?>">
                <i class="fa-solid fa-house-user"></i> Propietarios
            </a>
        </li>
        <li>
            <a href="../../public/index.php?action=inquilinos"
               class="<?= ($_GET['action'] ?? '') === 'inquilinos' ? 'active' : '' ?>">
                <i class="fa-solid fa-people-roof"></i> Inquilinos
            </a>
        </li>

        <div class="nav-divider"></div>
        <?php endif; ?>

        <li>
            <a href="../../public/index.php?action=contratos"
               class="<?= ($_GET['action'] ?? '') === 'contratos' ? 'active' : '' ?>">
                <i class="fa-solid fa-file-contract"></i> Contratos
            </a>
        </li>
        <li>
            <a href="../../public/index.php?action=pagos"
               class="<?= ($_GET['action'] ?? '') === 'pagos' ? 'active' : '' ?>">
                <i class="fa-solid fa-credit-card"></i> Pagos
            </a>
        </li>

        <?php if ($rol === 'Admin'): ?>
        <div class="nav-divider"></div>
        <li>
            <a href="../../public/index.php?action=usuarios"
               class="<?= ($_GET['action'] ?? '') === 'usuarios' ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear"></i> Usuarios
            </a>
        </li>
        <?php endif; ?>

    </ul>

    <div class="navbar-right">
        <span class="rol-badge rol-<?= $rol ?>"><?= $rol ?></span>
        <span class="navbar-user">
            <i class="fa-solid fa-circle-user"></i>
            <?= htmlspecialchars($nombre) ?>
        </span>
        <a href="../../public/index.php?action=logout" class="btn-logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Salir
        </a>
    </div>

    <button class="hamburger" onclick="toggleMenu()">
        <i class="fa-solid fa-bars" id="hamburgerIcon"></i>
    </button>
</nav>

<div class="main-content">

    <!-- Mensajes globales -->
    <?php
    $msg = $_GET['mensaje'] ?? '';
    $err = $_GET['error']   ?? '';
    $mensajes = [
        'creado'      => 'Registro creado exitosamente.',
        'actualizado' => 'Registro actualizado exitosamente.',
        'eliminado'   => 'Registro eliminado exitosamente.',
    ];
    $errores_msg = [
        'no_encontrado'   => 'El registro no fue encontrado.',
        'acceso_denegado' => 'No tienes permiso para acceder a esta sección.',
        'no_eliminado'    => 'No se pudo eliminar el registro.',
        'no_autoeliminar' => 'No puedes eliminar tu propia cuenta.',
    ];
    if ($msg && isset($mensajes[$msg])): ?>
        <div class="alert-global success">
            <i class="fa-solid fa-circle-check"></i>
            <?= $mensajes[$msg] ?>
        </div>
    <?php endif; ?>
    <?php if ($err && isset($errores_msg[$err])): ?>
        <div class="alert-global error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <?= $errores_msg[$err] ?>
        </div>
    <?php endif; ?>

<script>
    function toggleMenu() {
        const menu = document.getElementById('navMenu');
        const icon = document.getElementById('hamburgerIcon');
        menu.classList.toggle('open');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-xmark');
    }
</script>