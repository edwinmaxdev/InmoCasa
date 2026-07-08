<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$inquilinos = $inquilinos ?? [];
$rol = $_SESSION['rol'];
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

    .btn-primary  { background: #1a2e44; color: #fff; }
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
    }

    td {
        padding: 0.75rem 1rem;
        color: #4b5563;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f9fafb; }

    .acciones { display: flex; gap: 0.4rem; }

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
</style>

<div class="page-header">
    <div class="page-title">
        <i class="fa-solid fa-people-roof"></i>
        Inquilinos
    </div>
    <?php if ($rol === 'Admin'): ?>
        <a href="<?= BASE_URL ?>?action=inquilino_crear" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo inquilino
        </a>
    <?php endif; ?>
</div>

<div class="table-wrap">
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af"></i>
            <input type="text" id="buscador"
                   placeholder="Buscar por nombre o cédula..."
                   onkeyup="filtrarTabla()">
        </div>
    </div>

    <?php if (empty($inquilinos)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-users-slash"></i>
            No hay inquilinos registrados
        </div>
    <?php else: ?>
        <table id="tablaInquilinos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquilinos as $i): ?>
                <tr>
                    <td><?= $i['id'] ?></td>
                    <td><?= htmlspecialchars($i['nombre']) ?></td>
                    <td><?= htmlspecialchars($i['cedula']) ?></td>
                    <td><?= htmlspecialchars($i['telefono'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($i['email']) ?></td>
                    <td>
                        <div class="acciones">
                            <a href="<?= BASE_URL ?>?action=inquilino_detalle&id=<?= $i['id'] ?>"
                               class="btn btn-sm btn-info" title="Ver detalle">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <?php if ($rol === 'Admin'): ?>
                                <a href="<?= BASE_URL ?>?action=inquilino_editar&id=<?= $i['id'] ?>"
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="<?= BASE_URL ?>?action=inquilino_eliminar&id=<?= $i['id'] ?>"
                                   class="btn btn-sm btn-danger" title="Eliminar"
                                   onclick="return confirm('¿Eliminar este inquilino?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
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
    const filas  = document.querySelectorAll('#tablaInquilinos tbody tr');
    filas.forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(buscar) ? '' : 'none';
    });
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>