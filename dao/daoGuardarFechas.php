<?php
include_once('connectionCajita.php');

if (!empty($_POST["fechaInicio"]) && !empty($_POST["fechaCierre"]) && !empty($_POST["anio"])) {
    $fechaInicio = $_POST["fechaInicio"];
    $fechaCierre = $_POST["fechaCierre"];
    $anio = intval($_POST["anio"]);

    // Validar que las fechas sean válidas
    $anioInicio = intval(date('Y', strtotime($fechaInicio)));
    $anioCierre = intval(date('Y', strtotime($fechaCierre)));
    $anioActual = intval(date('Y'));

    // Validar que ambas fechas pertenezcan al mismo año
    if ($anioInicio !== $anioCierre) {
        $response = array("status" => 'error',"message" => "Ambas fechas deben pertenecer al mismo año.");
    }
    // Validar que el año coincida con el año actual
    elseif ($anioInicio !== $anioActual) {
        $response = array("status" => 'error',"message" => "Las fechas deben corresponder al año actual ($anioActual).");
    }
    // Validar que el año proporcionado coincida con el año de las fechas
    elseif ($anioInicio !== $anio) {
        $response = array("status" => 'error',"message" => "El año ingresado no coincide con las fechas seleccionadas.");
    }
    // Guardar las fechas si todas las validaciones son correctas
    else {
        $response = guardarFechas($fechaInicio, $fechaCierre, $anio);
    }
} else {
    $response = array("status" => 'error',"message" => "Faltan datos en el formulario.");
}

echo json_encode($response);

function guardarFechas($fechaInicio, $fechaCierre, $anio){
    $con = new LocalConectorCajita();
    $conexion=$con->conectar();

    //Buscar en la base de datos si ya existe el registro
    $selectFechas = $conexion->prepare("SELECT id FROM Convocatoria WHERE anio = ?");
    $selectFechas->bind_param("i", $anio);
    $selectFechas ->execute();
    $selectFechas->store_result(); // Almacena el resultado

    //Si ya hay un registro con ese año, se actualiza
    if($selectFechas -> num_rows > 0){
        $updateFechas = $conexion->prepare("UPDATE Convocatoria 
                                                     SET fechaInicio = ?, fechaCierre = ?
                                                   WHERE id = ?");
        $updateFechas->bind_param("ssi", $fechaInicio, $fechaCierre);
        $resultado = $updateFechas->execute();
    }else{
        $insertFechas = $conexion->prepare("INSERT INTO Convocatoria (anio, fechaInicio, fechaFin)
                                                       VALUES (?, ?, ?)");
        $insertFechas->bind_param("iss", $anio,$fechaInicio, $fechaCierre);
        $resultado = $insertFechas->execute();
    }
    $conexion->close();

    if(!$resultado){
        $response = array("status"=>"error", "message"=>"Error al actualizar las fechas.");
    }else{
        $response = array ("status"=>"success", "message"=>"Fechas actualizadas exitosamente");
    }

    return $response;
}
?>