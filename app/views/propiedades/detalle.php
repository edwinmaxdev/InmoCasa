<?php
include_once __DIR__ . '/../layouts/header.php';

$propiedad = $propiedad ?? [];
?>

<h1>Detalle de propiedad</h1>

<p><strong>Dirección:</strong> <?= htmlspecialchars($propiedad['direccion'] ?? '') ?></p>
<p><strong>Tipo:</strong> <?= htmlspecialchars($propiedad['tipo_nombre'] ?? '') ?></p>
<p><strong>Propietario:</strong> <?= htmlspecialchars($propiedad['propietario_nombre'] ?? '') ?></p>
<p><strong>Precio:</strong> $<?= number_format($propiedad['precio'] ?? 0, 2) ?></p>
<p><strong>Metros2:</strong> <?= htmlspecialchars($propiedad['metros2'] ?? '') ?></p>
<p><strong>Estado:</strong> <?= htmlspecialchars($propiedad['estado'] ?? '') ?></p>
<p><strong>Descripción:</strong><br><?= nl2br(htmlspecialchars($propiedad['descripcion'] ?? '')) ?></p>

<p>
    <a href="../../public/index.php?action=propiedades">Volver</a>
    <?php if ($rol === 'Admin'): ?>
        | <a href="../../public/index.php?action=propiedad_editar&id=<?= $propiedad['id'] ?? '' ?>">Editar</a>
    <?php endif; ?>
</p>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>