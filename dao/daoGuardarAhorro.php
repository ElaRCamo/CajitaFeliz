<?php
include_once('connectionCajita.php');

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['montoAhorro'],$_POST['nombreBen1'],$_POST['porcentajeBen1'],$_POST['telefonoBen1'],$_POST['domicilioBen1'])){
        $montoAhorro = $_POST['montoAhorro'];
        $nomina = $_SESSION['nomina'];
        $beneficiarios = [];

        // Agregar el primer beneficiario
        $beneficiarios[] = array(
            'nombre' => $_POST['nombreBen1'],
            'porcentaje' => $_POST['porcentajeBen1'],
            'telefono' => $_POST['telefonoBen1'],
            'domicilio' => $_POST['domicilioBen1']
        );

        // Verificar si hay otro beneficiario
        for ($i = 2; $i <= 2; $i++) {
            if (isset($_POST['nombreBen' . $i], $_POST['porcentajeBen' . $i], $_POST['telefonoBen' . $i], $_POST['domicilioBen' . $i])) {
                $beneficiarios[] = array(
                    'nombre' => $_POST['nombreBen' . $i],
                    'porcentaje' => $_POST['porcentajeBen' . $i],
                    'telefono' => $_POST['telefonoBen' . $i],
                    'domicilio' => $_POST['domicilioBen' . $i]
                );
            } else {
                break;
            }
        }

        $respuesta = guardarAhorro($nomina, $montoAhorro, $beneficiarios);
    }
    else{
        $respuesta = array("status" => 'error', "message" => "Faltan datos en el formulario.");
    }
}else{
    $respuesta = array("status" => 'error', "message" => "Se esperaba REQUEST_METHOD");
}

echo json_encode($respuesta);

function guardarAhorro($nomina, $monto, $beneficiarios) {
    $con = new LocalConectorCajita();
    $conex = $con->conectar();

    $conex->begin_transaction();

    try {
        $fechaSolicitud = date("Y-m-d");

        $insertAhorro = $conex->prepare("INSERT INTO CajaAhorro (nomina, montoAhorro, fechaSolicitud) VALUES ( ?, ?, ?)");
        $insertAhorro->bind_param("sss", $nomina, $monto, $fechaSolicitud);
        $resultado = $insertAhorro->execute();

        if (!$resultado) {
            throw new Exception("Error al guardar el registro.");
        }else{
            $rGuardarObjetos = true;
            // Obtener el ID generado automáticamente
            $idSolicitud = $conex->insert_id;

            //Registrar Beneficiarios
            for ($i = 1; $i <= count($beneficiarios); $i++) {
                $nombre = $beneficiarios[$i]['nombre'];
                $porcentaje = $beneficiarios[$i]['porcentaje'];
                $telefono = $beneficiarios[$i]['telefono'];
                $domicilio = $beneficiarios[$i]['domicilio'];

                // Inserción en la base de datos
                $insertBeneficiario = $conex->prepare("INSERT INTO `Beneficiarios` (`idCaja`, `nombre`, `direccion`, `telefono`, `porcentaje`) 
                                               VALUES (?, ?, ?, ?, ?)");
                $insertBeneficiario->bind_param("issss", $idSolicitud, $nombre, $domicilio, $telefono, $porcentaje);
                $rGuardarObjetos = $rGuardarObjetos && $insertBeneficiario->execute();
            }

            if(!$rGuardarObjetos){
                $respuesta = array('status' => 'error', 'message' => 'Error en Registrar Solicitud');
            }else{
                $respuesta = array("status" => 'success', "message" => "Tu ahorro ha sido aprobado exitosamente, lo veras reflejado proximamente en tu nómina");
            }
        }
        $conex->commit();

    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conex->rollback();
        $respuesta = array("status" => 'error', "message" => $e->getMessage());
    } finally {
        $conex->close();
    }

    return $respuesta;
}

?>
