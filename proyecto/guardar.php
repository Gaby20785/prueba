<?php
    require_once('config/config.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_boletin = $_POST['id_boletin'] ?? null;
        $contenido = $_POST['contenido'] ?? '';

        if (!$id_boletin || $contenido === '') {
            echo "Datos inválidos.";
            echo $id_boletin;
            echo $contenido;
            exit;
        }

        $stmt = $conexion->prepare("UPDATE boletines SET contenido = ? WHERE ID_boletin = ?");
        $stmt->bind_param("si", $contenido, $id_boletin);

        if ($stmt->execute()) {
            header("Location: especialista.php?mensaje=guardado");
            exit;
        } else {
            echo "Error al guardar los cambios.";
        }
    }

?>