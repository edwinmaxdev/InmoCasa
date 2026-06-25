<?php
define('DB_HOST','tu_localhost');
define('DB_USER','tu_usuario');
define('DB_NAME','inmoCasa');
define('DB_PASS','tu_contraseña');
define('DB_PORT','tu_puerto');


class Database{
    private static $conn = null;
    private function __clone(){}
    private function __construct(){}

    public static function getConexion(){
        if( self::$conn === null){
            try{
                $dsn = 'mysql:host='.DB_HOST . 
                ';port='. DB_PORT . 
                ';dbname='.DB_NAME . 
                ';charset=utf8';

                self::$conn = new PDO($dsn , DB_USER,DB_PASS ,[
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            }catch(PDOException $e){
                die("Error de conexion: ". $e->getMessage());
            }
        }
        return self::$conn;
    }
    
}
?>