<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/Propietario.php';

class PropietarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Propietario();
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
        $propietarios = $this->modelo->obtenerTodos();
        include_once __DIR__ . '/../views/propietarios/index.php';
    }

    public function detalle($id) {
        $this->verificarSession();
        $this->soloAdmin();
        $propietario = $this->modelo->obtenerPorId($id);
        if (!$propietario) {
            header("Location: " . BASE_URL . "?action=propietarios&error=no_encontrado");
            exit();
        }
        include_once __DIR__ . '/../views/propietarios/detalle.php';
    }

    public function crear() {
        $this->verificarSession();
        $this->soloAdmin();
        include_once __DIR__ . '/../views/propietarios/crear.php';
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
            header("Location: " . BASE_URL . "?action=propietario_crear");
            exit();
        }

        $datos = [
            'nombre'    => trim($_POST['nombre']),
            'cedula'    => trim($_POST['cedula']),
            'telefono'  => trim($_POST['telefono'] ?? ''),
            'email'     => trim($_POST['email']),
            'direccion' => trim($_POST['direccion'] ?? '')
        ];

        $resultado = $this->modelo->crear($datos);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=propietarios&mensaje=creado");
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: " . BASE_URL . "?action=propietario_crear");
        }
        exit();
    }

    public function editar($id) {
        $this->verificarSession();
        $this->soloAdmin();
        $propietario = $this->modelo->obtenerPorId($id);
        if (!$propietario) {
            header("Location: " . BASE_URL . "?action=propietarios&error=no_encontrado");
            exit();
        }
        include_once __DIR__ . '/../views/propietarios/editar.php';
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
            header("Location: " . BASE_URL . "?action=propietario_editar&id=$id");
            exit();
        }

        $datos = [
            'nombre'    => trim($_POST['nombre']),
            'cedula'    => trim($_POST['cedula']),
            'telefono'  => trim($_POST['telefono'] ?? ''),
            'email'     => trim($_POST['email']),
            'direccion' => trim($_POST['direccion'] ?? '')
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=propietarios&mensaje=actualizado");
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: " . BASE_URL . "?action=propietario_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id) {
        $this->verificarSession();
        $this->soloAdmin();
        $resultado = $this->modelo->eliminar($id);
        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=propietarios&mensaje=eliminado");
        } else {
            header("Location: " . BASE_URL . "?action=propietarios&error=no_eliminado");
        }
        exit();
    }
}
?>