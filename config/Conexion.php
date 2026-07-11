<?php
/**
 * Clase Conexion (Singleton)
 * Maneja una única instancia de conexión PDO hacia la base de datos.
 */
class Conexion {
    private static ?Conexion $instancia = null;
    private PDO $pdo;

    // Datos de conexión (ajustar según tu entorno WAMP)
    private string $host = 'localhost';
    private string $bd   = 'itech_contrataciones';
    private string $usuario = 'root';
    private string $clave   = '';
    private string $charset = 'utf8mb4';

    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->bd};charset={$this->charset}";
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->usuario, $this->clave, $opciones);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function obtenerInstancia(): Conexion {
        if (self::$instancia === null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia;
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }

    public function consultar(string $sql, array $params = []): array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function ejecutar(string $sql, array $params = []): bool {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function ultimoId(): string {
        return $this->pdo->lastInsertId();
    }

    // Evita clonar o deserializar la instancia (patrón Singleton)
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("No se puede deserializar un Singleton.");
    }
}
