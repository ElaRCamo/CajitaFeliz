<?php
include_once('connectionCajita.php');
include_once('funcionesGenerales.php');

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['prestamos']) && is_array($inputData['prestamos'])) {
        $todosExitosos = true;
        $errores = [];
        $fechaActual = date("Y-m-d");

        foreach ($inputData['prestamos'] as $prestamo) {
            $idSolicitud = isset($prestamo['idSolicitud']) ? $prestamo['idSolicitud'] : null;
            $anioConvocatoria = isset($prestamo['anioConvocatoria']) ? $prestamo['anioConvocatoria'] : null;
            $montoDeposito = isset($prestamo['montoDeposito']) ? $prestamo['montoDeposito'] : null;
            $fechaDeposito = isset($prestamo['fechaDeposito']) ? $prestamo['fechaDeposito'] : null;
            $montoAprobado = isset($prestamo['montoAprobado']) ? $prestamo['montoAprobado'] : null;
            $comentariosAdmin = trim(isset($prestamo['comentariosAdmin']) ? $prestamo['comentariosAdmin'] : '');

            if (empty($idSolicitud) || empty($anioConvocatoria)) {
                $errores[] = "Faltan datos obligatorios para la solicitud ID: $idSolicitud.";
                $todosExitosos = false;
                continue;
            }

            // Validar que la fecha no sea '0000-00-00' y que el monto sea válido
            if (($fechaDeposito == '0000-00-00' && !empty($montoDeposito)) ||
                ($fechaDeposito != '0000-00-00' && (empty($montoDeposito) || !is_numeric($montoDeposito) || $montoDeposito <= 0))) {
                $errores[] = "Monto y fecha de depósito deben estar correctamente proporcionados para la solicitud Folio: $idSolicitud.";
                $todosExitosos = false;
                continue;
            }


            if (!empty($fechaDeposito)) {
                $fechaDepositoFormateada = formatearFecha($fechaDeposito);
                if (!$fechaDepositoFormateada) {
                    $errores[] = "La fecha de depósito es inválida para la solicitud Folio: $idSolicitud.";
                    $todosExitosos = false;
                    continue;
                }
                $fechaDeposito = $fechaDepositoFormateada;
            }

            $idEstatus = (!empty($montoDeposito) && !empty($fechaDeposito)) ? 4 : null;
            if (!empty($montoDeposito) && (empty($montoAprobado) || $montoAprobado == 0)) {
                $montoAprobado = $montoDeposito;
            }
            if (empty($comentariosAdmin)) {
                $comentariosAdmin = 'Sin comentarios.';
            }

            $resultado = actualizarSolicitud(
                $idSolicitud,
                $anioConvocatoria,
                $idEstatus,
                $montoAprobado,
                $montoDeposito,
                $fechaDeposito,
                $comentariosAdmin,
                $fechaActual
            );

            if ($resultado['status'] !== 'success') {
                $errores[] = $resultado['message'];
                $todosExitosos = false;
            }
        }

        $respuesta = $todosExitosos
            ? ["status" => 'success', "message" => "Todas las solicitudes fueron actualizadas exitosamente."]
            : ["status" => 'error', "message" => "Errores al actualizar algunas solicitudes.", "detalles" => $errores];
    } else {
        $respuesta = ["status" => 'error', "message" => "Datos no válidos."];
    }
} else {
    $respuesta = ["status" => 'error', "message" => "Método no permitido, se esperaba POST."];
}

echo json_encode($respuesta);

function actualizarSolicitud($idSolicitud, $anioConvocatoria, $idEstatus, $montoAprobado, $montoDeposito, $fechaDeposito, $comentarios, $fechaRespuesta) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    try {
        $conex->begin_transaction();

        $campos = [
            "idEstatus = ?" => $idEstatus,
            "montoAprobado = ?" => $montoAprobado,
            "comentariosAdmin = ?" => $comentarios,
            "fechaRespuesta = ?" => $fechaRespuesta
        ];

        if (!empty($montoDeposito) && !empty($fechaDeposito)) {
            $campos["montoDepositado = ?"] = $montoDeposito;
            $campos["fechaDeposito = ?"] = $fechaDeposito;
        }

        $query = "UPDATE Prestamo SET " . implode(", ", array_keys($campos)) . " WHERE idSolicitud = ? AND anioConvocatoria = ?";
        $stmt = $conex->prepare($query);

        $parametros = array_values($campos);
        $parametros[] = $idSolicitud;
        $parametros[] = $anioConvocatoria;

        $stmt->bind_param(str_repeat("s", count($parametros)), ...$parametros);

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la solicitud ID: $idSolicitud.");
        }

        $nomina = $_SESSION["nomina"];
        $descripcion = "Actualización de préstamo ID: $idSolicitud.";
        if (!actualizarBitacoraCambios($nomina, $fechaRespuesta, $descripcion, $conex)) {
            throw new Exception("Error al registrar en bitácora para la solicitud ID: $idSolicitud.");
        }

        $conex->commit();
        return ["status" => 'success', "message" => "Solicitud ID: $idSolicitud actualizada exitosamente."];
    } catch (Exception $e) {
        $conex->rollback();
        return ["status" => 'error', "message" => $e->getMessage()];
    } finally {
        $conex->close();
    }
}
?>