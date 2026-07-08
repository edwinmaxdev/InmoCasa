<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/Inquilino.php';

class InquilinoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Inquilino();
    }

    private function verificarSession() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "?error=session_expirada");
            exit();
        }
    }

    private function soloAdmin() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
            header("Location: " . BASE_URL . "?error=acceso_denegado");
            exit();
        }
    }

    public function index() {
        $this->verificarSession();
        $this->soloAdmin();
        $inquilinos = $this->modelo->obtenerTodos();
        include_once __DIR__ . '/../views/inquilinos/index.php';
    }

    public function detalle($id) {
        $this->verificarSession();
        $this->soloAdmin();
        $inquilino = $this->modelo->obtenerPorId($id);
        if (!$inquilino) {
            header("Location: " . BASE_URL . "?action=inquilinos&error=no_encontrado");
            exit();
        }
        include_once __DIR__ . '/../views/inquilinos/detalle.php';
    }

    public function crear() {
        $this->verificarSession();
        $this->soloAdmin();
        include_once __DIR__ . '/../views/inquilinos/crear.php';
    }

    public function guardar() {
        $this->verificarSession();
        $this->soloAdmin();

        $errores = [];
        if (empty($_POST['nombre']))  $errores[] = "El nombre es obligatorio";
        if (empty($_POST['cedula']))  $errores[] = "La cédula es obligatoria";
        if (empty($_POST['email']))   $errores[] = "El email es obligatorio";
        if (!empty($_POST['cedula']) && !preg_match('/^\d{10}$/', $_POST['cedula'])) {
            $errores[] = "La cédula debe tener 10 dígitos";
        }
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: " . BASE_URL . "?action=inquilino_crear");
            exit();
        }

        $datos = [
            'nombre'     => trim($_POST['nombre']),
            'cedula'     => trim($_POST['cedula']),
            'telefono'   => trim($_POST['telefono'] ?? ''),
            'email'      => trim($_POST['email']),
            'direccion'  => trim($_POST['direccion'] ?? ''),
            'referencia' => trim($_POST['referencia'] ?? '')
        ];

        $resultado = $this->modelo->crear($datos);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=inquilinos&mensaje=creado");
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: " . BASE_URL . "?action=inquilino_crear");
        }
        exit();
    }

    public function editar($id) {
        $this->verificarSession();
        $this->soloAdmin();
        $inquilino = $this->modelo->obtenerPorId($id);
        if (!$inquilino) {
            header("Location: " . BASE_URL . "?action=inquilinos&error=no_encontrado");
            exit();
        }
        include_once __DIR__ . '/../views/inquilinos/editar.php';
    }

    public function actualizar($id) {
        $this->verificarSession();
        $this->soloAdmin();

        $errores = [];
        if (empty($_POST['nombre']))  $errores[] = "El nombre es obligatorio";
        if (empty($_POST['cedula']))  $errores[] = "La cédula es obligatoria";
        if (empty($_POST['email']))   $errores[] = "El email es obligatorio";
        if (!empty($_POST['cedula']) && !preg_match('/^\d{10}$/', $_POST['cedula'])) {
            $errores[] = "La cédula debe tener 10 dígitos";
        }
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: " . BASE_URL . "?action=inquilino_editar&id=$id");
            exit();
        }

        $datos = [
            'nombre'     => trim($_POST['nombre']),
            'cedula'     => trim($_POST['cedula']),
            'telefono'   => trim($_POST['telefono'] ?? ''),
            'email'      => trim($_POST['email']),
            'direccion'  => trim($_POST['direccion'] ?? ''),
            'referencia' => trim($_POST['referencia'] ?? '')
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=inquilinos&mensaje=actualizado");
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: " . BASE_URL . "?action=inquilino_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id) {
        $this->verificarSession();
        $this->soloAdmin();
        $resultado = $this->modelo->eliminar($id);
        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=inquilinos&mensaje=eliminado");
        } else {
            header("Location: " . BASE_URL . "?action=inquilinos&error=no_eliminado");
        }
        exit();
    }
}
?>