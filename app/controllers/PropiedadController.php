<?php
session_start();
include_once __DIR__ . '/../models/Propiedad.php';
include_once __DIR__ . '/../models/TipoInmueble.php';
include_once __DIR__ . '/../models/Propietario.php';

class PropiedadController{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Propiedad;
    }

    private function verificarSession(){
        if(!isset($_SESSION['usuario_id'])){
            header("Location: ../../public/index.php?error=session_expirada");
            exit();
        }
    }

    private function soloAdmin(){
        if(!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin'){
            header("Location: ../../public/index.php?error=acceso_denegado");
            exit();
        }
    }

    public function index(){
        $this->verificarSession();

        $rol = $_SESSION['rol'];

        if(isset($_GET['estado']) && $_GET['estado'] !== ''){
            $propiedades = $this->modelo->filtrarPorEstado($_GET['estado']);
        }elseif(isset($_GET['tipo_id']) && $_GET['tipo_id'] !== ''){
            $propiedades = $this->modelo->filtrarPorTipo($_GET['tipo_id']);
        }elseif($rol === 'Propietario'){
            $propiedades = $this->modelo->obtenerPorPropietario($_SESSION['propietario_id']);
        }else{
            $propiedades = $this->modelo->obtenerTodos();
        }

        $tipoModelo = new TipoInmueble;
        $tipos = $tipoModelo->obtenerTodos();

        include_once __DIR__ . '/../views/propiedades/index.php';
    }

    public function detalle($id){
        $this->verificarSession();

        $propiedad = $this->modelo->obtenerPorId($id);

        if(!$propiedad){
            header("Location: ../../public/index.php?action=propiedades&error=no_encontrada");
            exit();
        }

        if($_SESSION['rol'] === 'Propietario' && $propiedad['propietario_id'] != $_SESSION['propietario_id']){
            header("Location: ../../public/index.php?error=acceso_denegado");
            exit();
        }

        include_once __DIR__ . '/../views/propiedades/detalle.php';
    }

    public function crear(){
        $this->verificarSession();
        $this->soloAdmin();

        $tipoModelo = new TipoInmueble;
        $tipos = $tipoModelo->obtenerTodos();

        $propietarioModelo = new Propietario;
        $propietarios = $propietarioModelo->obtenerTodos();

        include_once __DIR__ . '/../views/propiedades/crear.php';
    }

    public function guardar(){
        $this->verificarSession();
        $this->soloAdmin();

        $errores = [];

        if(empty($_POST["direccion"])){
            $errores[] = "La dirección es obligatoria";
        }
        if(empty($_POST["precio"]) || !is_numeric($_POST["precio"]) || $_POST["precio"] <= 0){
            $errores[] = "El precio debe ser mayor a 0";
        }
        if(empty($_POST["metros2"]) || !is_numeric($_POST["metros2"]) || $_POST["metros2"] <= 0){
            $errores[] = "Los metros2 deben ser mayor a 0";
        }
        if(empty($_POST["tipo_id"])){
            $errores[] = "El tipo es obligatorio";
        }
        if(empty($_POST["propietario_id"])){
            $errores[] = "El propietario es obligatorio";
        }

        if(!empty($errores)){
            $_SESSION["errores"] = $errores;
            header("Location: ../../public/index.php?action=propiedad_crear");
            exit();
        }

        $datos = [
            "direccion" => trim($_POST["direccion"]),
            "precio" => $_POST["precio"],
            "metros2" => $_POST["metros2"],
            "descripcion" => trim($_POST["descripcion"] ?? ''),
            "estado" => $_POST["estado"] ?? 'Disponible',
            "tipo_id" => $_POST["tipo_id"],
            "propietario_id" => $_POST["propietario_id"]
        ];

        $resultado = $this->modelo->crear($datos);

        if($resultado["exito"]){
            header("Location: ../../public/index.php?action=propiedades&mensaje=creado");
        }else{
            $_SESSION["errores"] = [$resultado["mensaje"]];
            header("Location: ../../public/index.php?action=propiedad_crear");
        }
        exit();
    }

    public function editar($id){
        $this->verificarSession();
        $this->soloAdmin();

        $propiedad = $this->modelo->obtenerPorId($id);

        if(!$propiedad){
            header("Location: ../../public/index.php?action=propiedades&error=no_encontrada");
            exit();
        }

        $tipoModelo = new TipoInmueble;
        $tipos = $tipoModelo->obtenerTodos();

        $propietarioModelo = new Propietario;
        $propietarios = $propietarioModelo->obtenerTodos();

        include_once __DIR__ . '/../views/propiedades/editar.php';
    }

    public function actualizar($id){
        $this->verificarSession();
        $this->soloAdmin();

        $errores = [];

        if(empty($_POST["direccion"])){
            $errores[] = "La dirección es obligatoria";
        }
        if(empty($_POST["precio"]) || !is_numeric($_POST["precio"]) || $_POST["precio"] <= 0){
            $errores[] = "El precio debe ser mayor a 0";
        }
        if(empty($_POST["metros2"]) || !is_numeric($_POST["metros2"]) || $_POST["metros2"] <= 0){
            $errores[] = "Los metros2 deben ser mayor a 0";
        }
        if(empty($_POST["tipo_id"])){
            $errores[] = "El tipo es obligatorio";
        }
        if(empty($_POST["propietario_id"])){
            $errores[] = "El propietario es obligatorio";
        }

        if(!empty($errores)){
            $_SESSION["errores"] = $errores;
            header("Location: ../../public/index.php?action=propiedad_editar&id=$id");
            exit();
        }

        $datos = [
            "direccion" => trim($_POST["direccion"]),
            "precio" => $_POST["precio"],
            "metros2" => $_POST["metros2"],
            "descripcion" => trim($_POST["descripcion"] ?? ''),
            "estado" => $_POST["estado"],
            "tipo_id" => $_POST["tipo_id"],
            "propietario_id" => $_POST["propietario_id"]
        ];

        $resultado = $this->modelo->actualizar($id, $datos);

        if($resultado["exito"]){
            header("Location: ../../public/index.php?action=propiedades&mensaje=actualizado");
        }else{
            $_SESSION["errores"] = [$resultado["mensaje"]];
            header("Location: ../../public/index.php?action=propiedad_editar&id=$id");
        }
        exit();
    }

    public function eliminar($id){
        $this->verificarSession();
        $this->soloAdmin();

        $resultado = $this->modelo->eliminar($id);

        if($resultado["exito"]){
            header("Location: ../../public/index.php?action=propiedades&mensaje=eliminado");
        }else{
            header("Location: ../../public/index.php?action=propiedades&mensaje=no_eliminado");
        }
        exit();
    }
}
?>