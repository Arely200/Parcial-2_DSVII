<?php
require_once __DIR__ . '/../config/Conexion.php';

class Catalogo {
    public static function tiposSangre(): array {
        return Conexion::obtenerInstancia()->consultar(
            "SELECT id, Nombre as nombre FROM tiposangre ORDER BY Nombre"
        );
    }

    public static function rutas(): array {
        return Conexion::obtenerInstancia()->consultar(
            "SELECT id, Nombre as nombre FROM cat_rutas ORDER BY Nombre"
        );
    }

    public static function ocupaciones(): array {
        return Conexion::obtenerInstancia()->consultar(
            "SELECT C_OCUP as id_ocupacion, OCUPACION as nombre FROM cat_ocupaciones WHERE Activo = 1 ORDER BY OCUPACION"
        );
    }

    public static function tiposEmpleado(): array {  // Cambio: renombrado de tiposPlanilla
        return Conexion::obtenerInstancia()->consultar(
            "SELECT id, Nombre as nombre FROM cat_tipoempleado WHERE Activo = 1 ORDER BY Nombre"
        );
    }

    public static function sexos(): array {  // Nuevo método
        return Conexion::obtenerInstancia()->consultar(
            "SELECT id, nombre FROM cat_sexo ORDER BY nombre"
        );
    }

    public static function estadosCiviles(): array {  // Nuevo método
        return Conexion::obtenerInstancia()->consultar(
            "SELECT id, nombre FROM cat_estadocivil WHERE id != 1 ORDER BY nombre"  // Excluye "Seleccionar"
        );
    }

    public static function motivosTerminacion(): array {  // Nuevo método
        return Conexion::obtenerInstancia()->consultar(
            "SELECT C_TERMINACION as id, MOTIVO as nombre FROM cat_motivos_terminacion ORDER BY MOTIVO"
        );
    }

    // Mantener por compatibilidad (deprecado)
    public static function tiposPlanilla(): array {
        return self::tiposEmpleado();
    }
}