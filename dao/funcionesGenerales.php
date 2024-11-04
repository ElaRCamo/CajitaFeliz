<?php

function actualizarBitacoraCambios( $nomina, $fechaResp, $descripcion, $conex)
{
    $insertBitacora = $conex->prepare("INSERT INTO BitacoraCambios (nomina, fecha, descripcion) VALUES(?,?,?)");
    $insertBitacora->bind_param("sss", $nomina, $fechaResp, $descripcion);
    return $insertBitacora->execute();
}

function formatearFecha($fecha) {
    // Intentar crear un objeto DateTime desde la fecha ingresada
    $fechaFormateada = DateTime::createFromFormat('Y/m/d', $fecha);

    // Verificar si la fecha es válida
    if ($fechaFormateada && $fechaFormateada->format('Y/m/d') === $fecha) {
        // La fecha ya está en el formato correcto
        return $fechaFormateada->format('Y-m-d');
    } else {
        // Intentar crear un objeto DateTime desde la fecha en otros formatos comunes
        $fechaAlternativa = DateTime::createFromFormat('d/m/Y', $fecha)
            ?: DateTime::createFromFormat('m/d/Y', $fecha)
                ?: DateTime::createFromFormat('Y-m-d', $fecha);

        // Si se pudo crear un objeto DateTime, ajustar la fecha al formato yyyy/mm/dd
        if ($fechaAlternativa) {
            return $fechaAlternativa->format('Y-m-d'); // Convertir a formato de base de datos
        } else {
            return false; // La fecha no es válida
        }
    }
}

?>