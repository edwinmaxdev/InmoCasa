<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/Contrato.php';
include_once __DIR__ . '/AuthController.php';

class ContratoController {

    private $modelo;

    public function __construct() {
        $this->modelo = new Contrato();
    }

    public function index() {
        AuthController::verificarSesion();
        
        // Admin ve todos, Inquilino solo los suyos
        if ($_SESSION['rol'] === 'Admin') {
            $contratos = $this->modelo->obtenerTodos();
        } elseif ($_SESSION['rol'] === 'Inquilino') {
            $contratos = $this->modelo->obtenerPorInquilino($_SESSION['inquilino_id']);
        } else {
            header('Location: ../../public/index.php?error=acceso_denegado');
            exit();
        }

        $proximosAVencer = $this->modelo->obtenerProximosAVencer();
        include_once __DIR__ . '/../views/contratos/index.php';
    }

    public function detalle($id) {
        AuthController::verificarSesion();
        $contrato = $this->modelo->obtenerPorId($id);

        if (!$contrato) {
            header('Location: ../../public/index.php?action=contratos&error=no_encontrado');
            exit();
        }

        // Inquilino solo puede ver sus propios contratos
        if ($_SESSION['rol'] === 'Inquilino' && $contrato['inquilino_id'] != $_SESSION['inquilino_id']) {
            header('Location: ../../public/index.php?error=acceso_denegado');
            exit();
        }

        include_once __DIR__ . '/../views/contratos/detalle.php';
    }

    public function crear() {
        AuthController::verificarRol(['Admin']);
        include_once __DIR__ . '/../views/contratos/crear.php';
    }

    public function guardar() {
        AuthController::verificarRol(['Admin']);

        $errores = [];

        if (empty($_POST['propiedad_id']))    $errores[] = "La propiedad es obligatoria";
        if (empty($_POST['inquilino_id']))    $errores[] = "El inquilino es obligatorio";
        if (empty($_POST['fecha_inicio']))    $errores[] = "La fecha de inicio es obligatoria";
        if (empty($_POST['fecha_fin']))       $errores[] = "La fecha de fin es obligatoria";
        if (empty($_POST['monto_mensual']))   $errores[] = "El monto mensual es obligatorio";

        if (!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
            if ($_POST['fecha_fin'] <= $_POST['fecha_inicio']) {
                $errores[] = "La fecha de fin debe ser mayor a la fecha de inicio";
            }
        }

        if (!empty($_POST['monto_mensual']) && $_POST['monto_mensual'] <= 0) {
            $errores[] = "El monto mensual debe ser mayor a 0";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: ../../public/index.php?action=contrato_crear');
            exit();
        }

        $datos = [
            'propiedad_id'   => $_POST['propiedad_id'],
            'inquilino_id'   => $_POST['inquilino_id'],
            'fecha_inicio'   => $_POST['fecha_inicio'],
            'fecha_fin'      => $_POST['fecha_fin'],
            'monto_mensual'  => $_POST['monto_mensual'],
            'estado'         => $_POST['estado'] ?? 'Activo',
            'observaciones'  => $_POST['observaciones'] ?? null
        ];

        $resultado = $this->modelo->crear($datos);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=contratos&mensaje=creado');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header('Location: ../../public/index.php?action=contrato_crear');
        }
        exit();
    }

    public function editar($id) {
        AuthController::verificarRol(['Admin']);
        $contrato = $this->modelo->obtenerPorId($id);

        if (!$contrato) {
            header('Location: ../../public/index.php?action=contratos&error=no_encontrado');
            exit();
        }

        include_once __DIR__ . '/../views/contratos/editar.php';
    }

    public function actualizar($id) {
        AuthController::verificarRol(['Admin']);

        $errores = [];

        if (empty($_POST['propiedad_id']))   $errores[] = "La propiedad es obligatoria";
        if (empty($_POST['inquilino_id']))   $errores[] = "El inquilino es obligatorio";
        if (empty($_POST['fecha_inicio']))   $errores[] = "La fecha de inicio es obligatoria";
        if (empty($_POST['fecha_fin']))      $errores[] = "La fecha de fin es obligatoria";
        if (empty($_POST['monto_mensual']))  $errores[] = "El monto mensual es obligatorio";

        if (!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
            if ($_POST['fecha_fin'] <= $_POST['fecha_inicio']) {
                $errores[] = "La fecha de fin debe ser mayor a la fecha de inicio";
            }
        }

        if (!empty($_POST['monto_mensual']) && $_POST['monto_mensual'] <= 0) {
            $errores[] = "El monto mensual debe ser mayor a 0";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ../../public/index.php?action=contrato_editar&id=$id");
            exit();
        }

        $datos = [
            'propiedad_id'  => $_POST['propiedad_id'],
            'inquilino_id'  => $_POST['inquilino_id'],
            'fecha_inicio'  => $_POST['fecha_inicio'],
            'fecha_fin'     => $_POST['fecha_fin'],
            'monto_mensual' => $_POST['monto_mensual'],
            'estado'        => $_POST['estado'],
            'observaciones' => $_POST['observaciones'] ?? null
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=contratos&mensaje=actualizado');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: ../../public/index.php?action=contrato_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id) {
        AuthController::verificarRol(['Admin']);
        $resultado = $this->modelo->eliminar($id);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=contratos&mensaje=eliminado');
        } else {
            header('Location: ../../public/index.php?action=contratos&error=no_eliminado');
        }
        exit();
    }

    public function activos() {
        AuthController::verificarRol(['Admin']);
        $contratos = $this->modelo->obtenerActivos();
        include_once __DIR__ . '/../views/contratos/index.php';
    }
}
?>