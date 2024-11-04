<?php
include_once('connectionCajita.php');
include_once('funcionesGenerales.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decodificar el cuerpo JSON
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['retiros']) && is_array($inputData['retiros'])) {
        $respuesta = array();

        foreach ($inputData['retiros'] as $retiro) {
            // Validar y asignar valores
            $idRetiro = isset($retiro['idRetiro']) ? trim($retiro['idRetiro']) : null;
            $montoDepositado = isset($retiro['montoDepositado']) ? trim($retiro['montoDepositado']) : null;
            $fechaDeposito = isset($retiro['fechaDeposito']) ? trim($retiro['fechaDeposito']) : null;
            $fechaFormateada = formatearFecha($fechaDeposito);
            if ($fechaFormateada === false) {
                $respuesta = array("status" => 'error', "message" => "La fecha es inválida. Asegúrese de usar un formato correcto.");
            } else {
                // Llamar a la función de actualización con la fecha en el formato correcto
                $resultado = actualizarPresAdminExcel($idRetiro, $montoDepositado, $fechaFormateada);
                $respuesta[] = $resultado;
            }

            // Validar datos
            if (empty($idRetiro) || empty($montoDepositado) || empty($fechaDeposito)) {
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

function actualizarPresAdminExcel($idRetiro, $montoDepositado, $fechaDeposito) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $conex->begin_transaction();

    try {
        $fechaResp = date("Y-m-d");

        $updateSol = $conex->prepare("UPDATE RetiroAhorro 
                                               SET fechaDeposito = ?, 
                                                   montoDepositado = ?
                                             WHERE idRetiro = ?");
        $updateSol->bind_param("ssi", $fechaDeposito, $montoDepositado, $idRetiro);
        $resultado = $updateSol->execute();

        if (!$resultado) {
            $respuesta = array('status' => 'error', 'message' => 'Error al actualizar la solicitud.');
        } else {
            // Registro en la bitácora
            $nomina = $_SESSION["nomina"];
            $descripcion = "Actualización RetiroAhorro por admin. idRetiro:".$idRetiro." Monto depositado: $".$montoDepositado." Fecha Deposito:".$fechaDeposito;

            $resultadoBitacora = actualizarBitacoraCambios( $nomina, $fechaResp, $descripcion, $conex);

            if (!$resultadoBitacora) {
                $respuesta = array('status' => 'error', 'message' => 'Error al registrar en bitácora.');
            } else {
                $conex->commit();
                $respuesta = array("status" => 'success', "message" => "Solicitud $idRetiro actualizada exitosamente.");
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