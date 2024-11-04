<?php
include_once('connectionCajita.php');
include_once('funcionesGenerales.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['prestamos']) && is_array($inputData['prestamos'])) {
        $respuesta = array();

        foreach ($inputData['prestamos'] as $prestamo) {
            // Validar y asignar valores
            $idSolicitud = isset($prestamo['idSolicitud']) ? trim($prestamo['idSolicitud']) : null;
            $montoDepositado = isset($prestamo['montoDepositado']) ? trim($prestamo['montoDepositado']) : null;
            $fechaDeposito = isset($prestamo['fechaDeposito']) ? trim($prestamo['fechaDeposito']) : null;
            $fechaFormateada = formatearFecha($fechaDeposito);
            if ($fechaFormateada === false) {
                $respuesta = array("status" => 'error', "message" => "La fecha es inválida. Asegúrese de usar un formato correcto.");
            } else {
                // Llamar a la función de actualización con la fecha en el formato correcto
                $resultado = actualizarPresAdminExcel($idSolicitud, $montoDepositado, $fechaFormateada);
                $respuesta[] = $resultado;
            }

            // Validar datos
            if (empty($idSolicitud) || empty($montoDepositado) || empty($fechaDeposito)) {
                $respuesta[] = array("status" => 'error', "message" => "Campos requeridos vacíos en una fila.");
                continue;
            }
        }
    } else {
        $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
    }
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

echo json_encode($respuesta);

function actualizarPresAdminExcel($idSolicitud, $montoDepositado, $fechaDeposito) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $conex->begin_transaction();

    try {
        $fechaResp = date("Y-m-d");

        $updateSol = $conex->prepare("UPDATE Prestamo 
                                      SET fechaDeposito = ?, 
                                          montoDepositado = ?
                                      WHERE idSolicitud = ?");
        $updateSol->bind_param("ssi", $fechaDeposito, $montoDepositado, $idSolicitud);
        $resultado = $updateSol->execute();

        if (!$resultado) {
            $respuesta = array('status' => 'error', 'message' => 'Error al actualizar la solicitud.');
        } else {
            // Registro en la bitácora
            $nomina = $_SESSION["nomina"];
            $descripcion = "Actualización por admin. idSolicitud:".$idSolicitud." Monto depositado: $".$montoDepositado." Fecha Deposito:".$fechaDeposito;

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