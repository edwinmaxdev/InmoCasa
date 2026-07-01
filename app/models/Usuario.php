<?php
include_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConexion();
    }

    public function obtenerTodos() {
        try {
            $sql = "SELECT id, nombre, email, rol, propietario_id, inquilino_id, created_at
                    FROM usuarios
                    ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener usuarios: " . $e->getMessage()];
        }
    }

    public function obtenerPorId($id) {
        try {
            $sql = "SELECT id, nombre, email, rol, propietario_id, inquilino_id, created_at
                    FROM usuarios
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al obtener usuario: " . $e->getMessage()];
        }
    }

    public function buscarPorEmail($email) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al buscar usuario: " . $e->getMessage()];
        }
    }

    public function login($email, $password) {
        try {
            $usuario = $this->buscarPorEmail($email);

            if (!$usuario) {
                return ["exito" => false, "mensaje" => "El email no está registrado"];
            }

            if (!password_verify($password, $usuario['password'])) {
                return ["exito" => false, "mensaje" => "Contraseña incorrecta"];
            }
            // Guardar datos en sesión
            $_SESSION['usuario_id']  = $usuario['id'];
            $_SESSION['nombre']      = $usuario['nombre'];
            $_SESSION['email']       = $usuario['email'];
            $_SESSION['rol']         = $usuario['rol'];
            $_SESSION['propietario_id'] = $usuario['propietario_id'];
            $_SESSION['inquilino_id']   = $usuario['inquilino_id'];

            return ["exito" => true, "mensaje" => "Login exitoso", "rol" => $usuario['rol']];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al iniciar sesión: " . $e->getMessage()];
        }
    }

    public function crear($datos) {
        try {
            $sql = "INSERT INTO usuarios(nombre, email, password, rol, propietario_id, inquilino_id)
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['email'],
                password_hash($datos['password'], PASSWORD_DEFAULT),
                $datos['rol'],
                $datos['propietario_id'] ?? null,
                $datos['inquilino_id']   ?? null
            ]);
            return ["exito" => true, "mensaje" => "Usuario creado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al crear usuario: " . $e->getMessage()];
        }
    }

    public function actualizar($id, $datos) {
        try {
            // Si viene contraseña nueva la encripta, si no deja la anterior
            if (!empty($datos['password'])) {
                $sql = "UPDATE usuarios SET
                        nombre = ?, email = ?, password = ?,
                        rol = ?, propietario_id = ?, inquilino_id = ?
                        WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    $datos['nombre'],
                    $datos['email'],
                    password_hash($datos['password'], PASSWORD_DEFAULT),
                    $datos['rol'],
                    $datos['propietario_id'] ?? null,
                    $datos['inquilino_id']   ?? null,
                    $id
                ]);
            } else {
                $sql = "UPDATE usuarios SET
                        nombre = ?, email = ?,
                        rol = ?, propietario_id = ?, inquilino_id = ?
                        WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    $datos['nombre'],
                    $datos['email'],
                    $datos['rol'],
                    $datos['propietario_id'] ?? null,
                    $datos['inquilino_id']   ?? null,
                    $id
                ]);
            }
            return ["exito" => true, "mensaje" => "Usuario actualizado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al actualizar usuario: " . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return ["exito" => true, "mensaje" => "Usuario eliminado exitosamente"];
        } catch (PDOException $e) {
            return ["exito" => false, "mensaje" => "Error al eliminar usuario: " . $e->getMessage()];
        }
    }
}
?>