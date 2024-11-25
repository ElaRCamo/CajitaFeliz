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
    // Configurar la zona horaria
    date_default_timezone_set('America/Mexico_City');

    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    // Iniciar transacción
    $conex->begin_transaction();

    try {
        // Obtener fecha y hora actuales
        $fechaSolicitud = date("Y-m-d");
        $horaSolicitud = date("H:i:s");
        $anioActual = date('Y'); // Año actual

        // Obtener la fecha y hora de inicio desde la base de datos
        $selectFechaAut = $conex->prepare("SELECT fechaInicio, horaInicio FROM Convocatoria WHERE anio = ?");
        $selectFechaAut->bind_param("i", $anioActual);
        $selectFechaAut->execute();
        $resultado = $selectFechaAut->get_result();

        if ($resultado->num_rows > 0) {
            // Si se encuentra la fecha, obtenerla
            $row = $resultado->fetch_assoc();
            $fechaInicioDB = $row['fechaInicio'];
            $horaInicioDB = $row['horaInicio'];
        } else {
            throw new Exception("No se encontró la fecha de inicio para el año actual.");
        }

        // Construir datetime para comparar fecha y hora
        $fechaHoraInicioDB = new DateTime("$fechaInicioDB $horaInicioDB");
        $fechaHoraSolicitud = new DateTime("$fechaSolicitud $horaSolicitud");

        // Comparar fecha y hora actuales con las de la base de datos
        if ($fechaHoraSolicitud >= $fechaHoraInicioDB) {
            // Si la fecha y hora actuales son posteriores, hacer el INSERT

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
            // Convertir la fecha de inicio a un formato legible con el nombre del mes
            $fechaInicioFormatted = DateTime::createFromFormat("Y-m-d", $fechaInicioDB)->format("d \d\e F");
            setlocale(LC_TIME, 'es_ES.UTF-8'); // Asegurar la localización en español
            $fechaInicioFormatted = strftime("%d de %B", strtotime($fechaInicioDB));

            // Formatear la hora para mostrar solo horas y minutos
            $horaInicioFormatted = DateTime::createFromFormat("H:i:s", $horaInicioDB)->format("H:i");

            // Construir el mensaje de error
            $respuesta = array(
                "status" => 'error',
                "message" => "Por el momento no es posible atender tu solicitud. Las solicitudes se estarán recibiendo a partir del día $fechaInicioFormatted a las $horaInicioFormatted."
            );
        }

    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback y mostrar el mensaje de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    }

    return $respuesta;
}

?>