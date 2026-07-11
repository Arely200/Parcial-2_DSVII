<?php
require_once __DIR__ . '/../models/Catalogo.php';
require_once __DIR__ . '/../models/Colaborador.php';
require_once __DIR__ . '/../controllers/ColaboradorController.php';

$resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = ColaboradorController::procesarPerfilLaboral($_POST);
}

$colaboradores = Colaborador::listarTodos();
$ocupaciones = Catalogo::ocupaciones();
$tiposEmpleado = Catalogo::tiposEmpleado();
$motivosTerminacion = Catalogo::motivosTerminacion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Laboral · iTECH</title>
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
            border-bottom: 3px solid #a78bfa;
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
            background: linear-gradient(135deg, #a78bfa, #818cf8);
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
            color: #a78bfa;
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
            background: rgba(167, 139, 250, 0.15);
            border-color: rgba(167, 139, 250, 0.2);
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

        /* ========== NOTA ========== */
        .nota-info {
            background: #f5f3ff;
            border-left: 4px solid #a78bfa;
            padding: 0.8rem 1.2rem;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #4c1d95;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .nota-info .icono {
            font-size: 1.2rem;
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
            border-color: #a78bfa;
            box-shadow: 0 0 0 4px rgba(167, 139, 250, 0.1);
            background: #fff;
        }

        .campo input::placeholder {
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .campo select option {
            padding: 0.3rem;
        }

        /* ========== CAMPO MOTIVO (oculto inicialmente) ========== */
        #campo_motivo {
            grid-column: 1 / -1;
            display: none;
            flex-direction: column;
            gap: 0.8rem;
            background: #fafafa;
            padding: 1.2rem;
            border-radius: 12px;
            border: 1.5px dashed #e2e8f0;
            margin-top: 0.2rem;
        }

        #campo_motivo.visible {
            display: flex;
        }

        #campo_motivo .motivo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        #campo_motivo label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        #campo_motivo select,
        #campo_motivo input {
            padding: 0.6rem 0.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            background: #fff;
            transition: all 0.2s ease;
            font-family: inherit;
            color: #0f172a;
        }

        #campo_motivo select:focus,
        #campo_motivo input:focus {
            outline: none;
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
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
            color: #a78bfa;
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
            .form-actions {
                flex-direction: column;
            }
            .btn-primary,
            .btn-secondary {
                justify-content: center;
            }
            #campo_motivo .motivo-grid {
                grid-template-columns: 1fr;
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
        <h1>iTECH<span></span></h1>
    </div>
    <nav>
        <a href="../index.php">Inicio</a>
        <a href="formulario.php">Colaborador</a>
        <a href="perfil_laboral.php" class="activo">Perfil</a>
        <a href="reporte.php">Reporte</a>
    </nav>
</header>

