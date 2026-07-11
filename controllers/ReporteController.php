<?php
require_once __DIR__ . '/../models/PerfilLaboral.php';

class ReporteController {
    public static function obtenerDatosReporte(): array {
        $filas = PerfilLaboral::listarConColaborador();
        foreach ($filas as &$fila) {
            $fila['integridad'] = PerfilLaboral::verificarIntegridad($fila);
        }
        unset($fila);
        return $filas;
    }
}
