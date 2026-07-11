<?php
require_once __DIR__ . '/../models/Colaborador.php';
require_once __DIR__ . '/../models/PerfilLaboral.php';
require_once __DIR__ . '/../models/Catalogo.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Sanitizer.php';

class ColaboradorController {

    public static function procesarRegistro(array $post): array {
        $errores = [];

        // --- Sanitización ---
        $identidad    = Sanitizer::limpiarTexto($post['identidad'] ?? '');
        $nombre       = Sanitizer::aTitulo(Sanitizer::limpiarTexto($post['nombre'] ?? ''));
        $apellido     = Sanitizer::aTitulo(Sanitizer::limpiarTexto($post['apellido'] ?? ''));
        $edad         = Sanitizer::limpiarNumero($post['edad'] ?? '');
        $sexo         = $post['sexo'] ?? '';
        $nacionalidad = Sanitizer::aTitulo(Sanitizer::limpiarTexto($post['nacionalidad'] ?? ''));
        $correo       = Sanitizer::limpiarCorreo($post['correo'] ?? '');
        $celular      = Sanitizer::limpiarCelular($post['celular'] ?? '');
        $idTipoSangre = $post['id_tipo_sangre'] ?? '';
        $idRuta       = $post['id_ruta'] ?? '';
        $idEstadoCivil = $post['id_estado_civil'] ?? '';

        // --- Validación ---
        if (!Validator::validarIdentidad($identidad)) $errores[] = "La identidad no es válida.";
        if (!Validator::validarNombre($nombre)) $errores[] = "El nombre no es válido.";
        if (!Validator::validarNombre($apellido)) $errores[] = "El apellido no es válido.";
        if (!Validator::validarEdad($edad)) $errores[] = "La edad debe estar entre 18 y 99 años.";
        if (!Validator::validarSexoId($sexo)) $errores[] = "Seleccione un sexo válido.";
        if (Validator::esVacio($nacionalidad)) $errores[] = "La nacionalidad es requerida.";
        if (!Validator::validarRuta($idRuta)) $errores[] = "Seleccione la ruta del colaborador.";
        if (!Validator::validarTipoSangre($idTipoSangre)) $errores[] = "Seleccione el tipo de sangre.";
        if (!Validator::validarEstadoCivil($idEstadoCivil)) $errores[] = "Seleccione el estado civil.";
        if (!Validator::validarCorreo($correo)) $errores[] = "El correo no es válido.";
        if (!Validator::validarCelular($celular)) $errores[] = "El celular debe tener el formato 6XXX-XXXX.";

        if (empty($errores) && Colaborador::existeIdentidad($identidad)) {
            $errores[] = "Ya existe un colaborador con esa identidad.";
        }
        if (empty($errores) && Colaborador::existeCorreo($correo)) {
            $errores[] = "Ya existe un colaborador con ese correo.";
        }

        if (!empty($errores)) {
            return ['exito' => false, 'errores' => $errores];
        }

        $id = Colaborador::crear([
            'identidad'       => $identidad,
            'nombre'          => $nombre,
            'apellido'        => $apellido,
            'edad'            => $edad,
            'id_tipo_sangre'  => $idTipoSangre,
            'sexo'            => $sexo,
            'nacionalidad'    => $nacionalidad,
            'id_ruta'         => $idRuta,
            'correo'          => $correo,
            'celular'         => $celular,
            'id_estado_civil' => $idEstadoCivil,
        ]);

        return ['exito' => true, 'id_colaborador' => $id];
    }

    public static function procesarPerfilLaboral(array $post): array {
        $errores = [];

        $codigoEmpleado = $post['codigo_empleado'] ?? '';
        $idOcupacion    = $post['id_ocupacion'] ?? '';
        $idTipoEmpleado = $post['id_tipo_empleado'] ?? '';
        $salario        = Sanitizer::limpiarNumero($post['salario'] ?? '');
        $fechaInicio    = Sanitizer::limpiarTexto($post['fecha_inicio'] ?? '');
        $fechaFin       = Sanitizer::limpiarTexto($post['fecha_fin'] ?? '');
        $idMotivoTerminacion = $post['id_motivo_terminacion'] ?? '';
        $motivoBaja     = Sanitizer::limpiarTexto($post['motivo_baja'] ?? '');

        // --- Validaciones ---
        if (!Validator::validarCodigoEmpleado($codigoEmpleado)) {
            $errores[] = "Seleccione un colaborador válido.";
        }
        if (!Validator::validarOcupacion($idOcupacion)) {
            $errores[] = "Seleccione una ocupación válida.";
        }
        if (!Validator::validarTipoEmpleado($idTipoEmpleado)) {
            $errores[] = "Seleccione un tipo de empleado válido.";
        }
        if (!Validator::validarSalario($salario)) {
            $errores[] = "El salario debe ser numérico y mayor a 0.";
        }
        if (!Validator::validarFecha($fechaInicio)) {
            $errores[] = "La fecha de inicio no es válida (use AAAA-MM-DD).";
        }
        
        // Validación de fecha fin
        if (!empty($fechaFin)) {
            if (!Validator::validarFecha($fechaFin)) {
                $errores[] = "La fecha de fin no es válida (use AAAA-MM-DD).";
            } elseif ($fechaFin < $fechaInicio) {
                $errores[] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
            }
            
            // Si hay fecha fin, debe haber motivo de terminación
            if (empty($idMotivoTerminacion)) {
                $errores[] = "Debe indicar el motivo de la baja.";
            }
            if (!Validator::validarMotivoTerminacion($idMotivoTerminacion)) {
                $errores[] = "Seleccione un motivo de terminación válido.";
            }
            if (empty($motivoBaja)) {
                $errores[] = "Debe especificar detalles del motivo de baja.";
            }
        }

        if (!empty($errores)) {
            return ['exito' => false, 'errores' => $errores];
        }

        $id = PerfilLaboral::crear([
            'codigo_empleado'        => $codigoEmpleado,
            'id_ocupacion'           => $idOcupacion,
            'id_tipo_empleado'       => $idTipoEmpleado,
            'salario'                => $salario,
            'fecha_inicio'           => $fechaInicio,
            'fecha_fin'              => $fechaFin ?: null,
            'id_motivo_terminacion'  => $fechaFin ? $idMotivoTerminacion : null,
            'motivo_baja'            => $fechaFin ? $motivoBaja : null,
        ]);

        return ['exito' => true, 'id_perfil' => $id];
    }
}