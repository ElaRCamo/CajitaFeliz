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

            // Validar datos básicos
            if (empty($idSolicitud) || empty($anioConvocatoria)) {
                $errores[] = "Faltan datos obligatorios para la solicitud ID: $idSolicitud.";
                $todosExitosos = false;
                continue;
            }

            // Validar montoDeposito y fechaDeposito
            if (!empty($montoDeposito) xor !empty($fechaDeposito)) {
                $errores[] = "Monto y fecha de depósito deben proporcionarse juntos para la solicitud Folio: $idSolicitud.";
                $todosExitosos = false;
                continue;
            }

            // Formatear la fecha de depósito si está presente
            if (!empty($fechaDeposito)) {
                $fechaDepositoFormateada = formatearFecha($fechaDeposito);
                if (!$fechaDepositoFormateada) {
                    $errores[] = "La fecha de depósito es inválida para la solicitud Folio: $idSolicitud.";
                    $todosExitosos = false;
                    continue;
                }
                $fechaDeposito = $fechaDepositoFormateada;
            }

            // Asignar valores condicionales
            $idEstatus = (!empty($montoDeposito) && !empty($fechaDeposito)) ? 4 : null;
            if (!empty($montoDeposito) && (empty($montoAprobado) || $montoAprobado == 0)) {
                $montoAprobado = $montoDeposito;
            }
            if (empty($comentariosAdmin)) {
                $comentariosAdmin = 'Sin comentarios.';
            }

            // Actualizar solicitud
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

        // Respuesta final
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

        $query = "UPDATE Prestamo 
                  SET idEstatus = ?, montoAprobado = ?, montoDepositado = ?, fechaDeposito = ?, 
                      comentariosAdmin = ?, fechaRespuesta = ? 
                  WHERE idSolicitud = ? AND anioConvocatoria = ?";
        $stmt = $conex->prepare($query);
        $stmt->bind_param(
            "isssssii",
            $idEstatus,
            $montoAprobado,
            $montoDeposito,
            $fechaDeposito,
            $comentarios,
            $fechaRespuesta,
            $idSolicitud,
            $anioConvocatoria
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la solicitud ID: $idSolicitud.");
        }

        // Registro en bitácora
        $nomina = $_SESSION["nomina"];
        $descripcion = "Actualización de préstamo ID: $idSolicitud. Monto depositado: $montoDeposito, Fecha depósito: $fechaDeposito.";
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