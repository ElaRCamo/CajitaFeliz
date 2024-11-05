<?php
include_once('connectionCajita.php');
include_once('connection.php');
include_once('funcionesGenerales.php');

session_start();

function ObtenerNombresRetiros($retiros){

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);

    if (isset($inputData['retiros']) && is_array($inputData['retiros'])) {
        $todosExitosos = true; // Indicador de éxito global

        foreach ($inputData['retiros'] as $retiro) {
            $idRetiro = isset($retiro['idRetiro']) ? trim($retiro['idRetiro']) : null;
            $montoDepositado = isset($retiro['montoDepositado']) ? trim($retiro['montoDepositado']) : null;
            $fechaDeposito = isset($retiro['fechaDeposito']) ? trim($retiro['fechaDeposito']) : null;
            $fechaFormateada = formatearFecha($fechaDeposito);

            // Validar datos
            if (empty($idRetiro) || empty($montoDepositado) || empty($fechaDeposito) || $fechaFormateada === false) {
                $respuesta = array("status" => 'error', "message" => "Error en los datos de entrada o en el formato de fecha.");
                $todosExitosos = false;
                break;
            }

            // Llamar a la función de actualización con la fecha en el formato correcto
            $resultado = actualizarRetirosAdminExcel($idRetiro, $montoDepositado, $fechaFormateada);

            // Verificar el resultado y detener si hay un error
            if ($resultado['status'] !== 'success') {
                $respuesta = $resultado;
                $todosExitosos = false;
                break;
            }
        }

        // Respuesta final si todos fueron exitosos
        if ($todosExitosos) {
            $respuesta = array("status" => 'success', "message" => "Todos los retiros se actualizaron exitosamente.");
        }
    } else {
        $respuesta = array("status" => 'error', "message" => "Datos no válidos.");
    }
} else {
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD POST");
}

echo json_encode($respuesta);

function actualizarRetirosAdminExcel($idRetiro, $montoDepositado, $fechaDeposito) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $conex->begin_transaction();

    try {
        $fechaResp = date("Y-m-d");

        $updateSol = $conex->prepare("UPDATE RetiroAhorro 
                                       SET fechaDeposito = ?, 
                                           montoDepositado = ?,
                                           estatusRetiro = 1
                                       WHERE idRetiro = ?");
        $updateSol->bind_param("ssi", $fechaDeposito, $montoDepositado, $idRetiro);
        $resultado = $updateSol->execute();

        if (!$resultado) {
            $respuesta = array('status' => 'error', 'message' => 'Error al actualizar la solicitud.');
        } else {
            $nomina = $_SESSION["nomina"];
            $descripcion = "Actualización RetiroAhorro por admin. idRetiro:".$idRetiro." Monto depositado: $".$montoDepositado." Fecha Deposito:".$fechaDeposito;

            $resultadoBitacora = actualizarBitacoraCambios($nomina, $fechaResp, $descripcion, $conex);

            if (!$resultadoBitacora) {
                $respuesta = array('status' => 'error', 'message' => 'Error al registrar en bitácora.');
            } else {
                $conex->commit();
                $respuesta = array("status" => 'success', "message" => "Solicitud $idRetiro actualizada exitosamente.");
            }
        }
    } catch (Exception $e) {
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }
    return $respuesta;
}

?>