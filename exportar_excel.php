<?php
/**
 * Exporta el reporte a un archivo abrible en Excel.
 * (Usa formato HTML-table con encabezados de descarga; no requiere librerías externas).
 */
require_once __DIR__ . '/controllers/ReporteController.php';

$datos = ReporteController::obtenerDatosReporte();

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="reporte_colaboradores_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

echo "\xEF\xBB\xBF"; // BOM para que Excel reconozca UTF-8 (tildes, ñ)
?>
<table border="1">
    <tr>
        <th>Código</th><th>Identidad</th><th>Nombre</th><th>Apellido</th>
        <th>Correo</th><th>Celular</th><th>Ruta</th><th>Tipo Sangre</th>
        <th>Ocupación</th><th>Planilla</th><th>Salario</th>
        <th>Fecha Inicio</th><th>Fecha Fin</th><th>Empleado Activo</th>
        <th>Motivo Baja</th><th>Integridad</th>
    </tr>
    <?php foreach ($datos as $fila): ?>
    <tr>
        <td><?= htmlspecialchars($fila['id_colaborador']) ?></td>
        <td><?= htmlspecialchars($fila['identidad']) ?></td>
        <td><?= htmlspecialchars($fila['nombre']) ?></td>
        <td><?= htmlspecialchars($fila['apellido']) ?></td>
        <td><?= htmlspecialchars($fila['correo']) ?></td>
        <td><?= htmlspecialchars($fila['celular']) ?></td>
        <td><?= htmlspecialchars($fila['ruta'] ?? '') ?></td>
        <td><?= htmlspecialchars($fila['tipo_sangre'] ?? '') ?></td>
        <td><?= htmlspecialchars($fila['ocupacion'] ?? '') ?></td>
        <td><?= htmlspecialchars($fila['planilla'] ?? '') ?></td>
        <td><?= htmlspecialchars($fila['salario'] ?? '') ?></td>
        <td><?= htmlspecialchars($fila['fecha_inicio'] ?? '') ?></td>
        <td><?= htmlspecialchars($fila['fecha_fin'] ?? '') ?></td>
        <td><?= $fila['empleado_activo'] ? 'Sí' : 'No' ?></td>
        <td><?= htmlspecialchars($fila['motivo_baja'] ?? '') ?></td>
        <td><?= !empty($fila['id_perfil']) ? ($fila['integridad'] ? 'Íntegro' : 'Corrupto') : '-' ?></td>
    </tr>
    <?php endforeach; ?>
</table>
