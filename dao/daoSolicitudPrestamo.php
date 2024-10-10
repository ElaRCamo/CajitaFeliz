<?php
include_once('connectionCajita.php');

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['telefono'],$_POST['montoSolicitado'])){
        $telefono = $_POST['telefono'];
        $montoSolicitado = $_POST['montoSolicitado'];
        $nomina = $_SESSION['nomina'];

        $respuesta = guardarPrestamo($nomina,$montoSolicitado,$telefono);
    }
    else{
        $respuesta = array("success" => false, "message" => "Faltan datos en el formulario.");
    }
}else{
    $respuesta = array("success" => false, "message" => "Se esperaba REQUEST_METHOD");
}

echo json_encode($respuesta);

function guardarPrestamo($nomina, $montoSolicitado, $telefono) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $conex->begin_transaction();

    try {
        $fechaSolicitud = date("Y-m-d");

        $insertPrestamo = $conex->prepare("INSERT INTO Prestamo (nominaSolicitante, montoSolicitado, telefono, fechaSolicitud) VALUES (?, ?, ?, ?)");
        $insertPrestamo->bind_param("ssss", $nomina, $montoSolicitado, $telefono, $fechaSolicitud);
        $resultado = $insertPrestamo->execute();

        if (!$resultado) {
            throw new Exception("Error al guardar el préstamo.");
        }

        // Obtener el ID generado automáticamente
        $idSolicitud = $conex->insert_id;

        $conex->commit();

        return $respuesta = array("success" => true, "message" => "Folio de solicitud: " . $idSolicitud);

    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conex->rollback();
        return array("success" => false, "message" => $e->getMessage());
    } finally {
        $conex->close();
    }
}

?>
