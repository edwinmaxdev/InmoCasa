<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$tipos = $tipos ?? [];
$propietarios = $propietarios ?? [];
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva propiedad - InmoCasa</title>
<link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body>

<h1>Nueva propiedad</h1>

<?php if (!empty($errores)): ?>
    <ul style="color:red;">
        <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
<?php endif; ?>

<form id="formPropiedad" method="post" action="../../../public/index.php?action=propiedad_guardar" onsubmit="return validarPropiedad();">

    <label>Dirección</label><br>
    <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>"><br>

    <label>Tipo</label><br>
    <select name="tipo_id" id="tipo_id">
        <option value="">Seleccione</option>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Propietario</label><br>
    <select name="propietario_id" id="propietario_id">
        <option value="">Seleccione</option>
        <?php foreach ($propietarios as $pr): ?>
            <option value="<?= $pr['id'] ?>"><?= htmlspecialchars($pr['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" name="precio" id="precio" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>"><br>

    <label>Metros2</label><br>
    <input type="number" step="0.01" name="metros2" id="metros2" value="<?= htmlspecialchars($_POST['metros2'] ?? '') ?>"><br>

    <label>Estado</label><br>
    <select name="estado" id="estado">
        <option value="Disponible">Disponible</option>
        <option value="Arrendada">Arrendada</option>
        <option value="En venta">En venta</option>
        <option value="Vendida">Vendida</option>
    </select><br>

    <label>Descripción</label><br>
    <textarea name="descripcion"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea><br>

    <button type="submit">Guardar</button>
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