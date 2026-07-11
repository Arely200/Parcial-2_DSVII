<?php
/**
 * Clase Sanitizer
 * Métodos estáticos de sanitización / limpieza de datos (Punto 29).
 */
class Sanitizer {

    public static function limpiarTexto(string $texto): string {
        $texto = trim($texto);
        $texto = strip_tags($texto);
        return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    }

    // Convierte a "Tipo Título": ej. "juan carlos" -> "Juan Carlos" (Punto 30)
    public static function aTitulo(string $texto): string {
        $texto = mb_strtolower(trim($texto), 'UTF-8');
        return mb_convert_case($texto, MB_CASE_TITLE, 'UTF-8');
    }

    public static function limpiarNumero(string $valor): string {
        return preg_replace('/[^0-9.\-]/', '', trim($valor));
    }

    public static function limpiarCorreo(string $correo): string {
        return filter_var(trim($correo), FILTER_SANITIZE_EMAIL);
    }

    public static function limpiarCelular(string $celular): string {
        return preg_replace('/[^0-9\-]/', '', trim($celular));
    }
}
