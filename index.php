<?php
// index.php - Página de inicio con la misma temática que el resto del sistema
require_once __DIR__ . '/config/Conexion.php';

// Obtener estadísticas de la base de datos
try {
    $con = Conexion::obtenerInstancia();
    $stats = $con->consultar("
        SELECT 
            (SELECT COUNT(*) FROM colaboradores) as total_colaboradores,
            (SELECT COUNT(*) FROM perfiles_laborales WHERE cargo_activo = 1) as cargos_activos,
            (SELECT COUNT(*) FROM perfiles_laborales WHERE firma_digital IS NOT NULL) as perfiles_firmados
    ");
    $totalColab = $stats[0]['total_colaboradores'] ?? 0;
    $cargosActivos = $stats[0]['cargos_activos'] ?? 0;
    $perfilesFirmados = $stats[0]['perfiles_firmados'] ?? 0;
} catch (Exception $e) {
    $totalColab = 0;
    $cargosActivos = 0;
    $perfilesFirmados = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iTECH · Contrataciones</title>
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
            border-bottom: 3px solid #38bdf8;
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
            background: linear-gradient(135deg, #38bdf8, #818cf8);
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
            color: #38bdf8;
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
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.2);
        }

        /* ========== MAIN ========== */
        .app-main {
            flex: 1;
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        /* ========== HERO ========== */
        .hero {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
        }

        .hero h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
        }

        .hero h2 span {
            background: linear-gradient(135deg, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            color: #64748b;
            margin-top: 0.5rem;
            font-size: 1.1rem;
        }

        .hero .badge {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.3rem 1.2rem;
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid rgba(56, 189, 248, 0.2);
            font-weight: 500;
        }

        /* ========== STATS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
            margin: 2rem 0 3rem 0;
        }

        .stat-item {
            background: #fff;
            padding: 1.5rem;
            border-radius: 14px;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .stat-item .num {
            font-size: 2.2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-item .num span {
            color: #38bdf8;
        }

        .stat-item .label {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-top: 0.2rem;
            font-weight: 500;
        }

        /* ========== CARDS ========== */
        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            text-decoration: none;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            border-color: #38bdf8;
            box-shadow: 0 12px 30px rgba(56, 189, 248, 0.12);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card .icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .card h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .card p {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .card .btn {
            display: inline-block;
            margin-top: 1.2rem;
            padding: 0.5rem 1.5rem;
            background: #0f172a;
            color: #fff;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .card:hover .btn {
            background: linear-gradient(135deg, #38bdf8, #818cf8);
            transform: scale(1.02);
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
            color: #38bdf8;
            text-decoration: none;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
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
            .hero h2 {
                font-size: 1.8rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .cards {
                grid-template-columns: 1fr;
            }
            .stat-item .num {
                font-size: 1.8rem;
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
            .hero h2 {
                font-size: 1.5rem;
            }
            .card {
                padding: 1.5rem;
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
        <a href="index.php" class="activo">Inicio</a>
        <a href="views/formulario.php">Colaborador</a>
        <a href="views/perfil_laboral.php">Perfil</a>
        <a href="views/reporte.php">Reporte</a>
    </nav>
</header>

<!-- ========== MAIN ========== -->
<main class="app-main">

    <!-- ===== HERO ===== -->
    <div class="hero">
        <h2>Gestión de <span>Colaboradores</span></h2>
        <p>Registro, perfiles laborales y reportes con firma digital OpenSSL</p>
        <span class="badge"> Firmas Digitales · OpenSSL</span>
    </div>

    <!-- ===== STATS ===== -->
    <div class="stats-grid">
        <div class="stat-item">
            <div class="num"><span><?= $totalColab ?></span></div>
            <div class="label">Colaboradores</div>
        </div>
        <div class="stat-item">
            <div class="num"><span><?= $cargosActivos ?></span></div>
            <div class="label">Cargos Activos</div>
        </div>
        <div class="stat-item">
            <div class="num"><span><?= $perfilesFirmados ?></span></div>
            <div class="label">Perfiles Firmados</div>
        </div>
    </div>

    <!-- ===== CARDS ===== -->
    <div class="cards">
        <a href="views/formulario.php" class="card">
            <span class="icon"></span>
            <h3>Nuevo Colaborador</h3>
            <p>Registra un nuevo colaborador con todos sus datos personales y de contacto</p>
            <span class="btn">Registrar</span>
        </a>
        <a href="views/perfil_laboral.php" class="card">
            <span class="icon"></span>
            <h3>Perfil Laboral</h3>
            <p>Asigna cargo, salario y tipo de empleado con firma digital integrada</p>
            <span class="btn">Crear</span>
        </a>
        <a href="views/reporte.php" class="card">
            <span class="icon"></span>
            <h3>Reporte General</h3>
            <p>Visualiza y exporta el reporte completo con validación de integridad</p>
            <span class="btn">Ver</span>
        </a>
    </div>

</main>

<!-- ========== FOOTER ========== -->
<footer class="app-footer">
    <p>&copy; <?= date('Y') ?> <strong>iTECH</strong> · Sistema de Gestión de Colaboradores · 
    <a href="#">contacto@itech.com</a> · 📞 6000-0000</p>
</footer>

</body>
</html>