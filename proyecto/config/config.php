<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "boletines";

$conexion = new mysqli($host, $user, $password, $db);

if($conexion->connect_errno){
    echo "Falló la conexión con la base de datos" . $conexion->connect_error;
}

?>