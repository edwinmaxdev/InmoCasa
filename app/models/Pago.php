<?php
include_once __DIR__ . '/../../config/database.php';

class Pago {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT p.*, 
                    c.monto_mensual AS contrato_monto,
                    i.nombre AS inquilino_nombre
                    FROM pagos p
                    INNER JOIN contratos c ON p.contrato_id = c.id
                    INNER JOIN inquilinos i ON c.inquilino_id = i.id
                    ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener pagos: " . $e->getMessage()];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT p.*, 
                    c.monto_mensual AS contrato_monto,
                    i.nombre AS inquilino_nombre
                    FROM pagos p
                    INNER JOIN contratos c ON p.contrato_id = c.id
                    INNER JOIN inquilinos i ON c.inquilino_id = i.id
                    WHERE p.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener pago: " . $e->getMessage()];
        }
    }

    public function obtenerPorContrato($contrato_id) {
        try {
            $sql = "SELECT p.*, 
                    i.nombre AS inquilino_nombre
                    FROM pagos p
                    INNER JOIN contratos c ON p.contrato_id = c.id
                    INNER JOIN inquilinos i ON c.inquilino_id = i.id
                    WHERE p.contrato_id = ?
                    ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$contrato_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener pagos por contrato: " . $e->getMessage()];
        }
    }

    public function obtenerHistorial($inquilino_id) {
        try {
            $sql = "SELECT p.*, 
                    c.monto_mensual AS contrato_monto,
                    pr.direccion AS propiedad_direccion
                    FROM pagos p
                    INNER JOIN contratos c ON p.contrato_id = c.id
                    INNER JOIN propiedades pr ON c.propiedad_id = pr.id
                    WHERE c.inquilino_id = ?
                    ORDER BY p.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$inquilino_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener historial: " . $e->getMessage()];
        }
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO pagos(contrato_id, fecha_pago, monto, mes_correspondiente, estado, observaciones)
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['contrato_id'],
                $datos['fecha_pago'],
                $datos['monto'],
                $datos['mes_correspondiente'],
                $datos['estado'] ?? 'Pendiente',
                $datos['observaciones'] ?? null
            ]);
            return ["exito" => true, "mensaje" => "Pago registrado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al crear pago: " . $e->getMessage()];
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE pagos SET 
                    contrato_id = ?,
                    fecha_pago = ?,
                    monto = ?,
                    mes_correspondiente = ?,
                    estado = ?,
                    observaciones = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['contrato_id'],
                $datos['fecha_pago'],
                $datos['monto'],
                $datos['mes_correspondiente'],
                $datos['estado'],
                $datos['observaciones'] ?? null,
                $id
            ]);
            return ["exito" => true, "mensaje" => "Pago actualizado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al actualizar pago: " . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM pagos WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["exito" => true, "mensaje" => "Pago eliminado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al eliminar pago: " . $e->getMessage()];
        }
    }

    public function contarPendientes() {
        try {
            $sql = "SELECT COUNT(*) AS total FROM pagos WHERE estado = 'Pendiente'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch()['total'];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al contar pendientes: " . $e->getMessage()];
        }
    }
}
?>