<!-- ========== MAIN ========== -->
<main class="app-main">
    <div class="page-title">
        <div>
            <h2>
                Perfil Laboral
                <small>Asigna o actualiza el puesto de un colaborador</small>
            </h2>
        </div>
        <span class="fecha">📅 <?= date('d/m/Y') ?></span>
    </div>

    <!-- ===== NOTA ===== -->
    <div class="nota-info">

        <div>
            <strong>Promoción automática:</strong>
            Si el colaborador ya tiene un cargo activo, este se desactivará
            automáticamente al guardar el nuevo perfil.
        </div>
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
                <strong>¡Perfil laboral registrado con éxito!</strong><br>
                El perfil ha sido firmado digitalmente con OpenSSL.
            </div>
        </div>
    <?php endif; ?>

    <!-- ===== SIN COLABORADORES ===== -->
    <?php if (empty($colaboradores)): ?>
        <div class="alerta alerta-error">
            <span class="icono">👤</span>
            <div>
                <strong>No hay colaboradores registrados.</strong><br>
                <a href="formulario.php" style="color:#991b1b; font-weight:600;">Registra uno primero</a> antes de crear un perfil laboral.
            </div>
        </div>
    <?php else: ?>

    <!-- ===== FORMULARIO ===== -->
    <div class="form-card">
        <form method="POST" action="perfil_laboral.php">
            <div class="form-grid">

                <!-- Colaborador -->
                <div class="campo full-width">
                    <label>Colaborador <span class="required">*</span></label>
                    <select name="codigo_empleado" required>
                        <option value="">Seleccione un colaborador...</option>
                        <?php foreach ($colaboradores as $c): ?>
                            <option value="<?= $c['id_colaborador'] ?>">
                                #<?= $c['id_colaborador'] ?> · <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Puesto / Ocupación -->
                <div class="campo">
                    <label>Puesto <span class="required">*</span></label>
                    <select name="id_ocupacion" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($ocupaciones as $o): ?>
                            <option value="<?= $o['id_ocupacion'] ?>"><?= htmlspecialchars($o['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tipo de Empleado -->
                <div class="campo">
                    <label>Tipo de Empleado <span class="required">*</span></label>
                    <select name="id_tipo_empleado" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($tiposEmpleado as $te): ?>
                            <option value="<?= $te['id'] ?>"><?= htmlspecialchars($te['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Salario -->
                <div class="campo">
                    <label>Salario <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="salario" placeholder="0.00" required>
                </div>

                <!-- Fecha Inicio -->
                <div class="campo">
                    <label>Fecha de Inicio <span class="required">*</span></label>
                    <input type="date" name="fecha_inicio" required>
                </div>

                <!-- Fecha Fin -->
                <div class="campo">
                    <label>Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin">
                    <small style="color:#94a3b8; font-size:0.7rem;">Solo si hay baja o terminación</small>
                </div>

                <!-- ===== MOTIVO DE BAJA (oculto) ===== -->
                <div id="campo_motivo">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.3rem;">
                        <span style="font-size:1.2rem;">📋</span>
                        <span style="font-weight:600; color:#334155; text-transform:uppercase; font-size:0.8rem; letter-spacing:0.3px;">Motivo de Terminación</span>
                    </div>
                    <div class="motivo-grid">
                        <div>
                            <label for="id_motivo_terminacion">Motivo</label>
                            <select name="id_motivo_terminacion" id="id_motivo_terminacion">
                                <option value="">Seleccione...</option>
                                <?php foreach ($motivosTerminacion as $mt): ?>
                                    <option value="<?= $mt['id'] ?>"><?= htmlspecialchars($mt['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="motivo_baja">Detalles adicionales</label>
                            <input type="text" name="motivo_baja" id="motivo_baja" placeholder="Especifique detalles...">
                        </div>
                    </div>
                </div>

                <!-- ===== BOTONES ===== -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        ✚ Guardar Perfil
                    </button>
                    <a href="../index.php" class="btn-secondary">
                        ← Volver al inicio
                    </a>
                </div>

            </div>
        </form>
    </div>
    <?php endif; ?>
</main>

<!-- ========== FOOTER ========== -->
<footer class="app-footer">
    <p>&copy; <?= date('Y') ?> <strong>iTECH</strong> · Sistema de Gestión de Colaboradores · 
    <a href="#">contacto@itech.com</a> · 📞 6000-0000</p>
</footer>

<!-- ========== JAVASCRIPT ========== -->
<script>
document.getElementById('fecha_fin')?.addEventListener('change', function () {
    const campoMotivo = document.getElementById('campo_motivo');
    const motivoSelect = document.getElementById('id_motivo_terminacion');
    const motivoText = document.getElementById('motivo_baja');
    
    if (this.value) {
        campoMotivo.classList.add('visible');
        motivoSelect.required = true;
        motivoText.required = true;
    } else {
        campoMotivo.classList.remove('visible');
        motivoSelect.required = false;
        motivoText.required = false;
        motivoSelect.value = '';
        motivoText.value = '';
    }
});
</script>

</body>
</html>