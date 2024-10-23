<?php
include_once('connectionCajita.php');

if (isset ($_POST["idSolicitud"],$_POST["nom1"],$_POST["nom2"])){
    $solicitud = $_POST["idSolicitud"];
    $nomina1 = $_POST["nom1"];
    $nomina2 = $_POST["nom2"];

    $response = guardarAvalesDB($solicitud, $nomina1, $nomina2);
}else{
    $response = (array("data" => "Faltan datos en el formulario"));
}
echo json_encode($response);


function guardarAvalesDB($solicitud, $Nomina1, $Nomina2){
    $con = new LocalConectorCajita();
    $conexion=$con->conectar();

    $updateAvales = $conexion->prepare("UPDATE Prestamo 
                                                 SET nominaAval1 = ?, nominaAval2 = ?
                                               WHERE IdSolicitud = ?");
    $updateAvales->bind_param("ssi",$solicitud,$Nomina1,$Nomina2);
    $resultado = $updateAvales->execute();

    mysqli_close($conexion);

    if(!$resultado){
        $response = array("status"=>"error", "message"=>"Error al actualizar los avales.");
    }else{
        $response = array ("status"=>"success", "message"=>"Se ha guardado $Nomina1 y $Nomina2 como avales de la solicitud $solicitud");
    }

    return $response;
}
?>
