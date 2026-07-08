<?php
include_once __DIR__ . '/../../config/database.php';

class Propiedad{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos(){
        try{
            $sql = "SELECT p.*,
            t.nombre AS tipo_nombre,
            pr.nombre AS propietario_nombre
            FROM propiedades p
            INNER JOIN tipos_inmueble t ON p.tipo_id = t.id
            INNER JOIN propietarios pr ON p.propietario_id = pr.id
            ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al obtener propiedades: ".$e->getMessage()];
        }
    }

    public function obtenerPorId($id){
        try{
            $sql = "SELECT p.*,
            t.nombre AS tipo_nombre,
            pr.nombre AS propietario_nombre
            FROM propiedades p
            INNER JOIN tipos_inmueble t ON p.tipo_id = t.id
            INNER JOIN propietarios pr ON p.propietario_id = pr.id
            WHERE p.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al obtener propiedad: ".$e->getMessage()];
        }
    }

    public function obtenerPorPropietario($propietarioId){
        try{
            $sql = "SELECT p.*,
            t.nombre AS tipo_nombre,
            pr.nombre AS propietario_nombre
            FROM propiedades p
            INNER JOIN tipos_inmueble t ON p.tipo_id = t.id
            INNER JOIN propietarios pr ON p.propietario_id = pr.id
            WHERE p.propietario_id = ?
            ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$propietarioId]);
            return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al obtener propiedades del propietario: ".$e->getMessage()];
        }
    }

    public function crear($datos){
        try{
            $sql = "INSERT INTO propiedades(direccion, precio, metros2, descripcion, estado, tipo_id, propietario_id)
                    VALUES(?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['direccion'],
                $datos['precio'],
                $datos['metros2'],
                $datos['descripcion'],
                $datos['estado'],
                $datos['tipo_id'],
                $datos['propietario_id']
            ]);
            return ["exito"=>true,"mensaje"=>"Propiedad creada exitosamente"];
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al crear propiedad: ".$e->getMessage()];
        }
    }

    public function actualizar($id, $datos){
        try{
            $sql = "UPDATE propiedades SET
                    direccion = ?, precio = ?, metros2 = ?, descripcion = ?,
                    estado = ?, tipo_id = ?, propietario_id = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['direccion'],
                $datos['precio'],
                $datos['metros2'],
                $datos['descripcion'],
                $datos['estado'],
                $datos['tipo_id'],
                $datos['propietario_id'],
                $id
            ]);
            return ["exito"=>true,"mensaje"=>"Propiedad actualizada exitosamente"];
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al actualizar propiedad: ".$e->getMessage()];
        }
    }

    public function eliminar($id){
        try{
            $sql = "DELETE FROM propiedades WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["exito"=>true,"mensaje"=>"Propiedad eliminada exitosamente"];
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al eliminar propiedad: ".$e->getMessage()];
        }
    }

    public function filtrarPorEstado($estado){
        try{
            $sql = "SELECT p.*,
            t.nombre AS tipo_nombre,
            pr.nombre AS propietario_nombre
            FROM propiedades p
            INNER JOIN tipos_inmueble t ON p.tipo_id = t.id
            INNER JOIN propietarios pr ON p.propietario_id = pr.id
            WHERE p.estado = ?
            ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$estado]);
            return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al filtrar por estado: ".$e->getMessage()];
        }
    }

    public function filtrarPorTipo($tipoId){
        try{
            $sql = "SELECT p.*,
            t.nombre AS tipo_nombre,
            pr.nombre AS propietario_nombre
            FROM propiedades p
            INNER JOIN tipos_inmueble t ON p.tipo_id = t.id
            INNER JOIN propietarios pr ON p.propietario_id = pr.id
            WHERE p.tipo_id = ?
            ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$tipoId]);
            return $stmt->fetchAll();
        }catch(PDOException $e){
            return ["exito"=>false,"mensaje"=>"Error al filtrar por tipo: ".$e->getMessage()];
        }
    }

    public function contarDisponibles(){
        try{
            $sql = "SELECT COUNT(*) AS total FROM propiedades WHERE estado = 'Disponible'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch();
            return $resultado['total'];
        }catch(PDOException $e){
            return 0;
        }
    }
}
?>