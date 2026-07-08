<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
$tipos = $tipos ?? [];
$propietarios = $propietarios ?? [];
?>

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
    input, select, textarea { padding: 0.65rem 0.9rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; color: #1a2e44; background: #fafafa; outline: none; transition: border-color 0.2s; font-family: inherit; }
    input:focus, select:focus, textarea:focus { border-color: #4da6ff; background: #fff; }
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
    <a href="<?= BASE_URL ?>?action=propiedades"><i class="fa-solid fa-arrow-left"></i> Propiedades</a>
    <span class="separator">/</span>
    <div class="page-title"><i class="fa-solid fa-plus"></i> Nueva propiedad</div>
</div>

<?php if (!empty($errores)): ?>
    <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i><ul><?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="form-card">
    <form id="formPropiedad" method="POST" action="<?= BASE_URL ?>?action=propiedad_guardar" novalidate>
        <div class="form-grid">
            <div class="form-group full">
                <label>Dirección <span class="required">*</span></label>
                <input type="text" name="direccion" placeholder="Ej: Av. de los Shyris y Portugal" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
                <span class="field-error" id="err_direccion"></span>
            </div>
            <div class="form-group">
                <label>Precio <span class="required">*</span></label>
                <input type="number" step="0.01" name="precio" placeholder="Ej: 450.00" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>">
                <span class="field-error" id="err_precio"></span>
            </div>
            <div class="form-group">
                <label>Metros Cuadrados (m²) <span class="required">*</span></label>
                <input type="number" step="0.01" name="metros2" placeholder="Ej: 85.50" value="<?= htmlspecialchars($_POST['metros2'] ?? '') ?>">
                <span class="field-error" id="err_metros2"></span>
            </div>
            <div class="form-group">
                <label>Tipo de Inmueble <span class="required">*</span></label>
                <select name="tipo_id" id="tipo_id">
                    <option value="">Seleccione un tipo...</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= (isset($_POST['tipo_id']) && $_POST['tipo_id'] == $t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="err_tipo_id"></span>
            </div>
            <div class="form-group">
                <label>Propietario <span class="required">*</span></label>
                <select name="propietario_id" id="propietario_id">
                    <option value="">Seleccione un propietario...</option>
                    <?php foreach ($propietarios as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= (isset($_POST['propietario_id']) && $_POST['propietario_id'] == $p['id']) ? 'selected' : '' ?>><?= htmlspecialchars($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="err_propietario_id"></span>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="Disponible" <?= (isset($_POST['estado']) && $_POST['estado'] === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                    <option value="Arrendada" <?= (isset($_POST['estado']) && $_POST['estado'] === 'Arrendada') ? 'selected' : '' ?>>Arrendada</option>
                    <option value="En venta" <?= (isset($_POST['estado']) && $_POST['estado'] === 'En venta') ? 'selected' : '' ?>>En venta</option>
                    <option value="Vendida" <?= (isset($_POST['estado']) && $_POST['estado'] === 'Vendida') ? 'selected' : '' ?>>Vendida</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Descripción</label>
                <textarea name="descripcion" rows="4" placeholder="Detalles de la propiedad..."><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            <a href="<?= BASE_URL ?>?action=propiedades" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancelar</a>
        </div>
    </form>
</div>

<script>
document.getElementById('formPropiedad').addEventListener('submit', function(e) {
    let valido = true;
    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
    
    const direccion = document.querySelector('[name="direccion"]');
    const precio = document.querySelector('[name="precio"]');
    const metros2 = document.querySelector('[name="metros2"]');
    const tipo = document.getElementById('tipo_id');
    const propietario = document.getElementById('propietario_id');

    if (!direccion.value.trim()) { direccion.classList.add('input-error'); document.getElementById('err_direccion').textContent = 'La dirección es obligatoria'; valido = false; }
    
    if (!precio.value.trim() || isNaN(precio.value) || parseFloat(precio.value) <= 0) {
        precio.classList.add('input-error');
        document.getElementById('err_precio').textContent = 'El precio debe ser un número mayor a 0';
        valido = false;
    }
    
    if (!metros2.value.trim() || isNaN(metros2.value) || parseFloat(metros2.value) <= 0) {
        metros2.classList.add('input-error');
        document.getElementById('err_metros2').textContent = 'Los metros2 deben ser un número mayor a 0';
        valido = false;
    }

    if (!tipo.value) { tipo.classList.add('input-error'); document.getElementById('err_tipo_id').textContent = 'El tipo de inmueble es obligatorio'; valido = false; }
    if (!propietario.value) { propietario.classList.add('input-error'); document.getElementById('err_propietario_id').textContent = 'El propietario es obligatorio'; valido = false; }

    if (!valido) e.preventDefault();
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>