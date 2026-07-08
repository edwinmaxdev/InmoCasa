<?php
include_once __DIR__ . '/../../config/database.php';

class Inquilino {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT * FROM inquilinos ORDER BY nombre ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener inquilinos: " . $e->getMessage()];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT * FROM inquilinos WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener inquilino: " . $e->getMessage()];
        }
    }

    public function buscarPorCedula($cedula) {
        try {
            $sql = "SELECT * FROM inquilinos WHERE cedula = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$cedula]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al buscar inquilino: " . $e->getMessage()];
        }
    }

    public function buscarPorNombre($nombre) {
        try {
            $sql = "SELECT * FROM inquilinos WHERE nombre LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['%' . $nombre . '%']);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al buscar inquilino: " . $e->getMessage()];
        }
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO inquilinos(nombre, cedula, telefono, email, direccion, referencia)
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['cedula'],
                $datos['telefono'] ?? null,
                $datos['email'],
                $datos['direccion'] ?? null,
                $datos['referencia'] ?? null
            ]);
            return ["exito" => true, "mensaje" => "Inquilino creado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al crear inquilino: " . $e->getMessage()];
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE inquilinos SET
                    nombre = ?, cedula = ?, telefono = ?, email = ?, direccion = ?, referencia = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['cedula'],
                $datos['telefono'] ?? null,
                $datos['email'],
                $datos['direccion'] ?? null,
                $datos['referencia'] ?? null,
                $id
            ]);
            return ["exito" => true, "mensaje" => "Inquilino actualizado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al actualizar inquilino: " . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM inquilinos WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["exito" => true, "mensaje" => "Inquilino eliminado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al eliminar inquilino: " . $e->getMessage()];
        }
    }
}
?>