<?php
include_once('connectionCajita.php');
include_once('funcionesGenerales.php');

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
            // Validar si ya existe una solicitud en proceso en el periodo actual
            $existeSolActiva = validarYInsertarSolicitud($conex, $nomina, "$fechaSolicitud $horaSolicitud");

            if ($existeSolActiva === 0) {
                // Si no hay solicitudes en proceso, proceder con el INSERT
                $insertPrestamo = $conex->prepare(
                    "INSERT INTO Prestamo (nominaSolicitante, montoSolicitado, telefono, fechaSolicitud) 
                            VALUES (?, ?, ?, ?)"
                );
                $fechaSolicitud = (new DateTime($fechaHoraSolicitud))->format('Y-m-d');
                $insertPrestamo->bind_param("ssss", $nomina, $montoSolicitado, $telefono, $fechaSolicitud);
                $resultadoInsert = $insertPrestamo->execute();

                if (!$resultadoInsert) {
                    throw new Exception("Error al la solicitud de préstamo. Intente más tarde.");
                }

                // Obtener el ID generado automáticamente
                $idSolicitud = $conex->insert_id;

                // Responder con éxito
                $respuesta = array("status" => 'success', "message" => "Folio de solicitud: " . $idSolicitud);


            }else if($existeSolActiva > 0){
                // Ya existe una solicitud en proceso
                $respuesta = array("status" => 'error',"message" => "Ya existe una solicitud activa para el periodo actual.");
            }else{
                $respuesta = array("status" => 'error', "message" => $existeSolActiva);
            }

        } else {
            // Construir el mensaje de error utilizando la función formatearFechaHora
            $mensajeFechaHora = formatearFechaHora($fechaInicioDB, $horaInicioDB);

            $respuesta = array(
                "status" => 'error',
                "message" => "Por el momento no es posible atender tu solicitud. Las solicitudes se estarán recibiendo a partir del día $mensajeFechaHora horas."
            );
        }

    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback y mostrar el mensaje de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    }

    return $respuesta;
}

function validarYInsertarSolicitud($conex, $nomina, $fechaHoraSolicitud) {
    try {
        $anioSolicitud = (new DateTime($fechaHoraSolicitud))->format('Y');

        // Verificar si ya existe una solicitud en proceso para este año
        $queryValidacion = $conex->prepare(
            "SELECT COUNT(*) AS total FROM Prestamo 
             WHERE nominaSolicitante = ? 
             AND YEAR(fechaSolicitud) = ? 
             AND idEstatus IN (1, 3)"
        );
        $queryValidacion->bind_param("si", $nomina, $anioSolicitud);
        $queryValidacion->execute();
        $resultadoValidacion = $queryValidacion->get_result();
        $respuesta = $resultadoValidacion->fetch_assoc();

    } catch (Exception $e) {
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    }

    return $respuesta;
}

/*
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
            // Construir el mensaje de error utilizando la función formatearFechaHora
            $mensajeFechaHora = formatearFechaHora($fechaInicioDB, $horaInicioDB);

            $respuesta = array(
                "status" => 'error',
                "message" => "Por el momento no es posible atender tu solicitud. Las solicitudes se estarán recibiendo a partir del día $mensajeFechaHora horas."
            );

        }

    } catch (Exception $e) {
        // Si ocurre un error, hacer rollback y mostrar el mensaje de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    }

    return $respuesta;
}
*/

?>