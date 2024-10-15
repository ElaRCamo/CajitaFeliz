<?php

include_once('connectionCajita.php');
todosLosPrestamos();

function todosLosPrestamos(){
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $datosPrueba =  mysqli_query($conex,
        "SELECT
                    s.idSolicitud,
                    s.nominaSolicitante,
                    s.fechaSolicitud,
                    s.montoSolicitado,
                    s.idEstatus,
                    s.telefono,
                    CASE
                        WHEN s.idEstatus = 1 
                            THEN CONCAT('<span class=\"badge bg-warning text-dark\" title=\"', e.detalles, '\">', e.descripcion, '</span>')
                        WHEN s.idEstatus = 2 
                            THEN CONCAT('<span class=\"badge bg-primary\" title=\"', e.detalles, '\">', e.descripcion, '</span>')
                        WHEN s.idEstatus = 3 
                            THEN CONCAT('<span class=\"badge bg-info text-dark\" title=\"', e.detalles, '\">', e.descripcion, '</span>')
                        WHEN s.idEstatus = 4 
                            THEN CONCAT('<span class=\"badge bg-success\" title=\"', e.detalles, '\">', e.descripcion, '</span>')
                    END AS estatusVisual
                FROM
                    Prestamo s
                    LEFT JOIN EstatusPrestamo e ON s.idEstatus = e.idEstatus
                WHERE
                    s.idEstatus <> 4
                ORDER BY
                    s.idSolicitud DESC;
                ");

    $resultado= mysqli_fetch_all($datosPrueba, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));

}

?>