<?php
session_start();
include_once __DIR__ . '/../models/Usuario.php';

class AuthController{
    private $modelo;
    
    public function __construct(){
        $this->modelo = new Usuario();
    }
    public function login(){
        if(isset($_SESSION["usuario_id"])){
            header("Location: ../../public/index.php?action=dashboard");
            exit();
        }
        include_once __DIR__ . "/../views/auth/login.php";
    }
    public function procesarLogin(){
        $errores = [];

        if(empty($_POST["email"])){
            $errores[] = "Email es obligatorio";
        }
        if(empty($_POST["password"])){
            $errores[] = "Contraseña es obligatorio";
        }
        if(filter_var($_POST["password"], FILTER_VALIDATE_EMAIL)){
            $errores[] = "El formato del correo no valido";
        }
        if($_SESSION["errores"]){
            $_SESSION["errores"] = $errores;
            header("Location: ../../public/index.php?action=login");
        }
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $resultado = $this->modelo->login($email, $password);

        if($resultado["exito"]){
            switch($resultado["rol"]){
                case "Admin":
                    header("Location: ../../public/index.php?action=dashboard");
                    break;
                case "Propietario":
                    header("Location: ../../public/index.php?action=propiedades");
                    break;
                case "Inquilino":
                    header("Location: ../../public/index.php?action=contratos");
                    break;
                default:
                    header("Loaction: ../../public/index.php?action=dashboard");
            }
        }else{
            $_SESSION["errores"] = [$resultado["mensaje"]];
            header("Location: ../../public/index.php?action=login");
        }
        exit();
    }
    public function logout(){
        $_SESSION= [];
        session_destroy();
        header("Location: ../../public/index.php?action=login_cerrada");
        exit();
    }
    public static function verificarSession(){
        if(!isset($_SESSION["usuario"])){
            header("Location: ../../public/index.php?action=login");
            exit();
        }
    }
    public static function verificarRol($roles= []){
        self::verificarSession();
        if(!in_array($_SESSION["rol"],$roles)){
            header("Location: ../../public/index.php?error=acceso_denegado");
            exit();
        }
    }
}

?>