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

}