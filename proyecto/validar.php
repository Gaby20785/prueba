<?php
    require_once('config/config.php');

    if (isset($_GET['ID_boletin'])) {
        $id_boletin = $_GET['ID_boletin'];

        $query = "SELECT * FROM boletines WHERE ID_boletin = $id_boletin";
        $result = $conexion->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $titulo = $row['categoria'] . ' ' . $row['numero'];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $url = $_POST['url'];
                $imagen = $_POST['imagen'];

                // Insertar en la tabla aceptados
                $insert_query = "INSERT INTO aceptados (titulo, imagen, url) VALUES (?, ?, ?)";
                $insert_stmt = $conexion->prepare($insert_query);
                $insert_stmt->bind_param("sss", $titulo, $imagen, $url);
                $insert_stmt->execute();

                // Eliminar el boletín de la tabla boletines
                $delete_query = "DELETE FROM boletines WHERE ID_boletin = ?";
                $delete_stmt = $conexion->prepare($delete_query);
                $delete_stmt->bind_param("i", $id_boletin);
                $delete_stmt->execute();

                // Redirigir después de la validación
                header("Location: init.html");
                exit();
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
    <title>Validación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
    <div class="row mt-5 ms-4">
        <div class="col">
            <h1 class="d-inline">Validación de boletines</h1>
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

        <form method="POST" class="mt-4">
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="URL del boletín" id="url" name="url" required style="height: 100px;"></textarea>
                <label for="url">Dirección del boletín (URL)</label>
            </div>

            <div class="form-floating mb-4">
                <textarea class="form-control" placeholder="URL de la imagen de portada" id="imagen" name="imagen" required style="height: 100px;"></textarea>
                <label for="imagen">Dirección de la portada (URL)</label>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-5">Validar</button>
            </div>
        </form>
    </div>
    <script src="user-storage.js"></script>
  </body>
</html>
