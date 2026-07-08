<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$rol = $_SESSION['rol'];
$contratos = $contratos ?? [];
$proximosAVencer = $proximosAVencer ?? [];
?>

<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .page-title { font-size: 1.3rem; font-weight: 600; color: #1a2e44; display: flex; align-items: center; gap: 0.5rem; }
    .page-title i { color: #4da6ff; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #1a2e44; color: #fff; } .btn-primary:hover { background: #243d57; }
    .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
    .btn-info { background: #eff6ff; color: #3b82f6; } .btn-info:hover { background: #dbeafe; }
    .btn-warning { background: #fffbeb; color: #d97706; } .btn-warning:hover { background: #fef3c7; }
    .btn-danger { background: #fef2f2; color: #dc2626; } .btn-danger:hover { background: #fee2e2; }
    .alerta-vencer { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem; }
    .alerta-vencer i { color: #f59e0b; font-size: 1.1rem; margin-top: 2px; }
    .alerta-vencer-title { font-weight: 600; color: #92400e; font-size: 0.9rem; margin-bottom: 0.25rem; }
    .alerta-vencer-list { font-size: 0.82rem; color: #78350f; margin: 0; padding-left: 1.2rem; }
    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
    .table-toolbar { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
    .search-box { display: flex; align-items: center; gap: 0.5rem; background: #f8fafc; border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 0.45rem 0.85rem; }
    .search-box input { border: none; background: none; outline: none; font-size: 0.875rem; color: #1a2e44; width: 220px; }
    .filter-select { padding: 0.45rem 0.85rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem; color: #374151; background: #f8fafc; outline: none; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead { background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    th { padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.4px; }
    td { padding: 0.75rem 1rem; color: #4b5563; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tr:last-child td { border-bottom: none; } tr:hover td { background: #f9fafb; }
    .badge { font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.65rem; border-radius: 99px; }
    .badge-activo { background: #f0fdf4; color: #16a34a; }
    .badge-finalizado { background: #f3f4f6; color: #6b7280; }
    .badge-cancelado { background: #fef2f2; color: #dc2626; }
    .acciones { display: flex; gap: 0.4rem; }
    .empty-state { text-align: center; padding: 3rem; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; color: #d1d5db; }
</style>

<div class="page-header">
    <div class="page-title"><i class="fa-solid fa-file-contract"></i> Contratos</div>
    <?php if ($rol === 'Admin'): ?>
        <a href="<?= BASE_URL ?>?action=contrato_crear" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo contrato
        </a>
    <?php endif; ?>
</div>

<?php if (!empty($proximosAVencer)): ?>
    <div class="alerta-vencer">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <div class="alerta-vencer-title"><?= count($proximosAVencer) ?> contrato(s) próximo(s) a vencer en 30 días</div>
            <ul class="alerta-vencer-list">
                <?php foreach ($proximosAVencer as $c): ?>
                    <li><?= htmlspecialchars($c['propiedad_direccion']) ?> — <?= htmlspecialchars($c['inquilino_nombre']) ?> — vence en <strong><?= $c['dias_restantes'] ?> día(s)</strong></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<div class="table-wrap">
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af"></i>
            <input type="text" id="buscador" placeholder="Buscar por propiedad o inquilino..." onkeyup="filtrarTabla()">
        </div>
        <select class="filter-select" id="filtroEstado" onchange="filtrarTabla()">
            <option value="">Todos los estados</option>
            <option value="Activo">Activo</option>
            <option value="Finalizado">Finalizado</option>
            <option value="Cancelado">Cancelado</option>
        </select>
    </div>
    <?php if (empty($contratos)): ?>
        <div class="empty-state"><i class="fa-solid fa-file-circle-xmark"></i> No hay contratos registrados</div>
    <?php else: ?>
        <table id="tablaContratos">
            <thead>
                <tr><th>#</th><th>Propiedad</th><th>Inquilino</th><th>Inicio</th><th>Fin</th><th>Monto</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($contratos as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['propiedad_direccion']) ?></td>
                    <td><?= htmlspecialchars($c['inquilino_nombre'] ?? $c['nombre_inquilino'] ?? '') ?></td>
                    <td><?= date('d/m/Y', strtotime($c['fecha_inicio'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($c['fecha_fin'])) ?></td>
                    <td>$<?= number_format($c['monto_mensual'], 2) ?></td>
                    <td><span class="badge badge-<?= strtolower($c['estado']) ?>"><?= $c['estado'] ?></span></td>
                    <td>
                        <div class="acciones">
                            <a href="<?= BASE_URL ?>?action=contrato_detalle&id=<?= $c['id'] ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
                            <?php if ($rol === 'Admin'): ?>
                                <a href="<?= BASE_URL ?>?action=contrato_editar&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= BASE_URL ?>?action=contrato_eliminar&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este contrato?')"><i class="fa-solid fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
function filtrarTabla() {
    const buscar = document.getElementById('buscador').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    document.querySelectorAll('#tablaContratos tbody tr').forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        const estadoFila = fila.querySelector('.badge')?.textContent.trim().toLowerCase() ?? '';
        fila.style.display = texto.includes(buscar) && (estado === '' || estadoFila === estado) ? '' : 'none';
    });
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>