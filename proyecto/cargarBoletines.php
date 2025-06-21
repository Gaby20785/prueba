<?php
    require_once('config/config.php');

    $sql = "SELECT ID, titulo, url, imagen FROM aceptados";
    $result = $conexion->query($sql);

    $books = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = array(
                "title" => $row["titulo"],
                "author" => "N/A",
                "img" => $row["imagen"],
                "link" => $row["url"]
            );
        }
    }

    echo json_encode($books);

?>