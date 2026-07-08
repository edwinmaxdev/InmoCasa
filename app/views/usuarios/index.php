<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$usuarios = $usuarios ?? [];
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
    .btn-warning { background: #fffbeb; color: #d97706; }
    .btn-danger  { background: #fef2f2; color: #dc2626; }
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

    .badge-Admin       { background: #eff6ff; color: #3b82f6; }
    .badge-Propietario { background: #f0fdf4; color: #16a34a; }
    .badge-Inquilino   { background: #fffbeb; color: #d97706; }

    .usuario-info {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #1a2e44;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .acciones { display: flex; gap: 0.4rem; }

    .yo-badge {
        font-size: 0.7rem;
        background: #f0fdf4;
        color: #16a34a;
        padding: 0.15rem 0.5rem;
        border-radius: 99px;
        font-weight: 600;
        margin-left: 0.4rem;
    }

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
        <i class="fa-solid fa-users-gear"></i>
        Usuarios
    </div>
    <a href="../../public/index.php?action=usuario_crear" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo usuario
    </a>
</div>

<!-- Tabla -->
<div class="table-wrap">
    <div class="table-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af"></i>
            <input type="text" id="buscador"
                   placeholder="Buscar por nombre o email..."
                   onkeyup="filtrarTabla()">
        </div>
        <select class="filter-select" id="filtroRol" onchange="filtrarTabla()">
            <option value="">Todos los roles</option>
            <option value="Admin">Admin</option>
            <option value="Propietario">Propietario</option>
            <option value="Inquilino">Inquilino</option>
        </select>
    </div>

    <?php if (empty($usuarios)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-users-slash"></i>
            No hay usuarios registrados
        </div>
    <?php else: ?>
        <table id="tablaUsuarios">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td>
                        <div class="usuario-info">
                            <div class="avatar">
                                <?= mb_substr($u['nombre'], 0, 1) ?>
                            </div>
                            <?= htmlspecialchars($u['nombre']) ?>
                            <?php if ($u['id'] == $_SESSION['usuario_id']): ?>
                                <span class="yo-badge">Tú</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="badge badge-<?= $u['rol'] ?>">
                            <?= $u['rol'] ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <div class="acciones">
                            <a href="../../public/index.php?action=usuario_editar&id=<?= $u['id'] ?>"
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                <a href="../../public/index.php?action=usuario_eliminar&id=<?= $u['id'] ?>"
                                   class="btn btn-sm btn-danger" title="Eliminar"
                                   onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
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
    const rol    = document.getElementById('filtroRol').value.toLowerCase();
    const filas  = document.querySelectorAll('#tablaUsuarios tbody tr');

    filas.forEach(fila => {
        const texto   = fila.textContent.toLowerCase();
        const rolFila = fila.querySelector('.badge')?.textContent.trim().toLowerCase() ?? '';
        const coincideBuscar = texto.includes(buscar);
        const coincideRol    = rol === '' || rolFila === rol;
        fila.style.display = coincideBuscar && coincideRol ? '' : 'none';
    });
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>