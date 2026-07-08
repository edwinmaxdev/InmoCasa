<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);

$contrato    = $contrato    ?? [];
$propiedades = $propiedades ?? [];
$inquilinos  = $inquilinos  ?? [];
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

    .page-header a:hover { color: #1a2e44; }
    .page-header .separator { color: #d1d5db; }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1a2e44;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-title i { color: #f59e0b; }

    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        padding: 1.75rem;
        max-width: 750px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }

    label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .required { color: #ef4444; margin-left: 2px; }

    input, select, textarea {
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

    input:focus, select:focus, textarea:focus {
        border-color: #4da6ff;
        background: #fff;
    }

    .input-error { border-color: #ef4444 !important; }
    .field-error { font-size: 0.78rem; color: #ef4444; margin-top: 2px; }

    textarea { resize: vertical; min-height: 80px; }

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

    .alert-error ul { padding-left: 1.2rem; margin: 0; }
    .alert-error ul li { margin-top: 2px; }

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

    .btn-primary   { background: #1a2e44; color: #fff; }
    .btn-primary:hover { background: #243d57; }
    .btn-secondary { background: #f3f4f6; color: #374151; }
    .btn-secondary:hover { background: #e5e7eb; }

    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full { grid-column: 1; }
    }
</style>

<!-- Breadcrumb -->
<div class="page-header">
    <a href="../../public/index.php?action=contratos">
        <i class="fa-solid fa-arrow-left"></i> Contratos
    </a>
    <span class="separator">/</span>
    <div class="page-title">
        <i class="fa-solid fa-pen"></i>
        Editar contrato #<?= $contrato['id'] ?? '' ?>
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
    <form id="formContrato" method="POST"
          action="../../public/index.php?action=contrato_actualizar&id=<?= $contrato['id'] ?? '' ?>"
          novalidate>

        <div class="form-grid">

            <!-- Propiedad -->
            <div class="form-group">
                <label for="propiedad_id">
                    Propiedad <span class="required">*</span>
                </label>
                <select name="propiedad_id" id="propiedad_id">
                    <option value="">Seleccione una propiedad</option>
                    <?php foreach ($propiedades as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= $p['id'] == ($contrato['propiedad_id'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['direccion']) ?>
                            (<?= htmlspecialchars($p['estado']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="err_propiedad"></span>
            </div>

            <!-- Inquilino -->
            <div class="form-group">
                <label for="inquilino_id">
                    Inquilino <span class="required">*</span>
                </label>
                <select name="inquilino_id" id="inquilino_id">
                    <option value="">Seleccione un inquilino</option>
                    <?php foreach ($inquilinos as $i): ?>
                        <option value="<?= $i['id'] ?>"
                            <?= $i['id'] == ($contrato['inquilino_id'] ?? '') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($i['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="err_inquilino"></span>
            </div>

            <!-- Fecha inicio -->
            <div class="form-group">
                <label for="fecha_inicio">
                    Fecha de inicio <span class="required">*</span>
                </label>
                <input type="date" name="fecha_inicio" id="fecha_inicio"
                       value="<?= htmlspecialchars($contrato['fecha_inicio'] ?? '') ?>">
                <span class="field-error" id="err_fecha_inicio"></span>
            </div>

            <!-- Fecha fin -->
            <div class="form-group">
                <label for="fecha_fin">
                    Fecha de fin <span class="required">*</span>
                </label>
                <input type="date" name="fecha_fin" id="fecha_fin"
                       value="<?= htmlspecialchars($contrato['fecha_fin'] ?? '') ?>">
                <span class="field-error" id="err_fecha_fin"></span>
            </div>

            <!-- Monto mensual -->
            <div class="form-group">
                <label for="monto_mensual">
                    Monto mensual ($) <span class="required">*</span>
                </label>
                <input type="number" name="monto_mensual" id="monto_mensual"
                       step="0.01" min="0.01" placeholder="0.00"
                       value="<?= htmlspecialchars($contrato['monto_mensual'] ?? '') ?>">
                <span class="field-error" id="err_monto"></span>
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label for="estado">Estado</label>
                <select name="estado" id="estado">
                    <?php foreach (['Activo', 'Finalizado', 'Cancelado'] as $est): ?>
                        <option value="<?= $est ?>"
                            <?= $est === ($contrato['estado'] ?? '') ? 'selected' : '' ?>>
                            <?= $est ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Observaciones -->
            <div class="form-group full">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones"
                          placeholder="Notas adicionales del contrato..."><?= htmlspecialchars($contrato['observaciones'] ?? '') ?></textarea>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Actualizar contrato
            </button>
            <a href="../../public/index.php?action=contratos" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i>
                Cancelar
            </a>
        </div>

    </form>
</div>

<script>
document.getElementById('formContrato').addEventListener('submit', function(e) {
    let valido = true;

    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    const propiedad   = document.getElementById('propiedad_id');
    const inquilino   = document.getElementById('inquilino_id');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin    = document.getElementById('fecha_fin');
    const monto       = document.getElementById('monto_mensual');

    if (!propiedad.value) {
        propiedad.classList.add('input-error');
        document.getElementById('err_propiedad').textContent = 'Selecciona una propiedad';
        valido = false;
    }

    if (!inquilino.value) {
        inquilino.classList.add('input-error');
        document.getElementById('err_inquilino').textContent = 'Selecciona un inquilino';
        valido = false;
    }

    if (!fechaInicio.value) {
        fechaInicio.classList.add('input-error');
        document.getElementById('err_fecha_inicio').textContent = 'La fecha de inicio es obligatoria';
        valido = false;
    }

    if (!fechaFin.value) {
        fechaFin.classList.add('input-error');
        document.getElementById('err_fecha_fin').textContent = 'La fecha de fin es obligatoria';
        valido = false;
    }

    if (fechaInicio.value && fechaFin.value && fechaFin.value <= fechaInicio.value) {
        fechaFin.classList.add('input-error');
        document.getElementById('err_fecha_fin').textContent = 'La fecha de fin debe ser mayor a la de inicio';
        valido = false;
    }

    if (!monto.value || parseFloat(monto.value) <= 0) {
        monto.classList.add('input-error');
        document.getElementById('err_monto').textContent = 'El monto debe ser mayor a 0';
        valido = false;
    }

    if (!valido) e.preventDefault();
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>