<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$propiedad = $propiedad ?? [];
$rol = $_SESSION['rol'];
?>

<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .breadcrumb { display: flex; align-items: center; gap: 0.75rem; }
    .breadcrumb a { color: #6b7280; text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.3rem; }
    .breadcrumb a:hover { color: #1a2e44; }
    .separator { color: #d1d5db; }
    .page-title { font-size: 1.3rem; font-weight: 600; color: #1a2e44; display: flex; align-items: center; gap: 0.5rem; }
    .page-title i { color: #4da6ff; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-warning { background: #fffbeb; color: #d97706; } .btn-warning:hover { background: #fef3c7; }
    .btn-danger { background: #fef2f2; color: #dc2626; } .btn-danger:hover { background: #fee2e2; }
    .btn-secondary { background: #f3f4f6; color: #374151; } .btn-secondary:hover { background: #e5e7eb; }
    .acciones { display: flex; gap: 0.5rem; }
    .detail-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 1.5rem; max-width: 750px; }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; font-size: 0.875rem; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 0.5rem; }
    .card-header i { color: #4da6ff; }
    .card-body { padding: 1.25rem; }
    .field-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #f9fafb; font-size: 0.875rem; }
    .field-row:last-child { border-bottom: none; }
    .field-label { color: #6b7280; font-weight: 500; }
    .field-value { color: #1a2e44; font-weight: 500; }
    .badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    .badge-disponible { background: #dcfce7; color: #15803d; }
    .badge-arrendada { background: #dbeafe; color: #1d4ed8; }
    .badge-venta { background: #fef3c7; color: #b45309; }
    .badge-vendida { background: #f3f4f6; color: #4b5563; }
</style>

<div class="page-header">
    <div class="breadcrumb">
        <a href="<?= BASE_URL ?>?action=propiedades"><i class="fa-solid fa-arrow-left"></i> Propiedades</a>
        <span class="separator">/</span>
        <div class="page-title"><i class="fa-solid fa-house"></i> Propiedad #<?= htmlspecialchars($propiedad['id'] ?? '') ?></div>
    </div>
    <div class="acciones">
        <?php if ($rol === 'Admin'): ?>
            <a href="<?= BASE_URL ?>?action=propiedad_editar&id=<?= $propiedad['id'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen"></i> Editar</a>
            <a href="<?= BASE_URL ?>?action=propiedad_eliminar&id=<?= $propiedad['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar esta propiedad?')"><i class="fa-solid fa-trash"></i> Eliminar</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>?action=propiedades" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>
</div>

<div class="detail-card">
    <div class="card-header"><i class="fa-solid fa-circle-info"></i> Detalles de la propiedad</div>
    <div class="card-body">
        <div class="field-row"><span class="field-label">Dirección</span><span class="field-value"><?= htmlspecialchars($propiedad['direccion'] ?? '') ?></span></div>
        <div class="field-row"><span class="field-label">Tipo de Inmueble</span><span class="field-value"><?= htmlspecialchars($propiedad['tipo_nombre'] ?? '') ?></span></div>
        <div class="field-row"><span class="field-label">Propietario</span><span class="field-value"><?= htmlspecialchars($propiedad['propietario_nombre'] ?? '') ?></span></div>
        <div class="field-row"><span class="field-label">Precio</span><span class="field-value">$<?= number_format($propiedad['precio'] ?? 0, 2) ?></span></div>
        <div class="field-row"><span class="field-label">Metros Cuadrados</span><span class="field-value"><?= number_format($propiedad['metros2'] ?? 0, 2) ?> m²</span></div>
        <div class="field-row">
            <span class="field-label">Estado</span>
            <span class="field-value">
                <?php
                $badgeClass = 'badge-disponible';
                if (($propiedad['estado'] ?? '') === 'Arrendada') $badgeClass = 'badge-arrendada';
                elseif (($propiedad['estado'] ?? '') === 'En venta') $badgeClass = 'badge-venta';
                elseif (($propiedad['estado'] ?? '') === 'Vendida') $badgeClass = 'badge-vendida';
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($propiedad['estado'] ?? '') ?></span>
            </span>
        </div>
        <div class="field-row" style="flex-direction: column; align-items: flex-start; gap: 0.5rem;">
            <span class="field-label">Descripción</span>
            <span class="field-value" style="font-weight: normal; color: #4b5563; line-height: 1.5;"><?= nl2br(htmlspecialchars($propiedad['descripcion'] ?? 'Sin descripción')) ?></span>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>