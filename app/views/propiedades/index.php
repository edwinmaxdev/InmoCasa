<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$rol = $_SESSION['rol'] ?? 'Inquilino';
$tipos = $tipos ?? [];
$propiedades = $propiedades ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Propiedades - InmoCasa</title>
<link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>

<h1>Propiedades</h1>

<?php if(isset($_GET['mensaje'])): ?>
    <p style="color:green;"><?= htmlspecialchars($_GET['mensaje']) ?></p>
<?php endif; ?>
<?php if(isset($_GET['error'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

<form method="get" action="../../../public/index.php">
    <input type="hidden" name="action" value="propiedades">

    <label>Estado:</label>
    <select name="estado">
        <option value="">Todos</option>
        <option value="Disponible" <?= (($_GET['estado'] ?? '') === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
        <option value="Arrendada" <?= (($_GET['estado'] ?? '') === 'Arrendada') ? 'selected' : '' ?>>Arrendada</option>
        <option value="En venta" <?= (($_GET['estado'] ?? '') === 'En venta') ? 'selected' : '' ?>>En venta</option>
        <option value="Vendida" <?= (($_GET['estado'] ?? '') === 'Vendida') ? 'selected' : '' ?>>Vendida</option>
    </select>

    <label>Tipo:</label>
    <select name="tipo_id">
        <option value="">Todos</option>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id'] ?>" <?= (($_GET['tipo_id'] ?? '') == $t['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filtrar</button>
</form>

<?php if ($rol === 'Admin'): ?>
    <p><a href="../../../public/index.php?action=propiedad_crear">+ Nueva propiedad</a></p>
<?php endif; ?>

<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Dirección</th>
        <th>Tipo</th>
        <th>Propietario</th>
        <th>Precio</th>
        <th>m2</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php if (empty($propiedades)): ?>
        <tr><td colspan="8">No hay propiedades para mostrar.</td></tr>
    <?php endif; ?>
    <?php foreach ($propiedades as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['direccion']) ?></td>
            <td><?= htmlspecialchars($p['tipo_nombre'] ?? '') ?></td>
            <td><?= htmlspecialchars($p['propietario_nombre'] ?? '') ?></td>
            <td>$<?= number_format($p['precio'], 2) ?></td>
            <td><?= $p['metros2'] ?></td>
            <td><?= htmlspecialchars($p['estado']) ?></td>
            <td>
                <a href="../../../public/index.php?action=propiedad_detalle&id=<?= $p['id'] ?>">Ver</a>
                <?php if ($rol === 'Admin'): ?>
                    | <a href="../../../public/index.php?action=propiedad_editar&id=<?= $p['id'] ?>">Editar</a>
                    | <a href="../../../public/index.php?action=propiedad_eliminar&id=<?= $p['id'] ?>"
                         onclick="return confirm('¿Eliminar esta propiedad?');">Eliminar</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>