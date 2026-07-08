<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$inquilino = $inquilino ?? [];
$rol = $_SESSION['rol'];
?>

<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .breadcrumb { display: flex; align-items: center; gap: 0.75rem; }
    .breadcrumb a { color: #6b7280; text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.3rem; }
    .breadcrumb a:hover { color: #1a2e44; }
    .breadcrumb .separator { color: #d1d5db; }
    .page-title { font-size: 1.3rem; font-weight: 600; color: #1a2e44; display: flex; align-items: center; gap: 0.5rem; }
    .page-title i { color: #4da6ff; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-warning { background: #fffbeb; color: #d97706; }
    .btn-warning:hover { background: #fef3c7; }
    .btn-danger  { background: #fef2f2; color: #dc2626; }
    .btn-danger:hover  { background: #fee2e2; }
    .acciones { display: flex; gap: 0.5rem; }
    .detail-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 1.5rem; }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; font-size: 0.875rem; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 0.5rem; }
    .card-header i { color: #4da6ff; }
    .card-body { padding: 1.25rem; }
    .field-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #f9fafb; font-size: 0.875rem; }
    .field-row:last-child { border-bottom: none; }
    .field-label { color: #6b7280; font-weight: 500; }
    .field-value { color: #1a2e44; font-weight: 500; }
</style>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>?action=inquilinos">
            <i class="fa-solid fa-arrow-left"></i> Inquilinos
        </a>
        <span class="separator">/</span>
        <div class="page-title">
            <i class="fa-solid fa-person"></i>
            <?= htmlspecialchars($inquilino['nombre'] ?? '') ?>
        </div>
    </div>
    <?php if ($rol === 'Admin'): ?>
        <div class="acciones">
            <a href="<?= BASE_URL ?>?action=inquilino_editar&id=<?= $inquilino['id'] ?>"
               class="btn btn-warning">
                <i class="fa-solid fa-pen"></i> Editar
            </a>
            <a href="<?= BASE_URL ?>?action=inquilino_eliminar&id=<?= $inquilino['id'] ?>"
               class="btn btn-danger"
               onclick="return confirm('¿Eliminar este inquilino?')">
                <i class="fa-solid fa-trash"></i> Eliminar
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="detail-card">
    <div class="card-header">
        <i class="fa-solid fa-person"></i> Datos del inquilino
    </div>
    <div class="card-body">
        <div class="field-row">
            <span class="field-label">Nombre</span>
            <span class="field-value"><?= htmlspecialchars($inquilino['nombre'] ?? '') ?></span>
        </div>
        <div class="field-row">
            <span class="field-label">Cédula</span>
            <span class="field-value"><?= htmlspecialchars($inquilino['cedula'] ?? '') ?></span>
        </div>
        <div class="field-row">
            <span class="field-label">Teléfono</span>
            <span class="field-value"><?= htmlspecialchars($inquilino['telefono'] ?? '—') ?></span>
        </div>
        <div class="field-row">
            <span class="field-label">Email</span>
            <span class="field-value"><?= htmlspecialchars($inquilino['email'] ?? '') ?></span>
        </div>
        <div class="field-row">
            <span class="field-label">Dirección</span>
            <span class="field-value"><?= htmlspecialchars($inquilino['direccion'] ?? '—') ?></span>
        </div>
        <div class="field-row">
            <span class="field-label">Referencia</span>
            <span class="field-value"><?= htmlspecialchars($inquilino['referencia'] ?? '—') ?></span>
        </div>
        <div class="field-row">
            <span class="field-label">Registrado</span>
            <span class="field-value"><?= isset($inquilino['created_at']) ? date('d/m/Y', strtotime($inquilino['created_at'])) : '—' ?></span>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>