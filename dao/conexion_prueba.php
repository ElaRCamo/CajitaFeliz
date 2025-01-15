<?php
    $conexion = mysqli_connect("127.0.0.1:3306","poner aqui el usuario","poner aqui la contrasena","poner aqui la base de datos");
    if (!$conexion) {
        echo 'conexion exitosa';
    }
    else {
        echo 'conexion fallida';
    }

    ?>
