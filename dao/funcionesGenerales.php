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
        return $fechaFormateada->format('Y/m/d');
    } else {
        // Intentar crear un objeto DateTime desde la fecha en otros formatos comunes
        $fechaAlternativa = DateTime::createFromFormat('d/m/Y', $fecha) // d/m/Y
            ?: DateTime::createFromFormat('m/d/Y', $fecha) // m/d/Y
                ?: DateTime::createFromFormat('Y-m-d', $fecha) // Y-m-d
                    ?: DateTime::createFromFormat('d/m/y', $fecha) // d/m/y
                        ?: DateTime::createFromFormat('m/d/y', $fecha) // m/d/y
                            ?: DateTime::createFromFormat('Y-m-d H:i:s', $fecha); // Y-m-d H:i:s

        // Manejo del formato con mes en texto
        if (!$fechaAlternativa) {
            $meses = [
                'enero' => '01', 'febrero' => '02', 'marzo' => '03', 'abril' => '04',
                'mayo' => '05', 'junio' => '06', 'julio' => '07', 'agosto' => '08',
                'septiembre' => '09', 'octubre' => '10', 'noviembre' => '11', 'diciembre' => '12'
            ];
            // Cambiar la fecha a formato d/m/Y o m/d/Y si tiene un mes en texto
            foreach ($meses as $mesTexto => $mesNumero) {
                if (stripos($fecha, $mesTexto) !== false) {
                    // Reemplazar el mes por su número correspondiente
                    $fecha = preg_replace('/\b' . preg_quote($mesTexto, '/') . '\b/i', $mesNumero, $fecha);
                    break; // Salir del bucle una vez que se haya encontrado y reemplazado
                }
            }
            // Intentar nuevamente con el formato cambiado
            $fechaAlternativa = DateTime::createFromFormat('d/m/Y', $fecha)
                ?: DateTime::createFromFormat('m/d/Y', $fecha);
        }

        // Si se pudo crear un objeto DateTime, ajustar la fecha al formato yyyy/mm/dd
        if ($fechaAlternativa) {
            return $fechaAlternativa->format('Y/m/d'); // Convertir a formato deseado
        } else {
            return false; // La fecha no es válida
        }
    }
}


?>