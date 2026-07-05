<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$tipos = $tipos ?? [];
$propietarios = $propietarios ?? [];
$propiedad = $propiedad ?? [];
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar propiedad - InmoCasa</title>
<link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>

<h1>Editar propiedad</h1>

<?php if (!empty($errores)): ?>
    <ul style="color:red;">
        <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
<?php endif; ?>

<form id="formPropiedad" method="post" action="../../../public/index.php?action=propiedad_actualizar&id=<?= $propiedad['id'] ?>" onsubmit="return validarPropiedad();">

    <label>Dirección</label><br>
    <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($propiedad['direccion'] ?? '') ?>"><br>

    <label>Tipo</label><br>
    <select name="tipo_id" id="tipo_id">
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($t['id'] == ($propiedad['tipo_id'] ?? null)) ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Propietario</label><br>
    <select name="propietario_id" id="propietario_id">
        <?php foreach ($propietarios as $pr): ?>
            <option value="<?= $pr['id'] ?>" <?= ($pr['id'] == ($propiedad['propietario_id'] ?? null)) ? 'selected' : '' ?>>
                <?= htmlspecialchars($pr['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" name="precio" id="precio" value="<?= htmlspecialchars($propiedad['precio'] ?? '') ?>"><br>

    <label>Metros2</label><br>
    <input type="number" step="0.01" name="metros2" id="metros2" value="<?= htmlspecialchars($propiedad['metros2'] ?? '') ?>"><br>

    <label>Estado</label><br>
    <select name="estado" id="estado">
        <?php foreach (['Disponible','Arrendada','En venta','Vendida'] as $est): ?>
            <option value="<?= $est ?>" <?= ($est === ($propiedad['estado'] ?? '')) ? 'selected' : '' ?>><?= $est ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Descripción</label><br>
    <textarea name="descripcion"><?= htmlspecialchars($propiedad['descripcion'] ?? '') ?></textarea><br>

    <button type="submit">Actualizar</button>
    <a href="../../../public/index.php?action=propiedades">Cancelar</a>
</form>

<script>
function validarPropiedad() {
    const direccion = document.getElementById('direccion').value.trim();
    const tipoId = document.getElementById('tipo_id').value;
    const propietarioId = document.getElementById('propietario_id').value;
    const precio = parseFloat(document.getElementById('precio').value);
    const metros2 = parseFloat(document.getElementById('metros2').value);

    if (direccion === '') {
        alert('La dirección no puede estar vacía.');
        return false;
    }
    if (tipoId === '') {
        alert('Debe seleccionar un tipo.');
        return false;
    }
    if (propietarioId === '') {
        alert('Debe seleccionar un propietario.');
        return false;
    }
    if (isNaN(precio) || precio <= 0) {
        alert('El precio debe ser mayor a 0.');
        return false;
    }
    if (isNaN(metros2) || metros2 <= 0) {
        alert('Los metros cuadrados deben ser mayores a 0.');
        return false;
    }
    return true;
}
</script>

</body>
</html>