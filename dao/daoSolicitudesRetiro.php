<?php

include_once('connectionCajita.php');

$anio = $_GET["anio"];
todosLosRetiros($anio);

function todosLosRetiros($anio){
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $datosPrueba =  mysqli_query($conex,
        "SELECT
                    idRetiro,
                    C.idCaja,
                    nomina,
                    R.fechaSolicitud,
                    montoDepositado,
                    fechaDeposito
                FROM
                    CajaAhorro C, RetiroAhorro R
                WHERE
                    YEAR(R.fechaSolicitud) like '$anio'
                    AND C.idCaja = R.idCaja
                ORDER BY
                    R.fechaSolicitud DESC;
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}
?>