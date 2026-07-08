<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php $contrato = $contrato ?? []; $rol = $_SESSION['rol']; ?>

<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .breadcrumb { display: flex; align-items: center; gap: 0.75rem; }
    .breadcrumb a { color: #6b7280; text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.3rem; }
    .separator { color: #d1d5db; }
    .page-title { font-size: 1.3rem; font-weight: 600; color: #1a2e44; display: flex; align-items: center; gap: 0.5rem; }
    .page-title i { color: #4da6ff; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-warning { background: #fffbeb; color: #d97706; } .btn-warning:hover { background: #fef3c7; }
    .btn-danger { background: #fef2f2; color: #dc2626; } .btn-danger:hover { background: #fee2e2; }
    .acciones { display: flex; gap: 0.5rem; }
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .detail-card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
    .detail-card.full { grid-column: 1 / -1; }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; font-size: 0.875rem; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 0.5rem; }
    .card-header i { color: #4da6ff; }
    .card-body { padding: 1.25rem; }
    .field-row { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid #f9fafb; font-size: 0.875rem; }
    .field-row:last-child { border-bottom: none; }
    .field-label { color: #6b7280; font-weight: 500; }
    .field-value { color: #1a2e44; font-weight: 500; }
    .badge { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 99px; }
    .badge-activo { background: #f0fdf4; color: #16a34a; }
    .badge-finalizado { background: #f3f4f6; color: #6b7280; }
    .badge-cancelado { background: #fef2f2; color: #dc2626; }
    .badge-pagado { background: #f0fdf4; color: #16a34a; }
    .badge-pendiente { background: #fffbeb; color: #d97706; }
    .badge-vencido { background: #fef2f2; color: #dc2626; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead { background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    th { padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.78rem; text-transform: uppercase; }
    td { padding: 0.75rem 1rem; color: #4b5563; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    .empty-state { text-align: center; padding: 2rem; color: #9ca3af; }
    @media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } .detail-card.full { grid-column: 1; } }
</style>

<div class="page-header">
    <div class="breadcrumb">
        <a href="http://localhost:8080/InmoCasa/public/index.php?action=contratos"><i class="fa-solid fa-arrow-left"></i> Contratos</a>
        <span class="separator">/</span>
        <div class="page-title"><i class="fa-solid fa-file-contract"></i> Contrato #<?= $contrato['id'] ?? '' ?></div>
    </div>
    <?php if ($rol === 'Admin'): ?>
        <div class="acciones">
            <a href="http://localhost:8080/InmoCasa/public/index.php?action=contrato_editar&id=<?= $contrato['id'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen"></i> Editar</a>
            <a href="http://localhost:8080/InmoCasa/public/index.php?action=contrato_eliminar&id=<?= $contrato['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este contrato?')"><i class="fa-solid fa-trash"></i> Eliminar</a>
        </div>
    <?php endif; ?>
</div>

<div class="detail-grid">
    <div class="detail-card">
        <div class="card-header"><i class="fa-solid fa-house"></i> Propiedad</div>
        <div class="card-body">
            <div class="field-row"><span class="field-label">Dirección</span><span class="field-value"><?= htmlspecialchars($contrato['propiedad_direccion'] ?? '') ?></span></div>
        </div>
    </div>
    <div class="detail-card">
        <div class="card-header"><i class="fa-solid fa-person"></i> Inquilino</div>
        <div class="card-body">
            <div class="field-row"><span class="field-label">Nombre</span><span class="field-value"><?= htmlspecialchars($contrato['inquilino_nombre'] ?? $contrato['nombre_inquilino'] ?? '') ?></span></div>
        </div>
    </div>
    <div class="detail-card">
        <div class="card-header"><i class="fa-solid fa-file-contract"></i> Datos del contrato</div>
        <div class="card-body">
            <div class="field-row"><span class="field-label">Estado</span><span class="field-value"><span class="badge badge-<?= strtolower($contrato['estado'] ?? '') ?>"><?= htmlspecialchars($contrato['estado'] ?? '') ?></span></span></div>
            <div class="field-row"><span class="field-label">Fecha inicio</span><span class="field-value"><?= isset($contrato['fecha_inicio']) ? date('d/m/Y', strtotime($contrato['fecha_inicio'])) : '' ?></span></div>
            <div class="field-row"><span class="field-label">Fecha fin</span><span class="field-value"><?= isset($contrato['fecha_fin']) ? date('d/m/Y', strtotime($contrato['fecha_fin'])) : '' ?></span></div>
            <div class="field-row"><span class="field-label">Monto mensual</span><span class="field-value">$<?= number_format($contrato['monto_mensual'] ?? 0, 2) ?></span></div>
        </div>
    </div>
    <div class="detail-card">
        <div class="card-header"><i class="fa-solid fa-note-sticky"></i> Observaciones</div>
        <div class="card-body">
            <p style="font-size:0.875rem;color:#4b5563"><?= !empty($contrato['observaciones']) ? htmlspecialchars($contrato['observaciones']) : 'Sin observaciones.' ?></p>
        </div>
    </div>
    <div class="detail-card full">
        <div class="card-header">
            <i class="fa-solid fa-credit-card"></i> Pagos del contrato
            <?php if ($rol === 'Admin'): ?>
                <a href="http://localhost:8080/InmoCasa/public/index.php?action=pago_crear&contrato_id=<?= $contrato['id'] ?>" style="margin-left:auto;font-size:0.8rem;color:#4da6ff;text-decoration:none;font-weight:500;">
                    <i class="fa-solid fa-plus"></i> Registrar pago
                </a>
            <?php endif; ?>
        </div>
        <?php
        include_once __DIR__ . '/../../models/Pago.php';
        $pagoModelo = new Pago();
        $pagos = $pagoModelo->obtenerPorContrato($contrato['id']);
        ?>
        <?php if (empty($pagos)): ?>
            <div class="empty-state"><i class="fa-solid fa-receipt" style="font-size:2rem;display:block;margin-bottom:0.5rem;color:#d1d5db"></i> No hay pagos registrados</div>
        <?php else: ?>
            <table>
                <thead><tr><th>#</th><th>Mes</th><th>Monto</th><th>Fecha pago</th><th>Estado</th><?php if ($rol === 'Admin'): ?><th>Acciones</th><?php endif; ?></tr></thead>
                <tbody>
                    <?php foreach ($pagos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['mes_correspondiente']) ?></td>
                        <td>$<?= number_format($p['monto'], 2) ?></td>
                        <td><?= $p['fecha_pago'] ? date('d/m/Y', strtotime($p['fecha_pago'])) : '<span style="color:#9ca3af">—</span>' ?></td>
                        <td><span class="badge badge-<?= strtolower($p['estado']) ?>"><?= $p['estado'] ?></span></td>
                        <?php if ($rol === 'Admin'): ?>
                        <td>
                            <a href="http://localhost:8080/InmoCasa/public/index.php?action=pago_editar&id=<?= $p['id'] ?>" class="btn btn-warning" style="padding:0.3rem 0.7rem;font-size:0.78rem"><i class="fa-solid fa-pen"></i></a>
                            <a href="http://localhost:8080/InmoCasa/public/index.php?action=pago_eliminar&id=<?= $p['id'] ?>" class="btn btn-danger" style="padding:0.3rem 0.7rem;font-size:0.78rem" onclick="return confirm('¿Eliminar este pago?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>