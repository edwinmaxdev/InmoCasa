<?php
include_once __DIR__ . '/../../config/database.php';

class TipoInmueble {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM tipos_inmueble ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener tipos: " . $e->getMessage()];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM tipos_inmueble WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener tipo: " . $e->getMessage()];
        }
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO tipos_inmueble(nombre, descripcion) VALUES(?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['descripcion'] ?? null
            ]);
            return ["exito" => true, "mensaje" => "Tipo de inmueble creado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al crear tipo: " . $e->getMessage()];
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE tipos_inmueble SET nombre = ?, descripcion = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['descripcion'] ?? null,
                $id
            ]);
            return ["exito" => true, "mensaje" => "Tipo de inmueble actualizado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al actualizar tipo: " . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM tipos_inmueble WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["exito" => true, "mensaje" => "Tipo de inmueble eliminado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al eliminar tipo: " . $e->getMessage()];
        }
    }
}
?>