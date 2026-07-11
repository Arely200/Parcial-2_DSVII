<?php
require_once __DIR__ . '/../models/Catalogo.php';
require_once __DIR__ . '/../controllers/ColaboradorController.php';

$resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = ColaboradorController::procesarRegistro($_POST);
}

$tiposSangre = Catalogo::tiposSangre();
$rutas = Catalogo::rutas();
$sexos = Catalogo::sexos();
$estadosCiviles = Catalogo::estadosCiviles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Colaborador · iTECH</title>
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
            max-width: 960px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            width: 100%;
        }

        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .page-title h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
        }

        .page-title h2 small {
            font-weight: 400;
            font-size: 0.9rem;
            color: #64748b;
            display: block;
        }

        .page-title .fecha {
            background: #fff;
            padding: 0.4rem 1.2rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #64748b;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        /* ========== ALERTAS ========== */
        .alerta {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .alerta-error {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        .alerta-error ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .alerta-exito {
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            color: #166534;
        }

        .alerta .icono {
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* ========== FORMULARIO ========== */
        .form-card {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem 1.8rem;
        }

        .form-grid .full-width {
            grid-column: 1 / -1;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .campo label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .campo label .required {
            color: #ef4444;
        }

        .campo input,
        .campo select {
            padding: 0.6rem 0.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            background: #f8fafc;
            transition: all 0.2s ease;
            font-family: inherit;
            color: #0f172a;
        }

        .campo input:focus,
        .campo select:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
            background: #fff;
        }

        .campo input::placeholder {
            color: #94a3b8;
            font-size: 0.85rem;
        }

        /* ========== FIELDSET ========== */
        .fieldset-contacto {
            grid-column: 1 / -1;
            border: 1.5px dashed #e2e8f0;
            border-radius: 14px;
            padding: 1.5rem 1.5rem 0.8rem 1.5rem;
            margin-top: 0.2rem;
        }

        .fieldset-contacto legend {
            font-weight: 600;
            color: #334155;
            padding: 0 0.5rem;
            font-size: 0.85rem;
        }

        .fieldset-contacto .form-grid {
            margin-top: 0.5rem;
        }

        /* ========== BOTONES ========== */
        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 0.7rem 2.5rem;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.2);
        }

        .btn-secondary {
            padding: 0.7rem 2rem;
            background: transparent;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
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
            .form-grid {
                grid-template-columns: 1fr;
            }
            .page-title {
                flex-direction: column;
                align-items: flex-start;
            }
            .page-title h2 {
                font-size: 1.4rem;
            }
            .form-card {
                padding: 1.2rem;
            }
            .fieldset-contacto {
                padding: 1rem 1rem 0.2rem 1rem;
            }
            .form-actions {
                flex-direction: column;
            }
            .btn-primary,
            .btn-secondary {
                justify-content: center;
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
        <a href="formulario.php" class="activo">Colaborador</a>
        <a href="perfil_laboral.php">Perfil</a>
        <a href="reporte.php">Reporte</a>
    </nav>
</header>

<!-- ========== MAIN ========== -->
<main class="app-main">
    <div class="page-title">
        <div>
            <h2>
                 Nuevo Colaborador
                <small>Ingresa los datos personales del colaborador</small>
            </h2>
        </div>
        <span class="fecha">📅 <?= date('d/m/Y') ?></span>
    </div>

    <!-- ===== ALERTAS ===== -->
    <?php if ($resultado && !$resultado['exito']): ?>
        <div class="alerta alerta-error">
            <span class="icono">⚠️</span>
            <div>
                <strong>Por favor, corrige los siguientes errores:</strong>
                <ul>
                    <?php foreach ($resultado['errores'] as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php elseif ($resultado && $resultado['exito']): ?>
        <div class="alerta alerta-exito">
            <span class="icono">✅</span>
            <div>
                <strong>¡Colaborador registrado con éxito!</strong><br>
                Código de empleado asignado: <strong>#<?= $resultado['id_colaborador'] ?></strong>
                — usa este código para crear su perfil laboral.
            </div>
        </div>
    <?php endif; ?>

    <!-- ===== FORMULARIO ===== -->
    <div class="form-card">
        <form method="POST" action="formulario.php">
            <div class="form-grid">

                <!-- Identidad -->
                <div class="campo">
                    <label>Identidad <span class="required">*</span></label>
                    <input type="text" name="identidad" placeholder="8-888-8888" required>
                </div>

                <!-- Nombre -->
                <div class="campo">
                    <label>Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" placeholder="Ej: Juan" required>
                </div>

                <!-- Apellido -->
                <div class="campo">
                    <label>Apellido <span class="required">*</span></label>
                    <input type="text" name="apellido" placeholder="Ej: Pérez" required>
                </div>

                <!-- Edad -->
                <div class="campo">
                    <label>Edad <span class="required">*</span></label>
                    <input type="number" name="edad" min="18" max="99" placeholder="18-99" required>
                </div>

                <!-- Tipo de Sangre -->
                <div class="campo">
                    <label>Tipo de Sangre <span class="required">*</span></label>
                    <select name="id_tipo_sangre" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($tiposSangre as $ts): ?>
                            <option value="<?= $ts['id'] ?>"><?= htmlspecialchars($ts['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sexo -->
                <div class="campo">
                    <label>Sexo <span class="required">*</span></label>
                    <select name="sexo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($sexos as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Estado Civil -->
                <div class="campo">
                    <label>Estado Civil <span class="required">*</span></label>
                    <select name="id_estado_civil" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($estadosCiviles as $ec): ?>
                            <option value="<?= $ec['id'] ?>"><?= htmlspecialchars($ec['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nacionalidad -->
                <div class="campo">
                    <label>Nacionalidad <span class="required">*</span></label>
                    <input type="text" name="nacionalidad" placeholder="Ej: Panameño" required>
                </div>

                <!-- Ruta -->
                <div class="campo">
                    <label>Ruta del Colaborador <span class="required">*</span></label>
                    <select name="id_ruta" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($rutas as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ===== CONTACTO ===== -->
                <fieldset class="fieldset-contacto">
                    <legend> Información de Contacto</legend>
                    <div class="form-grid">
                        <div class="campo">
                            <label>Correo Electrónico <span class="required">*</span></label>
                            <input type="email" name="correo" placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="campo">
                            <label>Celular <span class="required">*</span></label>
                            <input type="text" name="celular" placeholder="6XXX-XXXX" pattern="6[0-9]{3}-[0-9]{4}" required>
                            <small style="color:#94a3b8; font-size:0.7rem;">Formato: 6XXX-XXXX</small>
                        </div>
                    </div>
                </fieldset>

                <!-- ===== BOTONES ===== -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        ✚ Guardar Colaborador
                    </button>
                    <a href="../index.php" class="btn-secondary">
                        ← Volver al inicio
                    </a>
                </div>

            </div>
        </form>
    </div>
</main>

<!-- ========== FOOTER ========== -->
<footer class="app-footer">
    <p>&copy; <?= date('Y') ?> <strong>iTECH</strong> · Sistema de Gestión de Colaboradores · 
    <a href="#">contacto@itech.com</a> · 📞 6000-0000</p>
</footer>

</body>
</html>