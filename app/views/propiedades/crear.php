<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php $errores = $_SESSION['errores'] ?? []; unset($_SESSION['errores']); ?>

<style>
    .page-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; }
    .page-header a { color: #6b7280; text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.3rem; }
    .page-header a:hover { color: #1a2e44; }
    .separator { color: #d1d5db; }
    .page-title { font-size: 1.3rem; font-weight: 600; color: #1a2e44; display: flex; align-items: center; gap: 0.5rem; }
    .page-title i { color: #4da6ff; }
    .form-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); padding: 1.75rem; max-width: 750px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }
    label { font-size: 0.875rem; font-weight: 500; color: #374151; }
    .required { color: #ef4444; margin-left: 2px; }
    input { padding: 0.65rem 0.9rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; color: #1a2e44; background: #fafafa; outline: none; transition: border-color 0.2s; font-family: inherit; }
    input:focus { border-color: #4da6ff; background: #fff; }
    .input-error { border-color: #ef4444 !important; }
    .field-error { font-size: 0.78rem; color: #ef4444; margin-top: 2px; }
    .alert-error { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1.5rem; font-size: 0.875rem; color: #dc2626; display: flex; gap: 0.5rem; }
    .alert-error ul { padding-left: 1.2rem; margin: 0; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #f3f4f6; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.65rem 1.25rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #1a2e44; color: #fff; } .btn-primary:hover { background: #243d57; }
    .btn-secondary { background: #f3f4f6; color: #374151; } .btn-secondary:hover { background: #e5e7eb; }
</style>

<div class="page-header">
    <a href="http://localhost:8080/InmoCasa/public/index.php?action=propietarios"><i class="fa-solid fa-arrow-left"></i> Propietarios</a>
    <span class="separator">/</span>
    <div class="page-title"><i class="fa-solid fa-plus"></i> Nuevo propietario</div>
</div>

<?php if (!empty($errores)): ?>
    <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i><ul><?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="form-card">
    <form id="formPropietario" method="POST" action="http://localhost:8080/InmoCasa/public/index.php?action=propietario_guardar" novalidate>
        <div class="form-grid">
            <div class="form-group full">
                <label>Nombre completo <span class="required">*</span></label>
                <input type="text" name="nombre" placeholder="Ej: Carlos Mendoza" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                <span class="field-error" id="err_nombre"></span>
            </div>
            <div class="form-group">
                <label>Cédula <span class="required">*</span></label>
                <input type="text" name="cedula" placeholder="10 dígitos" value="<?= htmlspecialchars($_POST['cedula'] ?? '') ?>">
                <span class="field-error" id="err_cedula"></span>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" placeholder="09XXXXXXXX" value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <span class="field-error" id="err_email"></span>
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            <a href="http://localhost:8080/InmoCasa/public/index.php?action=propietarios" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancelar</a>
        </div>
    </form>
</div>

<script>
document.getElementById('formPropietario').addEventListener('submit', function(e) {
    let valido = true;
    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    const nombre = document.querySelector('[name="nombre"]');
    const cedula = document.querySelector('[name="cedula"]');
    const email  = document.querySelector('[name="email"]');
    if (!nombre.value.trim()) { nombre.classList.add('input-error'); document.getElementById('err_nombre').textContent = 'El nombre es obligatorio'; valido = false; }
    if (!cedula.value.trim()) { cedula.classList.add('input-error'); document.getElementById('err_cedula').textContent = 'La cédula es obligatoria'; valido = false; }
    else if (!/^\d{10}$/.test(cedula.value)) { cedula.classList.add('input-error'); document.getElementById('err_cedula').textContent = 'La cédula debe tener 10 dígitos'; valido = false; }
    if (!email.value.trim()) { email.classList.add('input-error'); document.getElementById('err_email').textContent = 'El email es obligatorio'; valido = false; }
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) { email.classList.add('input-error'); document.getElementById('err_email').textContent = 'El email no es válido'; valido = false; }
    if (!valido) e.preventDefault();
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>