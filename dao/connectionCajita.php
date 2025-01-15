<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class LocalConectorCajita{
    private $host = "127.0.0.1:3306";
    private $usuario = "poner aqui el usuario";
    private $clave = "poner aqui la contrasena";
    private $db = "poner aqui la base de datos";
    private $conexion;

    public function conectar(){
        $this->conexion = mysqli_connect($this->host, $this->usuario, $this->clave, $this->db);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        //echo "conexion exitosa";
        return $this->conexion;
    }
}
