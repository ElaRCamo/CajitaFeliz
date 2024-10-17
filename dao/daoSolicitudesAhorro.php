<?php

include_once('connectionCajita.php');

$anio = $_GET["anio"];
todosLosAhorros($anio);

function todosLosAhorros($anio){
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $datosPrueba =  mysqli_query($conex,
        "SELECT
                    idCaja,
                    nomina,
                    montoAhorro,
                    fechaSolicitud
                FROM
                    CajaAhorro 
                WHERE
                    YEAR(fechaSolicitud) like '$anio'
                ORDER BY
                    idCaja DESC;
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>