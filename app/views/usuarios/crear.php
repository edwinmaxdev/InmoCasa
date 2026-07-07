<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$errores      = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
$propietarios = $propietarios ?? [];
$inquilinos   = $inquilinos   ?? [];
?>

<style>
    .page-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .page-header a {
        color: #6b7280;
        text-decoration: none;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
    }

    .page-header a:hover {
        color: #1a2e44;
    }

    .page-header .separator {
        color: #d1d5db;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1a2e44;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-title i {
        color: #4da6ff;
    }

    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        padding: 1.75rem;
        max-width: 750px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    input,
    select {
        padding: 0.65rem 0.9rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #1a2e44;
        background: #fafafa;
        outline: none;
        transition: border-color 0.2s;
        font-family: inherit;
    }

    input:focus,
    select:focus {
        border-color: #4da6ff;
        background: #fff;
    }

    .input-error {
        border-color: #ef4444 !important;
    }

    .field-error {
        font-size: 0.78rem;
        color: #ef4444;
        margin-top: 2px;
    }

    .password-wrapper {
        position: relative;
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

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        color: #dc2626;
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .alert-error ul {
        padding-left: 1.2rem;
        margin: 0;
    }

    .alert-error ul li {
        margin-top: 2px;
    }

    /* Hint de rol */
    .rol-hint {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.25rem;
        display: none;
    }

    .rol-hint.visible {
        display: block;
    }

    .rol-hint strong {
        color: #374151;
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f3f4f6;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.65rem 1.25rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #1a2e44;
        color: #fff;
    }

    .btn-primary:hover {
        background: #243d57;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: 1;
        }
    }
</style>

<!-- Breadcrumb -->
<div class="page-header">
    <a href="../../public/index.php?action=usuarios">
        <i class="fa-solid fa-arrow-left"></i> Usuarios
    </a>
    <span class="separator">/</span>
    <div class="page-title">
        <i class="fa-solid fa-user-plus"></i>
        Nuevo usuario
    </div>
</div>

<!-- Errores -->
<?php if (!empty($errores)): ?>
    <div class="alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Formulario -->
