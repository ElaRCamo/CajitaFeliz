<?php
include_once('connectionCajita.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idsolicitud'], $_POST['montoDepositado'], $_POST['fechaDeposito'])) {
        $idSolicitud = trim($_POST['idsolicitud']);
        $montoDepositado = trim($_POST['montoDepositado']);
        $fechaDeposito = trim($_POST['fechaDeposito']);

        // Validar datos
        if (empty($idSolicitud) || empty($montoAprobado) || empty($estatus)) {
            $respuesta = array("status" => 'error', "message" => "Algunos campos requeridos están vacíos.");
        } else {
            $respuesta = actualizarPresAdminExcel($idSolicitud, $montoAprobado, $estatus);
        }
    } else {
        $respuesta = array("status" => 'error', "message" => "Faltan datos en el formulario.");
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
        }else{
            // Registro en la bitácora
            $nomina = $_SESSION["nomina"];
            $descripcion = "Actualización por admin. Se carga excel de depositos de prestamos.";
            $insertBitacora = $conex->prepare("INSERT INTO BitacoraCambios (nomina, fecha, descripcion) VALUES(?,?,?)");
            $insertBitacora->bind_param("sss", $nomina, $fechaResp, $descripcion);
            $resultadoBitacora = $insertBitacora->execute();

            if (!$resultadoBitacora) {
                $respuesta = array('status' => 'error', 'message' => 'Error al registrar en bitácora.');
            }else {
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