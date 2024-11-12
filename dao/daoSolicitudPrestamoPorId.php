<?php

include_once('connectionCajita.php');
$idSolicitud = $_GET["id_solicitud"];
obtenerPrestamoPorId($idSolicitud);

function obtenerPrestamoPorId($idSolicitud){
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $datosPrueba =  mysqli_query($conex,
        "SELECT
                    s.idSolicitud,
                    s.nominaSolicitante,
                    s.fechaSolicitud,
                    s.montoSolicitado,
                    s.fechaDeposito,
                    s.montoDepositado,
                    s.idEstatus,
                    s.telefono,
                    s.montoAprobado,
                    s.comentariosAdmin,
                    s.nominaAval1,
                    s.nominaAval2
                FROM
                    Prestamo s
                WHERE idSolicitud = '$idSolicitud'
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>