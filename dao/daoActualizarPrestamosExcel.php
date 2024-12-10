<?php
include_once('connectionCajita.php');
include_once('funcionesGenerales.php');

session_start();

// Lógica principal para recibir los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['prestamos']) && is_array($inputData['prestamos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['prestamos'] as $prestamo) {
            // Validar y asignar valores
            $idSolicitud = isset($prestamo['idSolicitud']) ? trim($prestamo['idSolicitud']) : null;
            $anioConvocatoria = isset($prestamo['anioConvocatoria']) ? trim($prestamo['anioConvocatoria']) : null;
            $idEstatus = isset($prestamo['idEstatus']) ? trim($prestamo['idEstatus']) : null;
            $nominaAval1 = isset($prestamo['nominaAval1']) ? trim($prestamo['nominaAval1']) : null;
            $telAval1 = isset($prestamo['telAval1']) ? trim($prestamo['telAval1']) : null;
            $nominaAval2 = isset($prestamo['nominaAval2']) ? trim($prestamo['nominaAval2']) : null;
            $telAval2 = isset($prestamo['telAval2']) ? trim($prestamo['telAval2']) : null;
            $montoAprobado = isset($prestamo['montoAprobado']) ? trim($prestamo['montoAprobado']) : null;
            $fechaDeposito = isset($prestamo['fechaDeposito']) ? trim($prestamo['fechaDeposito']) : null;
            $montoDeposito = isset($prestamo['montoDeposito']) ? trim($prestamo['montoDeposito']) : null;
            $comentariosAdmin = isset($prestamo['comentariosAdmin']) ? trim($prestamo['comentariosAdmin']) : null;

            // Validar monto aprobado
            $validacionMontoAprobado = validarMonto($montoAprobado);
            if ($validacionMontoAprobado['status'] === 'error') {
                $errores[] = "Monto aprobado inválido para la solicitud ID: $idSolicitud. " . $validacionMontoAprobado['message'];
                $todosExitosos = false;
            } else {
                // Asignar el monto validado
                $montoAprobadoValidado = $validacionMontoAprobado['monto'];
            }

            // Validar monto de depósito
            $validacionMontoDeposito = validarMonto($montoDeposito);
            if ($validacionMontoDeposito['status'] === 'error') {
                $errores[] = "Monto de depósito inválido para la solicitud ID: $idSolicitud. " . $validacionMontoDeposito['message'];
                $todosExitosos = false;
            } else {
                // Asignar el monto validado
                $montoDepositoValidado = $validacionMontoDeposito['monto'];
            }

            // Formatear fechas
            $fechaDepositoFormateada = formatearFecha($fechaDeposito);

            // Validar datos
            if (empty($idSolicitud) || empty($anioConvocatoria) || empty($fechaRespuesta)) {
                $errores[] = "Faltan datos para la solicitud ID: $idSolicitud.";
                $todosExitosos = false;
            } elseif ($fechaDepositoFormateada === false ) {
                $errores[] = "Las fechas son inválidas para la solicitud ID: $idSolicitud.";
                $todosExitosos = false;
            } else {
                // Llamar a la función de actualización con los montos validados y las fechas formateadas
                $respuestaActualizacion = actualizarPresAdminExcel(
                    $idSolicitud,
                    $anioConvocatoria,
                    $idEstatus,
                    $nominaAval1,
                    $telAval1,
                    $nominaAval2,
                    $telAval2,
                    $montoAprobadoValidado,
                    $fechaDepositoFormateada,
                    $montoDepositoValidado,
                    $comentariosAdmin
                );

                if ($respuestaActualizacion['status'] !== 'success') {
                    $errores[] = "Error al actualizar la solicitud ID: $idSolicitud. " . $respuestaActualizacion['message'];
                    $todosExitosos = false;
                }
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los préstamos se actualizaron exitosamente.");
        } else {
            $respuesta = array("status" => 'error', "message" => "Se encontraron errores en las actualizaciones.", "detalles" => $errores);
        }
    } else {
        $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
    }
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

echo json_encode($respuesta);


function actualizarPresAdminExcel($idSolicitud, $anioConvocatoria, $idEstatus, $nominaAval1, $telAval1, $nominaAval2, $telAval2, $montoAprobado, $fechaDeposito, $montoDeposito, $comentarios) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $conex->begin_transaction();

    if ($comentarios === null){
        $comentarios = 'Sin comentarios.';
    }

    try {
        $fechaResp = date("Y-m-d");

        $updateSol = $conex->prepare("UPDATE Prestamo 
                                              SET fechaDeposito = ?, 
                                                  montoDepositado = ?,
                                                  idEstatus = ?,
                                                  comentariosAdmin = ?
                                              WHERE idSolicitud = ?
                                                AND anioConvocatoria = ?");
        $updateSol->bind_param("sssii", $fechaDeposito, $montoDepositado,$comentarios, $idSolicitud, $anio);
        $resultado = $updateSol->execute();

        if (!$resultado) {
            $respuesta = array('status' => 'error', 'message' => 'Error al actualizar la solicitud.');
        } else {
            // Registro en la bitácora
            $nomina = $_SESSION["nomina"];
            $descripcion = "Actualización Prestamo por admin. idSolicitud:".$idSolicitud." Monto depositado: $".$montoDepositado." Fecha Deposito:".$fechaDeposito;

            $resultadoBitacora = actualizarBitacoraCambios( $nomina, $fechaResp, $descripcion, $conex);

            if (!$resultadoBitacora) {
                $respuesta = array('status' => 'error', 'message' => 'Error al registrar en bitácora.');
            } else {
                $conex->commit();
                $respuesta = array("status" => 'success', "message" => "Solicitud $idSolicitud actualizada exitosamente.");
            }
        }
    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }
    return $respuesta;
}

/*
 * UPDATE `Prestamo` SET `nominaSolicitante` = '00001806', `nominaAval1` = '00030993', `telAval1` = '8899558622', `nominaAval2` = '000303133',
 * `telAval2` = '4455887771', `comentariosAdmin` = 'Aún no hay comentarios.fas' WHERE `Prestamo`.`idSolicitud` = 2
 * AND `Prestamo`.`anioConvocatoria` = 2022;
 */

function validarMonto($montoAhorro) {
    // Elimina espacios en blanco al inicio y al final
    $valor = trim($montoAhorro);

    // Elimina el signo de pesos si está al principio
    if (strpos($valor, '$') === 0) {
        $valor = substr($valor, 1); // Elimina el primer carácter '$'
    }

    // Convierte el valor a un número
    $numero = floatval($valor);

    // Verifica si el valor es un número válido
    if (!is_numeric($valor) || $numero <= 0) {
        // Retorna un error si no es válido
        return array(
            'status' => 'error',
            'message' => 'El monto ingresado no es válido.'
        );
    } else {
        // Retorna éxito con el valor numérico
        return array(
            'status' => 'success',
            'message' => 'El monto ingresado es válido.',
            'monto' => $numero
        );
    }
}
?>