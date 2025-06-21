<?php
    require_once('config/config.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fuentes = $_POST['fuentes'];
  
      // Guardar las fuentes en un archivo temporal para que el script Python pueda leerlas
      $fuentesFile = 'fuentes.txt';
      file_put_contents($fuentesFile, $fuentes);
  
      // Llamar al script Python para realizar el web scraping
      $output = shell_exec("python scrape.py $fuentesFile");
      
  }
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parametros = $_POST['parametros'];
    $fuentes = $_POST['fuentes'];
    $numero = $_POST['numero'];
    $categoria = $_POST['categoria'];
    $retroalimentacion = "";
    switch ($categoria) {
      case 'Adaptación y Mitigación al Cambio Climático':
          $contenido = file_get_contents('plantillas/climatico.html');
          break;
      case 'Gestión Sostenible de Recursos Hídricos':
          $contenido = file_get_contents('plantillas/alimentario.html');
          break;
      case 'Sistemas Alimentarios Sostenibles':
          $contenido = file_get_contents('plantillas/hidrico.html');
          break;
  }

    $query = "INSERT INTO boletines (numero, categoria, parametros, fuentes, retroalimentacion, contenido) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("isssss", $numero, $categoria, $parametros, $fuentes, $retroalimentacion, $contenido);
    $stmt->execute();
    // Respuesta para enviar al frontend
    echo json_encode(["success" => true, "message" => "Web scraping realizado y boletin creado. Datos almacenados en la base de datos."]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="media/fia.png" type="image/png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="/script.js"></script>
  <style>
    @import url("css/styles1.css");
  </style>
  <!--bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script>
    
    function NoRecargar(event) {
    event.preventDefault(); // no recarga la pagina
    
    const formData = new FormData(event.target);

    fetch("", {
      method: "POST",
      body: formData,
    })
    .then(response => response.json()) 
    .then(data => {
      if (data.success) {
        //muestra la cosa abajo
        document.getElementById("response").innerHTML = 
          '<div class="alert alert-success" role="alert">' + data.message + '</div>';
      }
      
      setTimeout(function() {//manda a respuesta el mensaje de que se hizo el webscrap
          document.getElementById("response").innerHTML = ""; // quita el mensaje de webscrap
        }, 10000); 
      
    })
    .catch(error => {
      console.error("Error:", error); // por si saltan errores
    });
  }
    </script>


</head>
  <!--bootstrap -->
  <div id="response" style="position: fixed; bottom: 10px; left: 50%; transform: translateX(-50%); z-index: 999;"></div> <!-- esto es el mensaje del webcraping realizado no tocar mucho-->

  <body style="background-image: url(https://cdn.glitch.global/f624dee9-81bf-4f9d-983d-ac102ffdfab7/fondo?v=1727044433376); background-repeat: no-repeat; background-size: cover;">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <h1>&nbsp Creación de boletines</h1>

  <!-- Cambiar el action para que apunte a /generar-pdf  action="/generar-pdf"-->
  <form class="parameters" method="POST" onsubmit="NoRecargar(event)"> <!-- aqui hice que cuando haga submit se llame a la funcion que hace que no recargue la pagina y que lo mande a alguna pagina si un parametro calza -->
    <div class="form-group">
      <label class="form-label" for="categoria">Categoría:</label>
      <select required class="form-input" name="categoria" id="categoria">
        <option value="Adaptación y Mitigación al Cambio Climático">Adaptación y Mitigación al Cambio Climático</option>
        <option value="Gestión Sostenible de Recursos Hídricos">Gestión Sostenible de Recursos Hídricos</option>
        <option value="Sistemas Alimentarios Sostenibles">Sistemas Alimentarios Sostenibles</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label" for="numero">Número:</label>
      <input required placeholder="Ej. 1, 2, 3..." type="number" class="form-input" name="numero" id="numero">
    </div>
  
    <div class="form-group">
      <label class="form-label" for="parametros">Parámetros:</label>
      <textarea required placeholder="Palabra clave 1, palabra clave 2, ..." class="form-input" name="parametros" id="pclave"></textarea>
    </div>

    <div class="form-group">
      <label class="form-label" for="fuentes">Fuentes:</label>
      <textarea required placeholder="Fuente 1, fuente 2, ..." class="form-input" name="fuentes" id="fuentes"></textarea>
    </div>
    
    <button class="form-button" type="submit" name="ElBoton">Generar PDF</button> 
    
  </form>

  <!-- Botón atrás -->

  <button class="button" onclick="location.href='./init.html'">
    <div class="button-box">
      <span class="button-elem">
        <svg viewBox="0 0 46 40" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"
          ></path>
        </svg>
      </span>
      <span class="button-elem">
        <svg viewBox="0 0 46 40">
          <path
            d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3.8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"
          ></path>
        </svg>
      </span>
    </div>
    
  </button>

  <div id="response"></div>
</body>
</html>
 