<div class="form-card">
    <form id="formUsuario" method="POST"
        action="../../public/index.php?action=usuario_guardar"
        novalidate>

        <div class="form-grid">

            <!-- Nombre -->
            <div class="form-group full">
                <label for="nombre">
                    Nombre completo <span class="required">*</span>
                </label>
                <input type="text" name="nombre" id="nombre"
                    placeholder="Ej: Carlos Mendoza"
                    value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                <span class="field-error" id="err_nombre"></span>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">
                    Correo electrónico <span class="required">*</span>
                </label>
                <input type="email" name="email" id="email"
                    placeholder="ejemplo@correo.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <span class="field-error" id="err_email"></span>
            </div>

            <!-- Rol -->
            <div class="form-group">
                <label for="rol">
                    Rol <span class="required">*</span>
                </label>
                <select name="rol" id="rol" onchange="toggleRolExtra()">
                    <option value="">Seleccione un rol</option>
                    <option value="Admin" <?= ($_POST['rol'] ?? '') === 'Admin'       ? 'selected' : '' ?>>Admin</option>
                    <option value="Propietario" <?= ($_POST['rol'] ?? '') === 'Propietario' ? 'selected' : '' ?>>Propietario</option>
                    <option value="Inquilino" <?= ($_POST['rol'] ?? '') === 'Inquilino'   ? 'selected' : '' ?>>Inquilino</option>
                </select>
                <span class="field-error" id="err_rol"></span>
            </div>

            <!-- Contraseña -->
            <div class="form-group">
                <label for="password">
                    Contraseña <span class="required">*</span>
                </label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password"
                        placeholder="Mínimo 6 caracteres">
                    <button type="button" class="toggle-password" onclick="togglePass()">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <span class="field-error" id="err_password"></span>
            </div>

            <!-- Propietario vinculado (solo si rol = Propietario) -->
            <div class="form-group full" id="grupo_propietario" style="display:none">
                <label for="propietario_id">
                    Vincular con propietario <span class="required">*</span>
                </label>
                <select name="propietario_id" id="propietario_id">
                    <option value="">Seleccione un propietario</option>
                    <?php foreach ($propietarios as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= ($_POST['propietario_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="rol-hint visible">
                    <strong><i class="fa-solid fa-circle-info"></i> ¿Por qué vincular?</strong>
                    Al vincular, el propietario solo verá sus propias propiedades y contratos al iniciar sesión.
                </div>
                <span class="field-error" id="err_propietario"></span>
            </div>

            <!-- Inquilino vinculado (solo si rol = Inquilino) -->
            <div class="form-group full" id="grupo_inquilino" style="display:none">
                <label for="inquilino_id">
                    Vincular con inquilino <span class="required">*</span>
                </label>
                <select name="inquilino_id" id="inquilino_id">
                    <option value="">Seleccione un inquilino</option>
                    <?php foreach ($inquilinos as $i): ?>
                        <option value="<?= $i['id'] ?>"
                            <?= ($_POST['inquilino_id'] ?? '') == $i['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($i['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="rol-hint visible">
                    <strong><i class="fa-solid fa-circle-info"></i> ¿Por qué vincular?</strong>
                    Al vincular, el inquilino solo verá sus propios contratos y pagos al iniciar sesión.
                </div>
                <span class="field-error" id="err_inquilino"></span>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Guardar usuario
            </button>
            <a href="../../public/index.php?action=usuarios" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i>
                Cancelar
            </a>
        </div>

    </form>
</div>

<script>
    function toggleRolExtra() {
        const rol = document.getElementById('rol').value;
        document.getElementById('grupo_propietario').style.display = rol === 'Propietario' ? 'block' : 'none';
        document.getElementById('grupo_inquilino').style.display = rol === 'Inquilino' ? 'block' : 'none';
    }

    function togglePass() {
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

    document.getElementById('formUsuario').addEventListener('submit', function(e) {
        let valido = true;

        document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

        const nombre = document.getElementById('nombre');
        const email = document.getElementById('email');
        const rol = document.getElementById('rol');
        const password = document.getElementById('password');

        if (!nombre.value.trim()) {
            nombre.classList.add('input-error');
            document.getElementById('err_nombre').textContent = 'El nombre es obligatorio';
            valido = false;
        }

        if (!email.value.trim()) {
            email.classList.add('input-error');
            document.getElementById('err_email').textContent = 'El email es obligatorio';
            valido = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            email.classList.add('input-error');
            document.getElementById('err_email').textContent = 'El email no es válido';
            valido = false;
        }

        if (!rol.value) {
            rol.classList.add('input-error');
            document.getElementById('err_rol').textContent = 'Selecciona un rol';
            valido = false;
        }

        if (!password.value) {
            password.classList.add('input-error');
            document.getElementById('err_password').textContent = 'La contraseña es obligatoria';
            valido = false;
        } else if (password.value.length < 6) {
            password.classList.add('input-error');
            document.getElementById('err_password').textContent = 'Mínimo 6 caracteres';
            valido = false;
        }

        if (rol.value === 'Propietario' && !document.getElementById('propietario_id').value) {
            document.getElementById('propietario_id').classList.add('input-error');
            document.getElementById('err_propietario').textContent = 'Debes vincular un propietario';
            valido = false;
        }

        if (rol.value === 'Inquilino' && !document.getElementById('inquilino_id').value) {
            document.getElementById('inquilino_id').classList.add('input-error');
            document.getElementById('err_inquilino').textContent = 'Debes vincular un inquilino';
            valido = false;
        }

        if (!valido) e.preventDefault();
    });

    // Inicializar estado del rol
    toggleRolExtra();
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>