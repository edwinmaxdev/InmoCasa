<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$rol   = $_SESSION['rol'];
$pagos = $pagos ?? [];
?>

<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1a2e44;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-title i { color: #4da6ff; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.1rem;
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
    .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
    .btn-info    { background: #eff6ff; color: #3b82f6; }
    .btn-warning { background: #fffbeb; color: #d97706; }
    .btn-danger  { background: #fef2f2; color: #dc2626; }
    .btn-info:hover    { background: #dbeafe; }
    .btn-warning:hover { background: #fef3c7; }
    .btn-danger:hover  { background: #fee2e2; }

    .table-wrap {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .table-toolbar {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f8fafc;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.45rem 0.85rem;
    }

    .search-box input {
        border: none;
        background: none;
        outline: none;
        font-size: 0.875rem;
        color: #1a2e44;
        width: 220px;
    }

    .filter-select {
        padding: 0.45rem 0.85rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #374151;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
    }

    .filters { display: flex; gap: 0.5rem; flex-wrap: wrap; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    thead { background: #f8fafc; border-bottom: 1px solid #e5e7eb; }

    th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }

    td {
        padding: 0.75rem 1rem;
        color: #4b5563;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f9fafb; }

    .badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.2rem 0.65rem;
        border-radius: 99px;
        white-space: nowrap;
    }

    .badge-pagado   { background: #f0fdf4; color: #16a34a; }
    .badge-pendiente{ background: #fffbeb; color: #d97706; }
    .badge-vencido  { background: #fef2f2; color: #dc2626; }

    .acciones { display: flex; gap: 0.4rem; flex-wrap: wrap; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: 0.75rem;
        color: #d1d5db;
    }

    @media (max-width: 768px) {
        table { font-size: 0.8rem; }
        th, td { padding: 0.6rem 0.75rem; }
        .search-box input { width: 140px; }
    }
</style>

<!-- Encabezado -->
<div class="page-header">
    <div class="page-title">
        <i class="fa-solid fa-credit-card"></i>
        Pagos
    </div>
    <?php if ($rol === 'Admin'): ?>
        <a href="<?= BASE_URL ?>?action=pago_crear" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Registrar pago
        </a>
    <?php endif; ?>
</div>

<!-- Tabla -->
<div class="table-wrap">
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af"></i>
            <input type="text" id="buscador"
                   placeholder="Buscar por inquilino o mes..."
                   onkeyup="filtrarTabla()">
        </div>
        <div class="filters">
            <select class="filter-select" id="filtroEstado" onchange="filtrarTabla()">
                <option value="">Todos los estados</option>
                <option value="Pagado">Pagado</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Vencido">Vencido</option>
            </select>
        </div>
    </div>

    <?php if (empty($pagos)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            No hay pagos registrados
        </div>
    <?php else: ?>
        <table id="tablaPagos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Inquilino</th>
                    <th>Mes</th>
                    <th>Monto</th>
                    <th>Fecha de pago</th>
                    <th>Estado</th>
                    <?php if ($rol === 'Admin'): ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['inquilino_nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['mes_correspondiente']) ?></td>
                    <td>$<?= number_format($p['monto'], 2) ?></td>
                    <td>
                        <?= $p['fecha_pago']
                            ? date('d/m/Y', strtotime($p['fecha_pago']))
                            : '<span style="color:#9ca3af">—</span>' ?>
                    </td>
                    <td>
                        <span class="badge badge-<?= strtolower($p['estado']) ?>">
                            <?= $p['estado'] ?>
                        </span>
                    </td>
                    <?php if ($rol === 'Admin'): ?>
                    <td>
                        <div class="acciones">
                            <a href="<?= BASE_URL ?>?action=pago_editar&id=<?= $p['id'] ?>"
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="<?= BASE_URL ?>?action=pago_eliminar&id=<?= $p['id'] ?>"
                               class="btn btn-sm btn-danger" title="Eliminar"
                               onclick="return confirm('¿Eliminar este pago?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                    <?php endif; ?>
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
    const filas  = document.querySelectorAll('#tablaPagos tbody tr');

    filas.forEach(fila => {
        const texto      = fila.textContent.toLowerCase();
        const estadoFila = fila.querySelector('.badge')?.textContent.trim().toLowerCase() ?? '';
        const coincideBuscar = texto.includes(buscar);
        const coincideEstado = estado === '' || estadoFila === estado;
        fila.style.display = coincideBuscar && coincideEstado ? '' : 'none';
    });
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>