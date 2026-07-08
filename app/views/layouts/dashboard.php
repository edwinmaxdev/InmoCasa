<?php include_once __DIR__ . '/header.php'; ?>
<?php
include_once __DIR__ . '/../../models/Contrato.php';
include_once __DIR__ . '/../../models/Pago.php';
include_once __DIR__ . '/../../models/Propiedad.php';
include_once __DIR__ . '/../../models/Usuario.php';

$contratoModelo  = new Contrato();
$pagoModelo      = new Pago();
$propiedadModelo = new Propiedad();

$totalContratosActivos  = $contratoModelo->contarActivos();
$totalPagosPendientes   = $pagoModelo->contarPendientes();
$totalPropDisponibles   = $propiedadModelo->contarDisponibles();
$proximosAVencer        = $contratoModelo->obtenerProximosAVencer();

// Para Admin también mostramos usuarios
if ($_SESSION['rol'] === 'Admin') {
    include_once __DIR__ . '/../../models/Usuario.php';
    $usuarioModelo  = new Usuario();
    $totalUsuarios  = count($usuarioModelo->obtenerTodos());
}
?>

<style>
    .dashboard-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #1a2e44;
        margin-bottom: 0.25rem;
    }

    .dashboard-subtitle {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    /* Cards de contadores */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .card {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid transparent;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .card-blue {
        border-left-color: transparent;
    }

    .card-green {
        border-left-color: transparent;
    }

    .card-amber {
        border-left-color: transparent;
    }

    .card-purple {
        border-left-color: transparent;
    }

    .card-red {
        border-left-color: transparent;
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .icon-blue {
        background: #eff6ff;
        color: #3b82f6;
    }

    .icon-green {
        background: #f0fdf4;
        color: #22c55e;
    }

    .icon-amber {
        background: #fffbeb;
        color: #f59e0b;
    }

    .icon-purple {
        background: #f5f3ff;
        color: #8b5cf6;
    }

    .icon-red {
        background: #fef2f2;
        color: #ef4444;
    }

    .card-info {
        flex: 1;
    }

    .card-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a2e44;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .card-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }

    .card-link {
        font-size: 0.75rem;
        color: #4da6ff;
        text-decoration: none;
        margin-top: 0.3rem;
        display: inline-block;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    /* Sección de alertas próximos a vencer */
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a2e44;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #f59e0b;
    }

    .table-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .table-card thead {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-card th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .table-card td {
        padding: 0.75rem 1rem;
        color: #4b5563;
        border-bottom: 1px solid #f3f4f6;
    }

    .table-card tr:last-child td {
        border-bottom: none;
    }

    .table-card tr:hover td {
        background: #f9fafb;
    }

    /* Badges de días restantes */
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 99px;
    }

    .badge-red {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-amber {
        background: #fffbeb;
        color: #d97706;
    }

    .badge-green {
        background: #f0fdf4;
        color: #16a34a;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
        font-size: 0.9rem;
    }

    .empty-state i {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
        color: #d1d5db;
    }

    /* Grid de dos columnas */
    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .cards-grid {
            grid-template-columns: 1fr 1fr;
        }

        .two-col {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Título -->
<div class="dashboard-title">
    Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?>
</div>
<p class="dashboard-subtitle">
    <i class="fa-solid fa-calendar-day"></i>
    <?= date('l, d \d\e F \d\e Y') ?>
</p>

<!-- Cards de contadores -->
<div class="cards-grid">
    <div class="card card-blue">
        <div class="card-icon icon-blue">
            <i class="fa-solid fa-house"></i>
        </div>
        <div class="card-info">
            <div class="card-value"><?= $totalPropDisponibles ?></div>
            <div class="card-label">Propiedades disponibles</div>
            <?php if ($_SESSION['rol'] === 'Admin' || $_SESSION['rol'] === 'Propietario'): ?>
                <a href="../../public/index.php?action=propiedades" class="card-link">Ver todas →</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card card-green">
        <div class="card-icon icon-green">
            <i class="fa-solid fa-file-contract"></i>
        </div>
        <div class="card-info">
            <div class="card-value"><?= $totalContratosActivos ?></div>
            <div class="card-label">Contratos activos</div>
            <a href="../../public/index.php?action=contratos" class="card-link">Ver todos →</a>
        </div>
    </div>

    <div class="card card-amber">
        <div class="card-icon icon-amber">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="card-info">
            <div class="card-value"><?= count($proximosAVencer) ?></div>
            <div class="card-label">Contratos por vencer</div>
            <a href="#proximosVencer" class="card-link">Ver detalle →</a>
        </div>
    </div>

    <div class="card card-red">
        <div class="card-icon icon-red">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="card-info">
            <div class="card-value"><?= $totalPagosPendientes ?></div>
            <div class="card-label">Pagos pendientes</div>
            <a href="../../public/index.php?action=pagos" class="card-link">Ver todos →</a>
        </div>
    </div>

    <?php if ($_SESSION['rol'] === 'Admin'): ?>
        <div class="card card-purple">
            <div class="card-icon icon-purple">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="card-info">
                <div class="card-value"><?= $totalUsuarios ?></div>
                <div class="card-label">Usuarios registrados</div>
                <a href="../../public/index.php?action=usuarios" class="card-link">Gestionar →</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Contratos próximos a vencer -->
<div id="proximosVencer">
    <div class="section-title">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Contratos próximos a vencer (30 días)
    </div>

    <div class="table-card">
        <?php if (empty($proximosAVencer)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-circle-check"></i>
                No hay contratos próximos a vencer
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Propiedad</th>
                        <th>Inquilino</th>
                        <th>Fecha fin</th>
                        <th>Días restantes</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proximosAVencer as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['propiedad_direccion']) ?></td>
                            <td><?= htmlspecialchars($c['inquilino_nombre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_fin'])) ?></td>
                            <td>
                                <?php
                                $dias = $c['dias_restantes'];
                                $clase = $dias <= 7 ? 'badge-red' : ($dias <= 15 ? 'badge-amber' : 'badge-green');
                                ?>
                                <span class="badge <?= $clase ?>">
                                    <?= $dias ?> día<?= $dias != 1 ? 's' : '' ?>
                                </span>
                            </td>
                            <td>
                                <a href="../../public/index.php?action=contrato_detalle&id=<?= $c['id'] ?>"
                                    class="card-link">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>