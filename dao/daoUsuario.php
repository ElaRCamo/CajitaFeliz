<?php

include_once('connection.php');

function Usuario($Nomina){
    $con = new LocalConector();
    $conexion=$con->conectar();

    $consP="SELECT  IdUser, NomUser, IdTag FROM Empleados WHERE IdUser = '$Nomina'";
    $rsconsPro=mysqli_query($conexion,$consP);

    mysqli_close($conexion);

    if(mysqli_num_rows($rsconsPro) == 1){
        $row = mysqli_fetch_assoc($rsconsPro);
        return array(
            'success' => true, // Indicador de éxito
            'password_bd' => $row['IdTag'],
            'nombreUsuario' => $row['NomUser'],
            'idUser' => $row['IdUser']
        );
    }
    else{
        return array(
            'success' => false
        );
    }
}
?>