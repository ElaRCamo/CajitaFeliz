<?php

include_once('connectionCajita.php');
todosLosAhorros();

function todosLosAhorros(){
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
                    s.idEstatus <> 4
                ORDER BY
                    s.idSolicitud DESC;
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>