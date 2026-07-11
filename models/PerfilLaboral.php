<?php
require_once __DIR__ . '/../config/Conexion.php';
require_once __DIR__ . '/../helpers/FirmaDigital.php';

class PerfilLaboral {

    public static function crear(array $datos): int {
        $con = Conexion::obtenerInstancia();

        // 1. Desactivar el cargo activo anterior
        $con->ejecutar(
            "UPDATE perfiles_laborales
             SET cargo_activo = 0
             WHERE codigo_empleado = :codigo AND cargo_activo = 1",
            [':codigo' => $datos['codigo_empleado']]
        );

        // 2. Obtener nombre del tipo de empleado para la firma
        $tipoEmpleado = self::getTipoEmpleadoNombre($datos['id_tipo_empleado']);

        // 3. Firmar los datos sensibles
        $firma = FirmaDigital::firmar([
            'salario'         => $datos['salario'],
            'codigo_empleado' => $datos['codigo_empleado'],
            'tipo_empleado'   => $tipoEmpleado,
            'planilla'        => $datos['id_tipo_empleado'],  // Cambio: ahora es id_tipo_empleado
            'ocupacion'       => $datos['id_ocupacion'],
            'fecha_inicio'    => $datos['fecha_inicio'],
        ]);

        // 4. Determinar empleado_activo
        $empleadoActivo = !empty($datos['fecha_fin']) ? 0 : 1;

        // 5. Insertar el nuevo perfil
        $sql = "INSERT INTO perfiles_laborales
                (codigo_empleado, id_ocupacion, id_tipo_empleado, salario,
                 fecha_inicio, fecha_fin, cargo_activo, empleado_activo, 
                 id_motivo_terminacion, motivo_baja, firma_digital)
                VALUES
                (:codigo, :ocupacion, :tipo_empleado, :salario,
                 :fecha_inicio, :fecha_fin, 1, :empleado_activo,
                 :motivo_id, :motivo, :firma)";

        $con->ejecutar($sql, [
            ':codigo'          => $datos['codigo_empleado'],
            ':ocupacion'       => $datos['id_ocupacion'],
            ':tipo_empleado'   => $datos['id_tipo_empleado'],
            ':salario'         => $datos['salario'],
            ':fecha_inicio'    => $datos['fecha_inicio'],
            ':fecha_fin'       => $datos['fecha_fin'] ?: null,
            ':empleado_activo' => $empleadoActivo,
            ':motivo_id'       => $datos['id_motivo_terminacion'] ?? null,
            ':motivo'          => $datos['motivo_baja'] ?? null,
            ':firma'           => $firma,
        ]);

        return (int) $con->ultimoId();
    }

    private static function getTipoEmpleadoNombre(int $id): string {
        $con = Conexion::obtenerInstancia();
        $res = $con->consultar(
            "SELECT Nombre FROM cat_tipoempleado WHERE id = :id AND Activo = 1",
            [':id' => $id]
        );
        return $res[0]['Nombre'] ?? 'Desconocido';
    }

    public static function listarConColaborador(): array {
        $con = Conexion::obtenerInstancia();
        $sql = "SELECT
                    c.id_colaborador, c.identidad, c.nombre, c.apellido,
                    c.correo, c.celular, c.nacionalidad, c.edad,
                    s.nombre as sexo,
                    r.Nombre as ruta,
                    ts.Nombre as tipo_sangre,
                    p.id_perfil, p.salario, p.fecha_inicio, p.fecha_fin,
                    p.cargo_activo, p.empleado_activo, p.motivo_baja, p.firma_digital,
                    o.OCUPACION as ocupacion,
                    te.Nombre as planilla, te.id as id_tipo_empleado
                FROM colaboradores c
                LEFT JOIN perfiles_laborales p
                    ON p.codigo_empleado = c.id_colaborador AND p.cargo_activo = 1
                LEFT JOIN cat_ocupaciones o ON o.C_OCUP = p.id_ocupacion
                LEFT JOIN cat_tipoempleado te ON te.id = p.id_tipo_empleado
                LEFT JOIN cat_rutas r ON r.id = c.id_ruta
                LEFT JOIN tiposangre ts ON ts.id = c.id_tipo_sangre
                LEFT JOIN cat_sexo s ON s.id = c.id_sexo
                ORDER BY c.id_colaborador DESC";
        return $con->consultar($sql);
    }

    public static function historialPorColaborador(int $idColaborador): array {
        $con = Conexion::obtenerInstancia();
        $sql = "SELECT p.*, o.OCUPACION as ocupacion, te.Nombre as planilla,
                       tm.MOTIVO as motivo_terminacion
                FROM perfiles_laborales p
                LEFT JOIN cat_ocupaciones o ON o.C_OCUP = p.id_ocupacion
                LEFT JOIN cat_tipoempleado te ON te.id = p.id_tipo_empleado
                LEFT JOIN cat_motivos_terminacion tm ON tm.C_TERMINACION = p.id_motivo_terminacion
                WHERE p.codigo_empleado = :id
                ORDER BY p.fecha_inicio DESC";
        return $con->consultar($sql, [':id' => $idColaborador]);
    }

    public static function verificarIntegridad(array $fila): bool {
        if (empty($fila['firma_digital'])) {
            return false;
        }
        return FirmaDigital::verificar([
            'salario'         => $fila['salario'],
            'codigo_empleado' => $fila['id_colaborador'],
            'tipo_empleado'   => $fila['planilla'],
            'planilla'        => $fila['id_tipo_empleado'] ?? $fila['id_tipo_planilla'] ?? 0,
            'ocupacion'       => $fila['ocupacion'],
            'fecha_inicio'    => $fila['fecha_inicio'],
        ], $fila['firma_digital']);
    }
}