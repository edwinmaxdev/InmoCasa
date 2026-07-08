<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/TipoInmueble.php';

class TipoInmuebleController {
    private $modelo;

    public function __construct() {
        $this->modelo = new TipoInmueble();
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
        $tipos = $this->modelo->obtenerTodos();
        include_once __DIR__ . '/../views/tipos/index.php';
    }

    public function crear() {
        $this->verificarSession();
        $this->soloAdmin();
        include_once __DIR__ . '/../views/tipos/crear.php';
    }

    public function guardar() {
        $this->verificarSession();
        $this->soloAdmin();

        $errores = [];

        if (empty($_POST['nombre'])) $errores[] = "El nombre es obligatorio";

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: " . BASE_URL . "?action=tipo_crear");
            exit();
        }

        $datos = [
            'nombre'      => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        $resultado = $this->modelo->crear($datos);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=tipos&mensaje=creado");
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: " . BASE_URL . "?action=tipo_crear");
        }
        exit();
    }

    public function editar($id) {
        $this->verificarSession();
        $this->soloAdmin();

        $tipo = $this->modelo->obtenerPorId($id);

        if (!$tipo) {
            header("Location: " . BASE_URL . "?action=tipos&error=no_encontrado");
            exit();
        }

        include_once __DIR__ . '/../views/tipos/editar.php';
    }

    public function actualizar($id) {
        $this->verificarSession();
        $this->soloAdmin();

        $errores = [];

        if (empty($_POST['nombre'])) $errores[] = "El nombre es obligatorio";

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: " . BASE_URL . "?action=tipo_editar&id=$id");
            exit();
        }

        $datos = [
            'nombre'      => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=tipos&mensaje=actualizado");
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: " . BASE_URL . "?action=tipo_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id) {
        $this->verificarSession();
        $this->soloAdmin();

        $resultado = $this->modelo->eliminar($id);

        if ($resultado['exito']) {
            header("Location: " . BASE_URL . "?action=tipos&mensaje=eliminado");
        } else {
            header("Location: " . BASE_URL . "?action=tipos&error=no_eliminado");
        }
        exit();
    }
}
?>