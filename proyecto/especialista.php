<?php
    require_once('config/config.php');

    // Consultar boletines desde la base de datos
    $query = "SELECT * FROM boletines";
    $result = $conexion->query($query);

?>


<!doctype html>
<html lang="en">
  <head>
    <link rel="icon" href="media/fia.png" type="image/png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Especialista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
    <div class="container-fluid">
        <div class="row mt-5 ms-4">
            <div class="col-auto">
                <h1 class="d-inline">Validación de boletines</h1>
            </div>
            <div class="col-auto">
                <a href="init.html" class="btn btn-secondary">Volver</a>
            </div>
        </div>

        <div class="row mt-5 ms-4">
            <div class="col">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>#</th>
                            <th>Categoría</th>
                            <th>Parametros</th>
                            <th>Fuentes</th>
                            <th>Retroalimentacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($row = $result->fetch_assoc()){
                        ?>
                        <tr>
                            <td><?php echo $row['ID_boletin']; ?></td>
                            <td><?php echo $row['numero']; ?></td>
                            <td><?php echo $row['categoria']; ?></td>
                            <td><?php echo $row['parametros']; ?></td>
                            <td><?php echo $row['Fuentes']; ?></td>
                            <td><?php echo $row['Retroalimentacion']; ?></td>
                            <td>
                                <a href="editar.php?ID_boletin=<?php echo $row['ID_boletin']; ?>" class="btn btn-primary">Editar</a>
                            </td>
                            <td>
                                <a href="retroalimentar.php?ID_boletin=<?php echo $row['ID_boletin']; ?>" class="btn btn-warning me-2">Retroalimentar</a>
                            </td>
                            <td>
                                <a href="validar.php?ID_boletin=<?php echo $row['ID_boletin']; ?>" class="btn btn-success">Validar</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="user-storage.js"></script>

    </body>
</html>