<?php
require_once __DIR__ . '/../controllers/ReporteController.php';
$datos = ReporteController::obtenerDatosReporte();

// Contar estadísticas
$totalColaboradores = count($datos);
$conPerfil = 0;
$integridadOK = 0;
$integridadFAIL = 0;

foreach ($datos as $fila) {
    if (!empty($fila['id_perfil'])) {
        $conPerfil++;
        if ($fila['integridad']) {
            $integridadOK++;
        } else {
            $integridadFAIL++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte · iTECH</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========== HEADER ========== */
        .app-header {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            border-bottom: 3px solid #34d399;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .app-header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .app-header .logo .icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #34d399, #10b981);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .app-header .logo h1 {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .app-header .logo h1 span {
            color: #34d399;
        }

        .app-header nav {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .app-header nav a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .app-header nav a:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
        }

        .app-header nav a.activo {
            color: #fff;
            background: rgba(52, 211, 153, 0.15);
            border-color: rgba(52, 211, 153, 0.2);
        }

        /* ========== MAIN ========== */
        .app-main {
            flex: 1;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
        }

        .page-header h2 small {
            font-weight: 400;
            font-size: 0.9rem;
            color: #64748b;
            display: block;
        }

        .header-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .btn-excel {
            padding: 0.6rem 1.5rem;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-excel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }

        .btn-refresh {
            padding: 0.6rem 1.2rem;
            background: #fff;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-refresh:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* ========== STATS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .stat-card .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            font-weight: 600;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.2rem;
        }

        .stat-card .number .badge-count {
            font-size: 0.8rem;
            font-weight: 400;
        }

        .stat-card.total .number { color: #0f172a; }
        .stat-card.perfiles .number { color: #3b82f6; }
        .stat-card.integridad .number { color: #22c55e; }
        .stat-card.corruptos .number { color: #ef4444; }

        /* ========== LEYENDA ========== */
        .leyenda {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            padding: 0.8rem 1.2rem;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 0.85rem;
        }

        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .leyenda-item .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-verde { background: #22c55e; }
        .dot-rojo { background: #ef4444; }
        .dot-gris { background: #94a3b8; }

        /* ========== TARJETAS DE RESUMEN ========== */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .resumen-card {
            background: #fff;
            border-radius: 14px;
            padding: 1rem 1.2rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .resumen-card:hover {
            border-color: #94a3b8;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        .resumen-card .info {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .resumen-card .info .nombre {
            font-weight: 600;
            color: #0f172a;
            font-size: 0.95rem;
        }

        .resumen-card .info .detalle {
            font-size: 0.8rem;
            color: #64748b;
        }

        .resumen-card .info .detalle span {
            margin-right: 0.8rem;
        }

        .resumen-card .estado {
            flex-shrink: 0;
        }

        .badge {
            padding: 0.25rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .badge-verde {
            background: #dcfce7;
            color: #166534;
        }

        .badge-rojo {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-gris {
            background: #f1f5f9;
            color: #475569;
        }

        /* ========== TABLA ========== */
        .table-wrapper {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 1.5rem;
        }

        .table-scroll {
            overflow-x: auto;
            padding: 0.5rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            min-width: 800px;
        }

        table thead {
            background: #f8fafc;
        }

        table th {
            padding: 0.7rem 0.8rem;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        table td {
            padding: 0.6rem 0.8rem;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            white-space: nowrap;
        }

        table tbody tr:hover {
            background: #f8fafc;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-footer {
            padding: 0.8rem 1.2rem;
            font-size: 0.8rem;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            text-align: right;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .empty-state .icono {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #94a3b8;
        }

        /* ========== FOOTER ========== */
        .app-footer {
            background: #fff;
            border-top: 1px solid #e2e8f0;
            padding: 1.2rem 2rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.8rem;
        }

        .app-footer strong {
            color: #0f172a;
        }

        .app-footer a {
            color: #34d399;
            text-decoration: none;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .app-header {
                padding: 0.8rem 1rem;
            }
            .app-header nav a {
                font-size: 0.75rem;
                padding: 0.3rem 0.7rem;
            }
            .app-main {
                padding: 0 1rem;
                margin: 1rem auto;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .page-header h2 {
                font-size: 1.4rem;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            .cards-grid {
                grid-template-columns: 1fr;
            }
            .leyenda {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .app-header .logo h1 {
                font-size: 1rem;
            }
            .app-header .logo .icon {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .resumen-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .header-actions {
                width: 100%;
            }
            .header-actions .btn-excel,
            .header-actions .btn-refresh {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="app-header">
    <div class="logo">
        <h1>iTECH<span>·</span></h1>
    </div>
    <nav>
        <a href="../index.php">Inicio</a>
        <a href="formulario.php">Colaborador</a>
        <a href="perfil_laboral.php">Perfil</a>
        <a href="reporte.php" class="activo">Reporte</a>
    </nav>
</header>

<!-- ========== MAIN ========== -->
<main class="app-main">

    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <div>
            <h2>
                Reporte General
                <small>Visualización completa de colaboradores y perfiles laborales</small>
            </h2>
        </div>
        <div class="header-actions">
            <a href="../exportar_excel.php" class="btn-excel">
                ⬇ Exportar Excel
            </a>
            <a href="reporte.php" class="btn-refresh">
                Actualizar
            </a>
        </div>
    </div>

    <!-- ===== STATS ===== -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="label">Total Colaboradores</div>
            <div class="number"><?= $totalColaboradores ?></div>
        </div>
        <div class="stat-card perfiles">
            <div class="label">Con Perfil Laboral</div>
            <div class="number"><?= $conPerfil ?></div>
        </div>
        <div class="stat-card integridad">
            <div class="label"> Datos Íntegros</div>
            <div class="number"><?= $integridadOK ?></div>
        </div>
        <div class="stat-card corruptos">
            <div class="label">Datos Corruptos</div>
            <div class="number"><?= $integridadFAIL ?></div>
        </div>
    </div>

    <!-- ===== LEYENDA ===== -->
    <div class="leyenda">
        <span class="leyenda-item">
            <span class="dot dot-verde"></span> Íntegro (datos validados)
        </span>
        <span class="leyenda-item">
            <span class="dot dot-rojo"></span> Corrupto / vulnerado
        </span>
        <span class="leyenda-item">
            <span class="dot dot-gris"></span> Sin perfil laboral
        </span>
        <span style="color:#94a3b8; font-size:0.75rem; margin-left:auto;">
            Firmas digitales OpenSSL
        </span>
    </div>

    <!-- ===== EMPTY STATE ===== -->
    <?php if (empty($datos)): ?>
        <div class="empty-state">
            <div class="icono">📭</div>
            <h3>No hay colaboradores registrados</h3>
            <p>Comienza registrando un nuevo colaborador desde el formulario.</p>
            <br>
            <a href="formulario.php" class="btn-excel" style="display:inline-flex;">+ Registrar Colaborador</a>
        </div>
    <?php else: ?>

    <!-- ===== TARJETAS DE RESUMEN ===== -->
    <div class="cards-grid">
        <?php foreach ($datos as $fila): ?>
            <div class="resumen-card">
                <div class="info">
                    <div class="nombre">
                        <?= htmlspecialchars($fila['nombre']) ?> <?= htmlspecialchars($fila['apellido']) ?>
                    </div>
                    <div class="detalle">
                        <span><?= htmlspecialchars($fila['identidad']) ?></span>
                        <span><?= htmlspecialchars($fila['correo']) ?></span>
                        <?php if (!empty($fila['ocupacion'])): ?>
                            <span><?= htmlspecialchars($fila['ocupacion']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="estado">
                    <?php if (!empty($fila['id_perfil'])): ?>
                        <?php if ($fila['integridad']): ?>
                            <span class="badge badge-verde">✅ Íntegro</span>
                        <?php else: ?>
                            <span class="badge badge-rojo">❌ Corrupto</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-gris">⚪ Sin perfil</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ===== TABLA ===== -->
    <div class="table-wrapper">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Cód.</th>
                        <th>Identidad</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Ruta</th>
                        <th>Ocupación</th>
                        <th>Planilla</th>
                        <th>Salario</th>
                        <th>F. Inicio</th>
                        <th>F. Fin</th>
                        <th>Activo</th>
                        <th>Integridad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $fila): ?>
                        <tr>
                            <td><strong>#<?= $fila['id_colaborador'] ?></strong></td>
                            <td><?= htmlspecialchars($fila['identidad']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['apellido']) ?></td>
                            <td><?= htmlspecialchars($fila['ruta'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($fila['ocupacion'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($fila['planilla'] ?? '-') ?></td>
                            <td><?= $fila['salario'] !== null ? '$' . number_format($fila['salario'], 2) : '-' ?></td>
                            <td><?= htmlspecialchars($fila['fecha_inicio'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($fila['fecha_fin'] ?? '-') ?></td>
                            <td><?= $fila['empleado_activo'] ? '✅ Sí' : '❌ No' ?></td>
                            <td>
                                <?php if (!empty($fila['id_perfil'])): ?>
                                    <span class="badge <?= $fila['integridad'] ? 'badge-verde' : 'badge-rojo' ?>">
                                        <?= $fila['integridad'] ? '✅ Íntegro' : '❌ Corrupto' ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-gris">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer">
            Mostrando <?= count($datos) ?> colaboradores · Última actualización: <?= date('d/m/Y H:i:s') ?>
        </div>
    </div>

    <?php endif; ?>
</main>

<!-- ========== FOOTER ========== -->
<footer class="app-footer">
    <p>&copy; <?= date('Y') ?> <strong>iTECH</strong> · Sistema de Gestión de Colaboradores · 
    <a href="#">contacto@itech.com</a> · 📞 6000-0000</p>
</footer>

</body>
</html>