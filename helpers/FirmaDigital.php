<?php
/**
 * Clase FirmaDigital
 * Firma y verifica la integridad de los datos sensibles del perfil laboral
 * usando OpenSSL.
 */
class FirmaDigital {

    private static string $rutaLlavePrivada = __DIR__ . '/../keys/private_key.pem';
    private static string $rutaLlavePublica  = __DIR__ . '/../keys/public_key.pem';

    public static function generarLlaves(): void {
        $carpeta = __DIR__ . '/../keys';
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0700, true);
        }

        $config = [
            "digest_alg"       => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $recurso = openssl_pkey_new($config);
        if ($recurso === false) {
            die("Error generando llaves OpenSSL.");
        }

        openssl_pkey_export($recurso, $llavePrivada);
        $detalles = openssl_pkey_get_details($recurso);

        file_put_contents(self::$rutaLlavePrivada, $llavePrivada);
        file_put_contents(self::$rutaLlavePublica, $detalles['key']);
    }

    private static function construirCadena(array $datos): string {
        return implode('|', [
            $datos['salario'],
            $datos['codigo_empleado'],
            $datos['tipo_empleado'],
            $datos['planilla'],
            $datos['ocupacion'],
            $datos['fecha_inicio'],
        ]);
    }

    public static function firmar(array $datos): string {
        $llavePrivada = openssl_pkey_get_private(file_get_contents(self::$rutaLlavePrivada));
        $cadena = self::construirCadena($datos);
        openssl_sign($cadena, $firma, $llavePrivada, OPENSSL_ALGO_SHA256);
        return base64_encode($firma);
    }

    public static function verificar(array $datos, ?string $firmaBase64): bool {
        if (empty($firmaBase64)) {
            return false;
        }
        
        // Verificar que la llave pública existe
        if (!file_exists(self::$rutaLlavePublica)) {
            return false;
        }
        
        $llavePublica = openssl_pkey_get_public(file_get_contents(self::$rutaLlavePublica));
        if ($llavePublica === false) {
            return false;
        }
        
        $cadena = self::construirCadena($datos);
        $firma = base64_decode($firmaBase64);
        return openssl_verify($cadena, $firma, $llavePublica, OPENSSL_ALGO_SHA256) === 1;
    }
}