<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
$mensaje = $_GET['mensaje'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InmoCasa — Iniciar sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
        }

        .panel-left {
            width: 45%;
            background: #1a2e44;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .brand {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .brand-logo span {
            color: #4da6ff;
        }

        .brand-tagline {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.95rem;
            margin-bottom: 3rem;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.95rem;
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(77, 166, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4da6ff;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .login-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #1a2e44;
            margin-bottom: 0.4rem;
        }

        .login-subtitle {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .alert ul {
            padding-left: 1.2rem;
            margin: 0;
        }

        .alert ul li {
            margin-top: 2px;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.9rem;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.7rem 1rem 0.7rem 2.5rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #1a2e44;
            transition: border-color 0.2s;
            outline: none;
            background: #fafafa;
        }

        input:focus {
            border-color: #4da6ff;
            background: #fff;
        }

        .input-error {
            border-color: #dc2626 !important;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 0.9rem;
            padding: 0;
        }

        .toggle-password:hover {
            color: #4da6ff;
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background: #1a2e44;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: #243d57;
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        @media (max-width: 768px) {
            .panel-left {
                display: none;
            }

            .panel-right {
                background: #f0f4f8;
            }
        }
    </style>
</head>

<body>

    <div class="panel-left">
        <div class="brand">
            <div class="brand-logo">Inmo<span>Casa</span></div>
            <p class="brand-tagline">Sistema de gestión inmobiliaria</p>
            <ul class="feature-list">
                <li>
                    <div class="feature-icon"><i class="fa-solid fa-house"></i></div>
                    Gestiona propiedades y tipos de inmueble
                </li>
                <li>
                    <div class="feature-icon"><i class="fa-solid fa-file-contract"></i></div>
                    Controla contratos y fechas de vencimiento
                </li>
                <li>
                    <div class="feature-icon"><i class="fa-solid fa-credit-card"></i></div>
                    Registra y rastrea pagos mensuales
                </li>
                <li>
                    <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                    Administra propietarios e inquilinos
                </li>
            </ul>
        </div>
    </div>

    <div class="panel-right">
        <div class="login-box">
            <h1 class="login-title">Bienvenido</h1>
            <p class="login-subtitle">Ingresa tus credenciales para continuar</p>

            <?php if ($mensaje === 'sesion_cerrada'): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    Sesión cerrada correctamente.
                </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="../../public/index.php?action=procesarLogin" method="POST" novalidate id="loginForm">

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email"
                            placeholder="ejemplo@correo.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="password" name="password"
                            placeholder="Tu contraseña">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let valido = true;

            email.classList.remove('input-error');
            password.classList.remove('input-error');

            if (!email.value.trim()) {
                email.classList.add('input-error');
                valido = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                email.classList.add('input-error');
                valido = false;
            }

            if (!password.value.trim()) {
                password.classList.add('input-error');
                valido = false;
            }

            if (!valido) e.preventDefault();
        });
    </script>

</body>

</html>