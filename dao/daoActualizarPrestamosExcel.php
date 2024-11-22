<?php
include_once('connectionCajita.php');
include_once('funcionesGenerales.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['prestamos']) && is_array($inputData['prestamos'])) {
        $todosExitosos = true;
        $errores = [];

        foreach ($inputData['prestamos'] as $prestamo) {
            // Validar y asignar valores
            $idSolicitud = isset($prestamo['idSolicitud']) ? trim($prestamo['idSolicitud']) : null;
            $montoDepositado = isset($prestamo['montoDepositado']) ? trim($prestamo['montoDepositado']) : null;
            $fechaDeposito = isset($prestamo['fechaDeposito']) ? trim($prestamo['fechaDeposito']) : null;
            $fechaFormateada = formatearFecha($fechaDeposito);
            $comentarios = isset($prestamo['comentarios']) ? trim($prestamo['comentarios']) : null;

            // Validar datos
            if (empty($idSolicitud) || empty($montoDepositado) || empty($fechaDeposito)) {
                $errores[] = "Faltan datos para la solicitud ID: $idSolicitud.";
                $todosExitosos = false;
            } elseif ($fechaFormateada === false) {
                $errores[] = "La fecha es inválida para la solicitud ID: $idSolicitud.";
                $todosExitosos = false;
            } else {
                // Llamar a la función de actualización con la fecha en el formato correcto
                $respuestaActualizacion = actualizarPresAdminExcel($idSolicitud, $montoDepositado, $fechaFormateada, $comentarios);
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

function actualizarPresAdminExcel($idSolicitud, $montoDepositado, $fechaDeposito, $comentarios) {
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
                                          comentariosAdmin = ?
                                      WHERE idSolicitud = ?");
        $updateSol->bind_param("sssi", $fechaDeposito, $montoDepositado,$comentarios, $idSolicitud);
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
?>