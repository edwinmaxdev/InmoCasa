<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$errores   = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
$pago      = $pago      ?? [];
$contratos = $contratos ?? [];
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

    .fecha-hint {
        font-size: 0.78rem;
        color: #6b7280;
        margin-top: 2px;
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

    .btn-primary { background: #1a2e44; color: #fff; }
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
    <a href="<?= BASE_URL ?>?action=pagos">
        <i class="fa-solid fa-arrow-left"></i> Pagos
    </a>
    <span class="separator">/</span>
    <div class="page-title">
        <i class="fa-solid fa-pen"></i>
        Editar pago #<?= $pago['id'] ?? '' ?>
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
    <form id="formPago" method="POST"
          action="<?= BASE_URL ?>?action=pago_actualizar&id=<?= $pago['id'] ?? '' ?>"
          novalidate>

        <div class="form-grid">

            <!-- Contrato -->
            <div class="form-group full">
                <label for="contrato_id">
                    Contrato <span class="required">*</span>
                </label>
                <select name="contrato_id" id="contrato_id">
                    <option value="">Seleccione un contrato</option>
                    <?php foreach ($contratos as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= $c['id'] == ($pago['contrato_id'] ?? '') ? 'selected' : '' ?>>
                            #<?= $c['id'] ?> —
                            <?= htmlspecialchars($c['propiedad_direccion'] ?? '') ?> /
                            <?= htmlspecialchars($c['inquilino_nombre'] ?? $c['nombre_inquilino'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="field-error" id="err_contrato"></span>
            </div>

            <!-- Mes correspondiente -->
            <div class="form-group">
                <label for="mes_correspondiente">
                    Mes correspondiente <span class="required">*</span>
                </label>
                <input type="text" name="mes_correspondiente" id="mes_correspondiente"
                       placeholder="Ej: Enero 2025"
                       value="<?= htmlspecialchars($pago['mes_correspondiente'] ?? '') ?>">
                <span class="field-error" id="err_mes"></span>
            </div>

            <!-- Monto -->
            <div class="form-group">
                <label for="monto">
                    Monto ($) <span class="required">*</span>
                </label>
                <input type="number" name="monto" id="monto"
                       step="0.01" min="0.01" placeholder="0.00"
                       value="<?= htmlspecialchars($pago['monto'] ?? '') ?>">
                <span class="field-error" id="err_monto"></span>
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label for="estado">
                    Estado <span class="required">*</span>
                </label>
                <select name="estado" id="estado" onchange="toggleFechaPago()">
                    <?php foreach (['Pendiente', 'Pagado', 'Vencido'] as $est): ?>
                        <option value="<?= $est ?>"
                            <?= $est === ($pago['estado'] ?? '') ? 'selected' : '' ?>>
                            <?= $est ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Fecha de pago -->
            <div class="form-group">
                <label for="fecha_pago">
                    Fecha de pago
                    <span class="required" id="fecha_requerida">*</span>
                </label>
                <input type="date" name="fecha_pago" id="fecha_pago"
                       value="<?= htmlspecialchars($pago['fecha_pago'] ?? '') ?>">
                <span class="fecha-hint" id="fecha_hint">Obligatoria cuando el estado es Pagado</span>
                <span class="field-error" id="err_fecha"></span>
            </div>

            <!-- Observaciones -->
            <div class="form-group full">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones"
                          placeholder="Notas adicionales del pago..."><?= htmlspecialchars($pago['observaciones'] ?? '') ?></textarea>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Actualizar pago
            </button>
            <a href="<?= BASE_URL ?>?action=pagos" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i>
                Cancelar
            </a>
        </div>

    </form>
</div>

<script>
function toggleFechaPago() {
    const estado = document.getElementById('estado').value;
    const hint   = document.getElementById('fecha_hint');
    if (estado === 'Pagado') {
        hint.style.color = '#ef4444';
        hint.textContent = 'Obligatoria cuando el estado es Pagado';
    } else {
        hint.style.color = '#6b7280';
        hint.textContent = 'Opcional si el pago está pendiente';
    }
}

document.getElementById('formPago').addEventListener('submit', function(e) {
    let valido = true;

    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    const contrato = document.getElementById('contrato_id');
    const mes      = document.getElementById('mes_correspondiente');
    const monto    = document.getElementById('monto');
    const estado   = document.getElementById('estado');
    const fecha    = document.getElementById('fecha_pago');

    if (!contrato.value) {
        contrato.classList.add('input-error');
        document.getElementById('err_contrato').textContent = 'Selecciona un contrato';
        valido = false;
    }

    if (!mes.value.trim()) {
        mes.classList.add('input-error');
        document.getElementById('err_mes').textContent = 'El mes correspondiente es obligatorio';
        valido = false;
    }

    if (!monto.value || parseFloat(monto.value) <= 0) {
        monto.classList.add('input-error');
        document.getElementById('err_monto').textContent = 'El monto debe ser mayor a 0';
        valido = false;
    }

    if (estado.value === 'Pagado' && !fecha.value) {
        fecha.classList.add('input-error');
        document.getElementById('err_fecha').textContent = 'La fecha de pago es obligatoria cuando el estado es Pagado';
        valido = false;
    }

    if (!valido) e.preventDefault();
});

toggleFechaPago();
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>