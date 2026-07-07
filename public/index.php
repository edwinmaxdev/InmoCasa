<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$action = $_GET['action'] ?? 'login';

// Rutas públicas (sin sesión)
$rutasPublicas = ['login', 'procesarLogin'];

// Si no hay sesión y la ruta no es pública, redirigir al login
if (!isset($_SESSION['usuario_id']) && !in_array($action, $rutasPublicas)) {
    header('Location: index.php?action=login');
    exit();
}

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ContratoController.php';
require_once __DIR__ . '/../app/controllers/PagoController.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';
require_once __DIR__ . '/../app/controllers/PropiedadController.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($action) {

    // AUTH
    case 'login':
        (new AuthController())->login();
        break;
    case 'procesarLogin':
        (new AuthController())->procesarLogin();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    // DASHBOARD
    case 'dashboard':
        include_once __DIR__ . '/../app/views/layouts/dashboard.php';
        break;

    // PROPIEDADES
    case 'propiedades':
        (new PropiedadController())->index();
        break;
    case 'propiedad_detalle':
        (new PropiedadController())->detalle($id);
        break;
    case 'propiedad_crear':
        (new PropiedadController())->crear();
        break;
    case 'propiedad_guardar':
        (new PropiedadController())->guardar();
        break;
    case 'propiedad_editar':
        (new PropiedadController())->editar($id);
        break;
    case 'propiedad_actualizar':
        (new PropiedadController())->actualizar($id);
        break;
    case 'propiedad_eliminar':
        (new PropiedadController())->eliminar($id);
        break;

    // TIPOS 
    case 'tipos':
    case 'tipo_crear':
    case 'tipo_guardar':
    case 'tipo_editar':
    case 'tipo_actualizar':
    case 'tipo_eliminar':
        require_once __DIR__ . '/../app/controllers/TipoInmuebleController.php';
        $ctrl = new TipoInmuebleController();
        match($action) {
            'tipos'           => $ctrl->index(),
            'tipo_crear'      => $ctrl->crear(),
            'tipo_guardar'    => $ctrl->guardar(),
            'tipo_editar'     => $ctrl->editar($id),
            'tipo_actualizar' => $ctrl->actualizar($id),
            'tipo_eliminar'   => $ctrl->eliminar($id),
        };
        break;

    // PROPIETARIOS 
    case 'propietarios':
    case 'propietario_detalle':
    case 'propietario_crear':
    case 'propietario_guardar':
    case 'propietario_editar':
    case 'propietario_actualizar':
    case 'propietario_eliminar':
        require_once __DIR__ . '/../app/controllers/PropietarioController.php';
        $ctrl = new PropietarioController();
        match($action) {
            'propietarios'            => $ctrl->index(),
            'propietario_detalle'     => $ctrl->detalle($id),
            'propietario_crear'       => $ctrl->crear(),
            'propietario_guardar'     => $ctrl->guardar(),
            'propietario_editar'      => $ctrl->editar($id),
            'propietario_actualizar'  => $ctrl->actualizar($id),
            'propietario_eliminar'    => $ctrl->eliminar($id),
        };
        break;

    // INQUILINOS 
    case 'inquilinos':
    case 'inquilino_detalle':
    case 'inquilino_crear':
    case 'inquilino_guardar':
    case 'inquilino_editar':
    case 'inquilino_actualizar':
    case 'inquilino_eliminar':
        require_once __DIR__ . '/../app/controllers/InquilinoController.php';
        $ctrl = new InquilinoController();
        match($action) {
            'inquilinos'            => $ctrl->index(),
            'inquilino_detalle'     => $ctrl->detalle($id),
            'inquilino_crear'       => $ctrl->crear(),
            'inquilino_guardar'     => $ctrl->guardar(),
            'inquilino_editar'      => $ctrl->editar($id),
            'inquilino_actualizar'  => $ctrl->actualizar($id),
            'inquilino_eliminar'    => $ctrl->eliminar($id),
        };
        break;

    // CONTRATOS
    case 'contratos':
        (new ContratoController())->index();
        break;
    case 'contrato_detalle':
        (new ContratoController())->detalle($id);
        break;
    case 'contrato_crear':
        (new ContratoController())->crear();
        break;
    case 'contrato_guardar':
        (new ContratoController())->guardar();
        break;
    case 'contrato_editar':
        (new ContratoController())->editar($id);
        break;
    case 'contrato_actualizar':
        (new ContratoController())->actualizar($id);
        break;
    case 'contrato_eliminar':
        (new ContratoController())->eliminar($id);
        break;
    case 'contratos_activos':
        (new ContratoController())->activos();
        break;

    // PAGOS
    case 'pagos':
        (new PagoController())->index();
        break;
    case 'pago_detalle':
        (new PagoController())->detalle($id);
        break;
    case 'pago_por_contrato':
        (new PagoController())->porContrato($id);
        break;
    case 'pago_historial':
        $inquilino_id = isset($_GET['inquilino_id']) ? (int)$_GET['inquilino_id'] : null;
        (new PagoController())->historial($inquilino_id);
        break;
    case 'pago_crear':
        (new PagoController())->crear();
        break;
    case 'pago_guardar':
        (new PagoController())->guardar();
        break;
    case 'pago_editar':
        (new PagoController())->editar($id);
        break;
    case 'pago_actualizar':
        (new PagoController())->actualizar($id);
        break;
    case 'pago_eliminar':
        (new PagoController())->eliminar($id);
        break;

    // USUARIOS
    case 'usuarios':
        (new UsuarioController())->index();
        break;
    case 'usuario_crear':
        (new UsuarioController())->crear();
        break;
    case 'usuario_guardar':
        (new UsuarioController())->guardar();
        break;
    case 'usuario_editar':
        (new UsuarioController())->editar($id);
        break;
    case 'usuario_actualizar':
        (new UsuarioController())->actualizar($id);
        break;
    case 'usuario_eliminar':
        (new UsuarioController())->eliminar($id);
        break;

    // RUTA NO ENCONTRADA
    default:
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=dashboard');
        } else {
            header('Location: index.php?action=login');
        }
        exit();
}