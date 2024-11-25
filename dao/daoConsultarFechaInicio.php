<?php
include_once('connection.php');

consultarFechas();
function consultarFechas(){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $anioActual = intval(date('Y'));

    $consP="SELECT fechaInicio, fechaFin FROM Convocatoria WHERE anio = '$anioActual'";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    $resultado= mysqli_fetch_all($rsconsPro, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>