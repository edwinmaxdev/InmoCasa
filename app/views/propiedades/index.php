<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$propiedades = $propiedades ?? [];
$tipos = $tipos ?? [];
$rol = $_SESSION['rol'];
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
    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
    .table-toolbar { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
    .search-box { display: flex; align-items: center; gap: 0.5rem; background: #f8fafc; border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 0.45rem 0.85rem; }
    .search-box input { border: none; background: none; outline: none; font-size: 0.875rem; color: #1a2e44; width: 220px; }
    .filters { display: flex; gap: 0.5rem; align-items: center; }
    .filter-select { padding: 0.45rem 0.85rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem; background: #fff; color: #374151; outline: none; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead { background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    th { padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.4px; }
    td { padding: 0.75rem 1rem; color: #4b5563; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tr:last-child td { border-bottom: none; } tr:hover td { background: #f9fafb; }
    .acciones { display: flex; gap: 0.4rem; }
    .badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    .badge-disponible { background: #dcfce7; color: #15803d; }
    .badge-arrendada { background: #dbeafe; color: #1d4ed8; }
    .badge-venta { background: #fef3c7; color: #b45309; }
    .badge-vendida { background: #f3f4f6; color: #4b5563; }
    .empty-state { text-align: center; padding: 3rem; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; color: #d1d5db; }
</style>

<div class="page-header">
    <div class="page-title"><i class="fa-solid fa-house"></i> Propiedades</div>
    <?php if ($rol === 'Admin'): ?>
        <a href="<?= BASE_URL ?>?action=propiedad_crear" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nueva propiedad
        </a>
    <?php endif; ?>
</div>

<div class="table-wrap">
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af"></i>
            <input type="text" id="buscador" placeholder="Buscar dirección..." onkeyup="filtrarTabla()">
        </div>
        <div class="filters">
            <select class="filter-select" id="filtroEstado" onchange="aplicarFiltros()">
                <option value="">Todos los estados</option>
                <option value="Disponible">Disponible</option>
                <option value="Arrendada">Arrendada</option>
                <option value="En venta">En venta</option>
                <option value="Vendida">Vendida</option>
            </select>
            <select class="filter-select" id="filtroTipo" onchange="aplicarFiltros()">
                <option value="">Todos los tipos</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= htmlspecialchars($t['nombre']) ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php if (empty($propiedades)): ?>
        <div class="empty-state"><i class="fa-solid fa-house-circle-xmark"></i> No hay propiedades registradas</div>
    <?php else: ?>
        <table id="tablaPropiedades">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Dirección</th>
                    <th>Tipo</th>
                    <th>Propietario</th>
                    <th>Precio</th>
                    <th>Metros²</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($propiedades as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['direccion']) ?></td>
                    <td><?= htmlspecialchars($p['tipo_nombre']) ?></td>
                    <td><?= htmlspecialchars($p['propietario_nombre']) ?></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                    <td><?= number_format($p['metros2'], 2) ?> m²</td>
                    <td>
                        <?php
                        $badgeClass = 'badge-disponible';
                        if ($p['estado'] === 'Arrendada') $badgeClass = 'badge-arrendada';
                        elseif ($p['estado'] === 'En venta') $badgeClass = 'badge-venta';
                        elseif ($p['estado'] === 'Vendida') $badgeClass = 'badge-vendida';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($p['estado']) ?></span>
                    </td>
                    <td>
                        <div class="acciones">
                            <a href="<?= BASE_URL ?>?action=propiedad_detalle&id=<?= $p['id'] ?>" class="btn btn-sm btn-info" title="Ver"><i class="fa-solid fa-eye"></i></a>
                            <?php if ($rol === 'Admin'): ?>
                                <a href="<?= BASE_URL ?>?action=propiedad_editar&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fa-solid fa-pen"></i></a>
                                <a href="<?= BASE_URL ?>?action=propiedad_eliminar&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar esta propiedad?')"><i class="fa-solid fa-trash"></i></a>
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
    aplicarFiltros();
}

function aplicarFiltros() {
    const buscar = document.getElementById('buscador').value.toLowerCase();
    const estado = document.getElementById('filtroEstado').value.toLowerCase();
    const tipo = document.getElementById('filtroTipo').value.toLowerCase();

    document.querySelectorAll('#tablaPropiedades tbody tr').forEach(fila => {
        const cDireccion = fila.cells[1].textContent.toLowerCase();
        const cTipo = fila.cells[2].textContent.toLowerCase();
        const cEstado = fila.cells[6].textContent.toLowerCase();

        const matchBuscar = cDireccion.includes(buscar);
        const matchEstado = !estado || cEstado.trim() === estado.trim();
        const matchTipo = !tipo || cTipo.trim() === tipo.trim();

        if (matchBuscar && matchEstado && matchTipo) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>