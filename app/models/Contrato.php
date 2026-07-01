<?php
include_once __DIR__ . '/../../config/database.php';

class Contrato{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos(){
        try{
            $sql = "SELECT c.* , 
            p.direccion AS propiedad_direccion,
            i.nombre AS nombre_inquilino
            FROM contratos c
            INNER JOIN propiedades p ON c.propiedad_id = p.id
            INNER JOIN inquilinos i ON c.inquilino_id = i.id
            ORDER BY c.created_at DESC ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de obtener por id" . $e->getMessage()];
        }
    }

    public function obtenerPorId($id){
        try{
            $sql = "SELECT c.*, 
            p.direccion AS propiedades_direccion,
            i.nombre AS nombre_inquilino
            FROM contratos c
            INNER JOIN propiedades p ON c.propiedad_id = p.id
            Inner join inquilinos i ON c.inquilino_id = i.id
            WHERE c.id = ?";
            
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de obtener por id" . $e->getMessage()];
        }
    }

    public function crear($datos){
        try{
            $sql = "INSERT INTO contratos(propiedad_id, inquilino_id, fecha_inicio, fecha_fin, monto_mensual, estado, observaciones)
                VALUES(?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['propiedad_id'],
                $datos['inquilino_id'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['monto_mensual'],
                $datos['estado'],
                $datos['observaciones']
            ]);
            return ["Exito"=> true , "mensaje"=>"Exito de la creacion del contractos"];
            
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de creacion del contractos" . $e->getMessage()];

        }
    }

    public function actualizar($id, $datos){
        try{
            $sql = "UPDATE contratos SET propiedad_id = ? , inquilino_id = ?, fecha_inicio = ?, fecha_fin = ? , monto_mensual = ? , estado= ? , observaciones = ?
                WHERE id = ? ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $datos['propiedad_id'],
            $datos['inquilino_id'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['monto_mensual'],
            $datos['estado'],
            $datos['observaciones'],
            $id
        ]);
        return ["Exito"=> true , "mensaje"=>"Actualizacion del contractos" ];
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de la actualizacion del contractos". $e->getMessage()];
        }
    }
    public function eliminar($id){
        try{
            $sql = "DELETE FROM contratos WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["Exito"=> true , "mensaje"=>"Eliminacion del contractos exitoso"];
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error a la eliminacion del contractos". $e->getMessage()];
        }
    }
    public function obtenerActivos(){
        try{
            $sql = "SELECT c.*, 
                p.direccion AS propiedad_direccion,
                i.nombre AS inquilino_nombre
                FROM contratos c
                INNER JOIN propiedades p ON c.propiedad_id = p.id
                INNER JOIN inquilinos i ON c.inquilino_id = i.id
                WHERE c.estado = 'Activo'
                ORDER BY c.fecha_fin ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de activos". $e->getMessage()];
        }
    }
    public function obtenerProximosAVencer(){
        try{
            $sql = "SELECT c.*, 
                p.direccion AS propiedad_direccion,
                i.nombre AS inquilino_nombre,
                DATEDIFF(c.fecha_fin, CURDATE()) AS dias_restantes
                FROM contratos c
                INNER JOIN propiedades p ON c.propiedad_id = p.id
                INNER JOIN inquilinos i ON c.inquilino_id = i.id
                WHERE c.estado = 'Activo'
                AND DATEDIFF(c.fecha_fin, CURDATE()) BETWEEN 0 AND 30
                ORDER BY c.fecha_fin ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de obtencion de proximo a vencer". $e->getMessage()];
        }
    }

    }

?>