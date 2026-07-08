<?php include_once __DIR__ . '/../layouts/header.php'; ?>
<?php $tipos = $tipos ?? []; $rol = $_SESSION['rol']; ?>

<style>
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .page-title { font-size: 1.3rem; font-weight: 600; color: #1a2e44; display: flex; align-items: center; gap: 0.5rem; }
    .page-title i { color: #4da6ff; }
    .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #1a2e44; color: #fff; } .btn-primary:hover { background: #243d57; }
    .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
    .btn-warning { background: #fffbeb; color: #d97706; } .btn-warning:hover { background: #fef3c7; }
    .btn-danger { background: #fef2f2; color: #dc2626; } .btn-danger:hover { background: #fee2e2; }
    .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead { background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
    th { padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.4px; }
    td { padding: 0.75rem 1rem; color: #4b5563; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tr:last-child td { border-bottom: none; } tr:hover td { background: #f9fafb; }
    .acciones { display: flex; gap: 0.4rem; }
    .empty-state { text-align: center; padding: 3rem; color: #9ca3af; }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; color: #d1d5db; }
</style>

<div class="page-header">
    <div class="page-title"><i class="fa-solid fa-tags"></i> Tipos de Inmueble</div>
    <?php if ($rol === 'Admin'): ?>
        <a href="<?= BASE_URL ?>?action=tipo_crear" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nuevo tipo</a>
    <?php endif; ?>
</div>

<div class="table-wrap">
    <?php if (empty($tipos)): ?>
        <div class="empty-state"><i class="fa-solid fa-tags"></i> No hay tipos registrados</div>
    <?php else: ?>
        <table>
            <thead><tr><th>#</th><th>Nombre</th><th>Descripción</th><?php if ($rol === 'Admin'): ?><th>Acciones</th><?php endif; ?></tr></thead>
            <tbody>
                <?php foreach ($tipos as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= htmlspecialchars($t['nombre']) ?></td>
                    <td><?= htmlspecialchars($t['descripcion'] ?? '—') ?></td>
                    <?php if ($rol === 'Admin'): ?>
                    <td>
                        <div class="acciones">
                            <a href="<?= BASE_URL ?>?action=tipo_editar&id=<?= $t['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fa-solid fa-pen"></i></a>
                            <a href="<?= BASE_URL ?>?action=tipo_eliminar&id=<?= $t['id'] ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar este tipo?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>