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
                    s.idEstatus,
                    e.descripcion,
                    s.telefono
                FROM
                    Prestamo s
                    LEFT JOIN EstatusPrestamo e ON s.idEstatus = e.idEstatus
                WHERE
                    s.idEstatus <> 4
                  AND idSolicitud = '$idSolicitud'
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>