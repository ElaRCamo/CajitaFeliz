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
        $respuesta = array("status" => 'error', "message" => "Faltan datos en el formulario.");
    }
}else{
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD");
}

echo json_encode($respuesta);

function guardarPrestamo($nomina, $montoSolicitado, $telefono) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Obtener fecha actual de la solicitud
        $fechaSolicitud = date("Y-m-d");
        $anioActual = date('Y'); // Año actual

        // Obtener la fecha de inicio desde la base de datos
        $selectFechaAut = $conex->prepare("SELECT fechaInicio FROM Convocatoria WHERE anio = ?");
        $selectFechaAut->bind_param("i", $anioActual);
        $selectFechaAut->execute();
        $resultado = $selectFechaAut->get_result();

        if ($resultado->num_rows > 0) {
            // Si se encuentra la fecha, obtenerla
            $fechaInicioDB = $resultado->fetch_assoc()['fechaInicio'];
        } else {
            throw new Exception("No se encontró la fecha de inicio para el año actual.");
        }

        // Comparar la fecha de solicitud con la fecha de inicio de la base de datos
        if (new DateTime($fechaSolicitud) > new DateTime($fechaInicioDB)) {
            // Si la fecha de solicitud es posterior a la fecha de inicio, hacer el INSERT

            // Preparar la consulta de inserción
            $insertPrestamo = $conex->prepare("INSERT INTO Prestamo (nominaSolicitante, montoSolicitado, telefono, fechaSolicitud) VALUES (?, ?, ?, ?)");
            $insertPrestamo->bind_param("ssss", $nomina, $montoSolicitado, $telefono, $fechaSolicitud);
            $resultado = $insertPrestamo->execute();

            // Verificar si la inserción fue exitosa
            if (!$resultado) {
                // En caso de error en la inserción, se lanza una excepción
                throw new Exception("Error al guardar el préstamo.");
            }

            // Obtener el ID generado automáticamente
            $idSolicitud = $conex->insert_id;

            // Confirmar la transacción
            $conex->commit();

            // Responder con éxito
            $respuesta = array("status" => 'success', "message" => "Folio de solicitud: " . $idSolicitud);
        } else {
            // Si la fecha de solicitud no es posterior a la fecha de inicio, devolver un mensaje de error
            $respuesta = array("status" => 'error', "message" => "Por el momento no es posible atender tu solicitud. Las solicitudes se estarán recibiendo a partir del día $fechaInicioDB.");
        }

    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback y mostrar el mensaje de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    }

    return $respuesta;
}

?>