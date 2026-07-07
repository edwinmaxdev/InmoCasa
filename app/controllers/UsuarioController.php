<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    private function soloAdmin() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
            header('Location: ../../public/index.php?error=acceso_denegado');
            exit();
        }
    }

    private function verificarSesion() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../../public/index.php?error=sesion_expirada');
            exit();
        }
    }

    public function index() {
        $this->verificarSesion();
        $this->soloAdmin();
        $usuarios = $this->modelo->obtenerTodos();
        include_once __DIR__ . '/../views/usuarios/index.php';
    }

    public function crear() {
        $this->verificarSesion();
        $this->soloAdmin();
        include_once __DIR__ . '/../views/usuarios/crear.php';
    }

    public function guardar() {
        $this->verificarSesion();
        $this->soloAdmin();

        // Validaciones backend
        $errores = [];

        if (empty($_POST['nombre']))   $errores[] = "El nombre es obligatorio";
        if (empty($_POST['email']))    $errores[] = "El email es obligatorio";
        if (empty($_POST['password'])) $errores[] = "La contraseña es obligatoria";
        if (empty($_POST['rol']))      $errores[] = "El rol es obligatorio";

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (strlen($_POST['password']) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header('Location: ../../public/index.php?action=usuario_crear');
            exit();
        }

        $datos = [
            'nombre'         => trim($_POST['nombre']),
            'email'          => trim($_POST['email']),
            'password'       => $_POST['password'],
            'rol'            => $_POST['rol'],
            'propietario_id' => $_POST['propietario_id'] ?? null,
            'inquilino_id'   => $_POST['inquilino_id']   ?? null
        ];

        $resultado = $this->modelo->crear($datos);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=usuarios&mensaje=creado');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header('Location: ../../public/index.php?action=usuario_crear');
        }
        exit();
    }

    public function editar($id) {
        $this->verificarSesion();
        $this->soloAdmin();
        $usuario = $this->modelo->obtenerPorId($id);
        if (!$usuario) {
            header('Location: ../../public/index.php?action=usuarios&error=no_encontrado');
            exit();
        }
        include_once __DIR__ . '/../views/usuarios/editar.php';
    }

    public function actualizar($id) {
        $this->verificarSesion();
        $this->soloAdmin();

        // Validaciones backend
        $errores = [];

        if (empty($_POST['nombre']))  $errores[] = "El nombre es obligatorio";
        if (empty($_POST['email']))   $errores[] = "El email es obligatorio";
        if (empty($_POST['rol']))     $errores[] = "El rol es obligatorio";

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (!empty($_POST['password']) && strlen($_POST['password']) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            header("Location: ../../public/index.php?action=usuario_editar&id=$id");
            exit();
        }

        $datos = [
            'nombre'         => trim($_POST['nombre']),
            'email'          => trim($_POST['email']),
            'password'       => $_POST['password'] ?? '',
            'rol'            => $_POST['rol'],
            'propietario_id' => $_POST['propietario_id'] ?? null,
            'inquilino_id'   => $_POST['inquilino_id']   ?? null
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=usuarios&mensaje=actualizado');
        } else {
            $_SESSION['errores'] = [$resultado['mensaje']];
            header("Location: ../../public/index.php?action=usuario_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id) {
        $this->verificarSesion();
        $this->soloAdmin();

        // No puede eliminarse a sí mismo
        if ($id == $_SESSION['usuario_id']) {
            header('Location: ../../public/index.php?action=usuarios&error=no_autoeliminar');
            exit();
        }

        $resultado = $this->modelo->eliminar($id);

        if ($resultado['exito']) {
            header('Location: ../../public/index.php?action=usuarios&mensaje=eliminado');
        } else {
            header('Location: ../../public/index.php?action=usuarios&error=no_eliminado');
        }
        exit();
    }
}
?>