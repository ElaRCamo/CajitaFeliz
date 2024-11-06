<?php

require_once ["connectionCajita.php"];
session_start();

cargarUltimoAhorro();

function cargarUltimoAhorro()
{
    $con = new LocalConectorCajita();
    $conexion = $con -> conectar();

    $nomina = $_SESSION["nomina"];

    $consultaAhorro = "SELECT ca.montoAhorro, b.nombre, b.direccion, b.telefono, b.porcentaje
                        FROM CajaAhorro ca
                        JOIN Beneficiarios b ON ca.idCaja = b.idCaja
                       WHERE ca.nomina = '$nomina'
                         AND ca.idCaja = (
                             SELECT MAX(idCaja)
                             FROM CajaAhorro
                             WHERE nomina = '$nomina');";

    $resultadoConsulta = mysqli_query($conexion,$consultaAhorro);

    if (!$resultadoConsulta) {
        echo "Error en la consulta SQL: " . mysqli_error($conexion);
        mysqli_close($conexion);
        return;
    }

    mysqli_close($conexion);
    $resultado = mysqli_fetch_all($resultadoConsulta, MYSQLI_ASSOC);
    echo json_encode(array("data" => $resultado));
}

?>

