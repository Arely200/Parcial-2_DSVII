<?php
/**
 * Actualiza las firmas de los perfiles laborales existentes
 * con firmas reales generadas con las llaves OpenSSL
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/Conexion.php';
require_once __DIR__ . '/helpers/FirmaDigital.php';

echo "<h1> ACTUALIZACIÓN DE FIRMAS</h1>";

// Verificar que existen las llaves
$privateKey = __DIR__ . '/keys/private_key.pem';
$publicKey = __DIR__ . '/keys/public_key.pem';

if (!file_exists($privateKey) || !file_exists($publicKey)) {
    echo "No existen las llaves OpenSSL.<br>";
    echo "Ejecuta primero: <a href='setup_llaves.php'>setup_llaves.php</a><br>";
    exit;
}

echo "Llaves OpenSSL encontradas.<br><br>";

$con = Conexion::obtenerInstancia();

// Verificar si hay perfiles
$perfiles = $con->consultar("SELECT COUNT(*) as total FROM perfiles_laborales");
$totalPerfiles = $perfiles[0]['total'];

if ($totalPerfiles == 0) {
    echo "No hay perfiles laborales para actualizar.<br>";
    echo "<a href='index.php'>Volver al inicio</a>";
    exit;
}

echo "Encontrados " . $totalPerfiles . " perfiles laborales.<br><br>";

// Obtener todos los perfiles con sus datos
$perfiles = $con->consultar("
    SELECT 
        p.id_perfil,
        p.codigo_empleado,
        p.salario,
        p.fecha_inicio,
        p.id_tipo_empleado,
        te.Nombre as tipo_empleado,
        o.C_OCUP as id_ocupacion,
        o.OCUPACION as ocupacion,
        c.nombre as colaborador_nombre,
        c.apellido as colaborador_apellido
    FROM perfiles_laborales p
    LEFT JOIN cat_tipoempleado te ON te.id = p.id_tipo_empleado
    LEFT JOIN cat_ocupaciones o ON o.C_OCUP = p.id_ocupacion
    LEFT JOIN colaboradores c ON c.id_colaborador = p.codigo_empleado
");

$actualizados = 0;
$errores = 0;

foreach ($perfiles as $perfil) {
    try {
        // Datos para la firma - deben coincidir EXACTAMENTE con los que se usaron al firmar
        $datos = [
            'salario'         => $perfil['salario'],
            'codigo_empleado' => $perfil['codigo_empleado'],
            'tipo_empleado'   => $perfil['tipo_empleado'] ?? 'Desconocido',
            'planilla'        => $perfil['id_tipo_empleado'],
            'ocupacion'       => $perfil['ocupacion'] ?? 'Desconocido',
            'fecha_inicio'    => $perfil['fecha_inicio'],
        ];
        
        // Generar firma real con las llaves
        $firma = FirmaDigital::firmar($datos);
        
        // Actualizar en la base de datos
        $con->ejecutar(
            "UPDATE perfiles_laborales SET firma_digital = :firma WHERE id_perfil = :id",
            [':firma' => $firma, ':id' => $perfil['id_perfil']]
        );
        
        $actualizados++;
        echo "Perfil #{$perfil['id_perfil']} - {$perfil['colaborador_nombre']} {$perfil['colaborador_apellido']} - Firmado correctamente<br>";
        
    } catch (Exception $e) {
        $errores++;
        echo " Error en perfil #{$perfil['id_perfil']}: " . $e->getMessage() . "<br>";
    }
}

echo "<br><hr>";
echo "<h2>Resumen:</h2>";
echo "Actualizados: <strong>$actualizados</strong><br>";
echo "Errores: <strong>$errores</strong><br>";
echo "Total: <strong>$totalPerfiles</strong><br>";

echo "<br><a href='views/reporte.php'>Ver reporte</a> | ";
echo "<a href='index.php'>Volver al inicio</a>";
?>