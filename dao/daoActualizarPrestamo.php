<?php
include_once('connectionCajita.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['idPrestamo'],$_POST['telefono'],$_POST['montoSolicitado'])){
        $solicitud = $_POST['idPrestamo'];
        $telefono = $_POST['telefono'];
        $montoSolicitado = $_SESSION['montoSolicitado'];

        $respuesta = actualizarPrestamo($solicitud,$montoSolicitado,$telefono);
    }
    else{
        $respuesta = array("status" => 'error', "message" => "Faltan datos en el formulario.");
    }
}else{
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD");
}

echo json_encode($respuesta);

function  actualizarPrestamo($solicitud,$montoSolicitado,$telefono)
{
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        $updateSol = $conex->prepare("UPDATE Prestamo 
                                              SET montoSolicitado = ?,
                                                  telefono = ?
                                              WHERE idSolicitud = ?");
        $updateSol->bind_param("ssi", $montoSolicitado, $telefono, $solicitud);
        $resultado = $updateSol->execute();

        $respuesta = array("status" => 'success', "message" => "Actualización exitosa");

    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback y mostrar el mensaje de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    }finally {
        $conex->close();
    }

    return $respuesta;
}