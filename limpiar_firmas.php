<?php
/**
 * Limpia todas las firmas de la base de datos
 * para luego regenerarlas con las llaves correctas
 */
require_once __DIR__ . '/config/Conexion.php';

echo "<h1>LIMPIANDO FIRMAS</h1>";

try {
    $con = Conexion::obtenerInstancia();
    
    // Verificar cuántos perfiles hay
    $perfiles = $con->consultar("SELECT COUNT(*) as total FROM perfiles_laborales");
    $total = $perfiles[0]['total'];
    
    echo "Perfiles encontrados: <strong>$total</strong><br>";
    
    if ($total == 0) {
        echo "No hay perfiles para limpiar.<br>";
        echo "<a href='index.php'>Volver al inicio</a>";
        exit;
    }
    
    // Eliminar todas las firmas
    $con->ejecutar("UPDATE perfiles_laborales SET firma_digital = NULL");
    
    echo "Se eliminaron todas las firmas de la base de datos.<br>";
    echo "Ahora puedes ejecutar <a href='actualizar_firmas.php'>actualizar_firmas.php</a> para regenerarlas.<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<br><a href='index.php'>Volver al inicio</a>";
?>