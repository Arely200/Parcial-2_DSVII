<?php
/**
 * Clase Validator
 * Métodos estáticos de validación de datos.
 */
class Validator {

    // ===== VALIDACIONES GENERALES =====
    
    public static function esVacio($valor): bool {
        return trim((string)$valor) === '';
    }

    public static function validarEntero($valor): bool {
        return filter_var($valor, FILTER_VALIDATE_INT) !== false;
    }

    public static function validarFecha(string $fecha): bool {
        $d = DateTime::createFromFormat('Y-m-d', $fecha);
        return $d && $d->format('Y-m-d') === $fecha;
    }

    public static function validarBooleano($valor): bool {
        return in_array($valor, [0, 1, '0', '1'], true);
    }

    public static function validarTextoNoVacio(string $texto, int $min = 2): bool {
        $texto = trim($texto);
        return strlen($texto) >= $min;
    }

    // ===== VALIDACIONES DE COLABORADOR =====
    
    public static function validarIdentidad(string $identidad): bool {
        return (bool) preg_match('/^[0-9A-Za-z\-]{5,20}$/', $identidad);
    }

    public static function validarNombre(string $texto): bool {
        return (bool) preg_match('/^[A-Za-zÁÉÍÓÚÑáéíóúñ\s]{2,60}$/u', $texto);
    }

    public static function validarEdad($edad): bool {
        return is_numeric($edad) && $edad >= 18 && $edad <= 99;
    }

    public static function validarSexo($sexo): bool {
        if (is_numeric($sexo)) {
            return in_array((int)$sexo, [1, 2, 3]);
        }
        return in_array($sexo, ['M', 'F'], true);
    }

    public static function validarSexoId($sexo): bool {
        return is_numeric($sexo) && in_array((int)$sexo, [1, 2, 3]);
    }

    public static function validarCorreo(string $correo): bool {
        return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validarCelular(string $celular): bool {
        return (bool) preg_match('/^6[0-9]{3}-[0-9]{4}$/', $celular);
    }

    public static function validarEstadoCivil($estado): bool {
        return is_numeric($estado) && (int)$estado > 0;
    }

    public static function validarRuta($ruta): bool {
        return is_numeric($ruta) && (int)$ruta > 0;
    }

    public static function validarTipoSangre($tipo): bool {
        return is_numeric($tipo) && (int)$tipo > 0;
    }

    // ===== VALIDACIONES DE PERFIL LABORAL =====
    
    public static function validarSalario($salario): bool {
        return is_numeric($salario) && (float)$salario > 0;
    }

    public static function validarTipoEmpleado($tipo): bool {
        return is_numeric($tipo) && (int)$tipo > 0;
    }

    public static function validarMotivoTerminacion($motivo): bool {
        return is_numeric($motivo) && (int)$motivo > 0;
    }

    public static function validarOcupacion($ocupacion): bool {
        return is_numeric($ocupacion) && (int)$ocupacion > 0;
    }

    /**
     * Valida que el código de empleado sea un entero positivo
     * Este es el método que está causando el error
     */
    public static function validarCodigoEmpleado($codigo): bool {
        return is_numeric($codigo) && (int)$codigo > 0;
    }

    /**
     * Alias de validarCodigoEmpleado para compatibilidad
     */
    public static function validarColaborador($codigo): bool {
        return self::validarCodigoEmpleado($codigo);
    }

    // ===== VALIDACIONES DE TEXTO =====
    
    public static function validarTextoLongitud(string $texto, int $min = 2, int $max = 255): bool {
        $texto = trim($texto);
        $len = strlen($texto);
        return $len >= $min && $len <= $max;
    }

    /**
     * Valida que el motivo de baja tenga texto si se proporciona
     */
    public static function validarMotivoBaja($motivo): bool {
        return empty($motivo) || strlen(trim($motivo)) >= 3;
    }
}