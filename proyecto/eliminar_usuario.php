<?php
require_once('config/config.php');

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $query = "DELETE FROM users WHERE email=?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        header("Location: administrador.php");
        exit();
    } else {
        echo "Error al eliminar usuario.";
    }
} else {
    echo "Email no proporcionado.";
}
?>