<?php

include_once('connectionCajita.php');

$anio = $_GET["anio"];
todosLosAhorros($anio);

function todosLosAhorros($anio){
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $datosPrueba =  mysqli_query($conex,
        "SELECT
                    s.idCaja,
                    s.nomina,
                    s.montoAhorro,
                    s.fechaSolicitud
                FROM
                    CajaAhorro s
                WHERE
                    YEAR(c.fechaSolicitud) like $anio
                ORDER BY
                    s.idSolicitud DESC;
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>