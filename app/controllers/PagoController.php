<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/Pago.php';
include_once __DIR__ . '/AuthController.php';

class PagoController {

    private $modelo;

    public function __construct() {
        $this->modelo = new Pago();
    }

    public function index() {
        AuthController::verificarSesion();

        // Admin ve todos, Inquilino solo los suyos
        if ($_SESSION['rol'] === 'Admin') {
            $pagos = $this->modelo->obtenerTodos();
        } elseif ($_SESSION['rol'] === 'Inquilino') {
            $pagos = $this->modelo->obtenerHistorial($_SESSION['inquilino_id']);
        } else {
            header('Location: ../../public/index.php?error=acceso_denegado');
            exit();
        }

        include_once __DIR__ . '/../views/pagos/index.php';
    }

    public function detalle($id) {
        AuthController::verificarSesion();
        $pago = $this->modelo->obtenerPorId($id);

        if (!$pago) {
            header('Location: ../../public/index.php?action=pagos&error=no_encontrado');
            exit();
        }

        include_once __DIR__ . '/../views/pagos/detalle.php';
    }

    public function porContrato($contrato_id) {
        AuthController::verificarSesion();
        $pagos = $this->modelo->obtenerPorContrato($contrato_id);
        include_once __DIR__ . '/../views/pagos/index.php';
    }

    public function historial($inquilino_id) {
        AuthController::verificarSesion();

        // Inquilino solo puede ver su propio historial
        if ($_SESSION['rol'] === 'Inquilino' && $_SESSION['inquilino_id'] != $inquilino_id) {
            header('Location: ../../public/index.php?error=acceso_denegado');
            exit();
        }

        $pagos = $this->modelo->obtenerHistorial($inquilino_id);
        include_once __DIR__ . '/../views/pagos/historial.php';
    }

    public function crear() {
        AuthController::verificarRol(['Admin']);
        include_once __DIR__ . '/../views/pagos/crear.php';
    }

    public function guardar() {
        AuthController::verificarRol(['Admin']);

        $errores = [];

        if (empty($_POST['contrato_id']))         $errores[] = "El contrato es obligatorio";
        if (empty($_POST['monto']))               $errores[] = "El monto es obligatorio";
        if (empty($_POST['mes_correspondiente'])) $errores[] = "El mes correspondiente es obligatorio";
        if (empty($_POST['estado']))              $errores[] = "El estado es obligatorio";

        if (!empty($_POST['monto']) && $_POST['monto'] <= 0) {
            $errores[] = "El monto debe ser mayor a 0";
        }

        // Si el estado es Pagado la fecha es obligatoria
        if (!empty($_POST['estado']) && $_POST['estado'] === 'Pagado' && empty($_POST['fecha_pago'])) {
            $errores[] = "La fecha de pago es obligatoria cuando el estado es Pagado";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: ../../public/index.php?action=pago_crear');
            exit();
        }

        $datos = [
            'contrato_id'        => $_POST['contrato_id'],
            'fecha_pago'         => !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : null,
            'monto'              => $_POST['monto'],
            'mes_correspondiente'=> $_POST['mes_correspondiente'],
            'estado'             => $_POST['estado'],
            'observaciones'      => $_POST['observaciones'] ?? null
        ];

        $resultado = $this->modelo->crear($datos);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=pagos&mensaje=creado');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header('Location: ../../public/index.php?action=pago_crear');
        }
        exit();
    }

    public function editar($id) {
        AuthController::verificarRol(['Admin']);
        $pago = $this->modelo->obtenerPorId($id);

        if (!$pago) {
            header('Location: ../../public/index.php?action=pagos&error=no_encontrado');
            exit();
        }

        include_once __DIR__ . '/../views/pagos/editar.php';
    }

    public function actualizar($id) {
        AuthController::verificarRol(['Admin']);

        $errores = [];

        if (empty($_POST['contrato_id']))          $errores[] = "El contrato es obligatorio";
        if (empty($_POST['monto']))                $errores[] = "El monto es obligatorio";
        if (empty($_POST['mes_correspondiente']))  $errores[] = "El mes correspondiente es obligatorio";
        if (empty($_POST['estado']))               $errores[] = "El estado es obligatorio";

        if (!empty($_POST['monto']) && $_POST['monto'] <= 0) {
            $errores[] = "El monto debe ser mayor a 0";
        }

        if (!empty($_POST['estado']) && $_POST['estado'] === 'Pagado' && empty($_POST['fecha_pago'])) {
            $errores[] = "La fecha de pago es obligatoria cuando el estado es Pagado";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ../../public/index.php?action=pago_editar&id=$id");
            exit();
        }

        $datos = [
            'contrato_id'         => $_POST['contrato_id'],
            'fecha_pago'          => !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : null,
            'monto'               => $_POST['monto'],
            'mes_correspondiente' => $_POST['mes_correspondiente'],
            'estado'              => $_POST['estado'],
            'observaciones'       => $_POST['observaciones'] ?? null
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=pagos&mensaje=actualizado');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: ../../public/index.php?action=pago_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id) {
        AuthController::verificarRol(['Admin']);
        $resultado = $this->modelo->eliminar($id);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=pagos&mensaje=eliminado');
        } else {
            header('Location: ../../public/index.php?action=pagos&error=no_eliminado');
        }
        exit();
    }
}
?>