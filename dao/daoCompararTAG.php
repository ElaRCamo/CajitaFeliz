<?php
include_once('connection.php');
include_once('daoUsuario.php');

session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['password'], $_POST['user'])){
        $tagUser = Usuario($_POST['user']);

        if($tagUser['success'] && $_POST['password'] == $tagUser['password_bd']){
            $respuesta = array("success" => true, "message" => "Inicio de sesión correcto.");
        } else {
            $respuesta = array("success" => false, "message" => "TAG incorrecto.");
        }
    }
    else{
        $respuesta = array("success" => false, "message" => "Faltan datos en el formulario.");
    }
}else{
    $respuesta = array("success" => false, "message" => "Se esperaba REQUEST_METHOD");
}

echo json_encode($respuesta);
?>
