<?php

require_once ["connectionCajita.php"];
session_start();

cargarUltimoAhorro();

function cargarUltimoAhorro()
{
    $con = new LocalConectorCajita();
    $conexion = $con -> conectar();

    $nomina = $_SESSION["nomina"];

    $consultaAhorro = "SELECT montoAhorro, nombre,direccion,telefono, porcentaje
                         FROM CajaAhorro ca, Beneficiarios b
                        WHERE nomina = '$nomina'
                          AND ca.idCaja = b.idCaja";

    $resultadoConsulta = mysqli_query($conexion,$consultaAhorro);
    mysqli_close($conexion);

    $resultadoConsulta= mysqli_fetch_assoc($resultadoConsulta, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultadoConsulta));
}

?>