<?php
    require_once('config/config.php');

    // Obtener el ID del boletín que se retroalimentará
    if (isset($_GET['ID_boletin'])) {
        $id_boletin = $_GET['ID_boletin'];

        // Consultar el boletín original
        $query = "SELECT * FROM boletines WHERE ID_boletin = $id_boletin";
        $result = $conexion->query($query);

        if ($result->num_rows > 0) {
            // Obtenemos el boletín
            $row = $result->fetch_assoc();

            // Si el formulario se ha enviado, procesamos la retroalimentación
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Obtener la retroalimentación del formulario
                $retroalimentacion = $conexion->real_escape_string($_POST['Retroalimentacion']);

                $updateQuery = "UPDATE boletines SET Retroalimentacion = ? WHERE ID_boletin = ?";
                $updateStmt = $conexion->prepare($updateQuery);
                $updateStmt->bind_param("si", $retroalimentacion, $id_boletin);

                if ($updateStmt->execute()) {
                    echo "<script>alert('Retroalimentación actualizada con éxito.'); window.location.href='especialista.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Error al actualizar la retroalimentación.');</script>";
                }
            }
        } else {
            echo "<script>alert('Boletín no encontrado.'); window.location.href='especialista.php';</script>";
        }
    } else {
        echo "<script>alert('ID del boletín no especificado.'); window.location.href='especialista.php';</script>";
    }
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
  
    <div class="row mt-5 ms-4">
        <div class="col">
            <h1 class="d-inline">Retroalimentación de boletines</h1>
        </div>
    </div>
    
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Boletín <?php echo $row['ID_boletin']; ?></h1>
                <a href="especialista.php" class="btn btn-secondary">Volver</a>
            </div>
            <div class="card-body">
                <p><strong>Categoría:</strong> <?php echo $row['categoria']; ?></p>
                <p><strong>Parámetros:</strong> <?php echo $row['parametros']; ?></p>
                <p><strong>Fuentes:</strong>                    
                    <?php
                        // Mostrar las fuentes como enlaces
                        $fuentes = explode(",", $row['Fuentes']);
                        foreach ($fuentes as $fuente) {
                            echo "<a href='$fuente' target='_blank'>$fuente</a><br>";
                        }
                    ?></p>
            </div>
        </div>

        <form action="" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="Retroalimentacion" class="form-label">Retroalimentación</label>
                <textarea class="form-control" name="Retroalimentacion" id="Retroalimentacion" rows="5" placeholder="Ingrese aquí su retroalimentación"></textarea>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
    <script src="user-storage.js"></script>
  </body>
</html>
