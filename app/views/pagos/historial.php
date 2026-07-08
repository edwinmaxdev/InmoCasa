<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$rol   = $_SESSION['rol'];
$pagos = $pagos ?? [];

// Calcular totales
$totalPagado   = array_sum(array_column(array_filter($pagos, fn($p) => $p['estado'] === 'Pagado'), 'monto'));
$totalPendiente= array_sum(array_column(array_filter($pagos, fn($p) => $p['estado'] === 'Pendiente'), 'monto'));
$totalVencido  = array_sum(array_column(array_filter($pagos, fn($p) => $p['estado'] === 'Vencido'), 'monto'));
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

    .page-title i { color: #4da6ff; }

    /* Cards resumen */
    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .resumen-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 0.85rem;
        border-left: 4px solid transparent;
    }

    .resumen-card.verde  { border-left-color: #22c55e; }
    .resumen-card.amarillo { border-left-color: #f59e0b; }
    .resumen-card.rojo   { border-left-color: #ef4444; }

    .resumen-icon {
        width: 42px; height: 42px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .icon-verde    { background: #f0fdf4; color: #22c55e; }
    .icon-amarillo { background: #fffbeb; color: #f59e0b; }
    .icon-rojo     { background: #fef2f2; color: #ef4444; }

    .resumen-info { flex: 1; }

    .resumen-valor {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a2e44;
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .resumen-label {
        font-size: 0.78rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Tabla */
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
        width: 200px;
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

    .badge-pagado    { background: #f0fdf4; color: #16a34a; }
    .badge-pendiente { background: #fffbeb; color: #d97706; }
    .badge-vencido   { background: #fef2f2; color: #dc2626; }

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
        .resumen-grid { grid-template-columns: 1fr; }
        table { font-size: 0.8rem; }
        th, td { padding: 0.6rem 0.75rem; }
    }
</style>

<!-- Breadcrumb -->
<div class="page-header">
    <a href="<?= BASE_URL ?>?action=pagos">
        <i class="fa-solid fa-arrow-left"></i> Pagos
    </a>
    <span class="separator">/</span>
    <div class="page-title">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Historial de pagos
    </div>
</div>

<!-- Cards resumen -->
<div class="resumen-grid">
    <div class="resumen-card verde">
        <div class="resumen-icon icon-verde">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="resumen-info">
            <div class="resumen-valor">$<?= number_format($totalPagado, 2) ?></div>
            <div class="resumen-label">Total pagado</div>
        </div>
    </div>

    <div class="resumen-card amarillo">
        <div class="resumen-icon icon-amarillo">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="resumen-info">
            <div class="resumen-valor">$<?= number_format($totalPendiente, 2) ?></div>
            <div class="resumen-label">Total pendiente</div>
        </div>
    </div>

    <div class="resumen-card rojo">
        <div class="resumen-icon icon-rojo">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="resumen-info">
            <div class="resumen-valor">$<?= number_format($totalVencido, 2) ?></div>
            <div class="resumen-label">Total vencido</div>
        </div>
    </div>
</div>

<!-- Tabla historial -->
<div class="table-wrap">
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af"></i>
            <input type="text" id="buscador"
                   placeholder="Buscar por propiedad o mes..."
                   onkeyup="filtrarTabla()">
        </div>
        <select class="filter-select" id="filtroEstado" onchange="filtrarTabla()">
            <option value="">Todos los estados</option>
            <option value="Pagado">Pagado</option>
            <option value="Pendiente">Pendiente</option>
            <option value="Vencido">Vencido</option>
        </select>
    </div>

    <?php if (empty($pagos)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            No hay pagos en el historial
        </div>
    <?php else: ?>
        <table id="tablaHistorial">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Propiedad</th>
                    <th>Mes</th>
                    <th>Monto</th>
                    <th>Fecha de pago</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['propiedad_direccion'] ?? '') ?></td>
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
    const filas  = document.querySelectorAll('#tablaHistorial tbody tr');

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