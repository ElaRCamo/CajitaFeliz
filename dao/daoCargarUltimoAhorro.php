<?php
require_once("connectionCajita.php");
session_start();

cargarUltimoAhorro();

function cargarUltimoAhorro()
{
    if (!isset($_SESSION["nomina"])) {
        echo json_encode(array("error" => "No se encontró la sesión de nomina."));
        return;
    }

    $con = new LocalConectorCajita();
    $conexion = $con->conectar();

    if (!$conexion) {
        echo json_encode(array("error" => "Error de conexión a la base de datos."));
        return;
    }

    $nomina = $_SESSION["nomina"];

    $consultaAhorro = "SELECT ca.montoAhorro, b.nombre, b.direccion, b.telefono, b.porcentaje
                       FROM CajaAhorro ca
                       JOIN Beneficiarios b ON ca.idCaja = b.idCaja
                       WHERE ca.nomina = ?
                         AND ca.idCaja = (
                             SELECT MAX(idCaja)
                             FROM CajaAhorro
                             WHERE nomina = ?
                         );";

    $stmt = mysqli_prepare($conexion, $consultaAhorro);
    mysqli_stmt_bind_param($stmt, "ss", $nomina, $nomina);

    if (mysqli_stmt_execute($stmt)) {
        $resultadoConsulta = mysqli_stmt_get_result($stmt);
        $resultado = mysqli_fetch_all($resultadoConsulta, MYSQLI_ASSOC);
        echo json_encode(array("data" => $resultado));
    } else {
        echo json_encode(array("error" => "Error en la consulta de base de datos."));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>