<?php
require_once __DIR__ . '/../config/Conexion.php';

class Colaborador {

    public static function crear(array $datos): int {
        $con = Conexion::obtenerInstancia();
        $sql = "INSERT INTO colaboradores
                (identidad, nombre, apellido, edad, id_tipo_sangre, id_sexo, 
                 nacionalidad, id_ruta, correo, celular, id_estado_civil)
                VALUES
                (:identidad, :nombre, :apellido, :edad, :tipo_sangre, :sexo, 
                 :nacionalidad, :ruta, :correo, :celular, :estado_civil)";

        // Determinar id_sexo basado en el valor recibido (M/F o id)
        $idSexo = self::getSexoId($datos['sexo'] ?? '');
        
        $con->ejecutar($sql, [
            ':identidad'    => $datos['identidad'],
            ':nombre'       => $datos['nombre'],
            ':apellido'     => $datos['apellido'],
            ':edad'         => $datos['edad'],
            ':tipo_sangre'  => $datos['id_tipo_sangre'],
            ':sexo'         => $idSexo,
            ':nacionalidad' => $datos['nacionalidad'],
            ':ruta'         => $datos['id_ruta'],
            ':correo'       => $datos['correo'],
            ':celular'      => $datos['celular'],
            ':estado_civil' => $datos['id_estado_civil'] ?? 1,
        ]);

        return (int) $con->ultimoId();
    }

    private static function getSexoId($sexo): int {
        // Si ya es un ID numérico, devolverlo
        if (is_numeric($sexo)) return (int)$sexo;
        
        // Si es 'M' o 'F', mapear a IDs de cat_sexo
        $map = ['M' => 2, 'F' => 3];
        return $map[$sexo] ?? 2; // Por defecto: Hombre
    }

    public static function listarTodos(): array {
        $con = Conexion::obtenerInstancia();
        return $con->consultar("SELECT * FROM colaboradores ORDER BY id_colaborador DESC");
    }

    public static function buscarPorId(int $id): ?array {
        $con = Conexion::obtenerInstancia();
        $res = $con->consultar("SELECT * FROM colaboradores WHERE id_colaborador = :id", [':id' => $id]);
        return $res[0] ?? null;
    }

    public static function existeIdentidad(string $identidad): bool {
        $con = Conexion::obtenerInstancia();
        $res = $con->consultar("SELECT id_colaborador FROM colaboradores WHERE identidad = :identidad", [':identidad' => $identidad]);
        return count($res) > 0;
    }

    public static function existeCorreo(string $correo): bool {
        $con = Conexion::obtenerInstancia();
        $res = $con->consultar("SELECT id_colaborador FROM colaboradores WHERE correo = :correo", [':correo' => $correo]);
        return count($res) > 0;
    }
}