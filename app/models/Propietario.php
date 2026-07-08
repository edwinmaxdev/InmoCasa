<?php
include_once __DIR__ . '/../../config/database.php';

class Propietario {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM propietarios ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener propietarios: " . $e->getMessage()];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM propietarios WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener propietario: " . $e->getMessage()];
        }
    }

    public function buscarPorCedula($cedula) {
        try {
            $sql = "SELECT * FROM propietarios WHERE cedula = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cedula]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al buscar propietario: " . $e->getMessage()];
        }
    }

    public function buscarPorNombre($nombre) {
        try {
            $sql = "SELECT * FROM propietarios WHERE nombre LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['%' . $nombre . '%']);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al buscar propietario: " . $e->getMessage()];
        }
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO propietarios(nombre, cedula, telefono, email, direccion)
                    VALUES(?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['cedula'],
                $datos['telefono'] ?? null,
                $datos['email'],
                $datos['direccion'] ?? null
            ]);
            return ["exito" => true, "mensaje" => "Propietario creado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al crear propietario: " . $e->getMessage()];
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE propietarios SET
                    nombre = ?, cedula = ?, telefono = ?, email = ?, direccion = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['cedula'],
                $datos['telefono'] ?? null,
                $datos['email'],
                $datos['direccion'] ?? null,
                $id
            ]);
            return ["exito" => true, "mensaje" => "Propietario actualizado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al actualizar propietario: " . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM propietarios WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["exito" => true, "mensaje" => "Propietario eliminado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al eliminar propietario: " . $e->getMessage()];
        }
    }
}
?>