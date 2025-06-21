<?php
    require_once('config/config.php');

    if (isset($_GET['ID_boletin'])) {
        $id_boletin = $_GET['ID_boletin'];

        $query = "SELECT contenido FROM boletines WHERE ID_boletin = $id_boletin";
        $result = $conexion->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $contenido = $row['contenido'];

        } else {
            echo "<script>alert('Boletín no encontrado.'); window.location.href='especialista.php';</script>";
        }
    } else {
        echo "<script>alert('ID del boletín no especificado.'); window.location.href='especialista.php';</script>";
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="media/fia.png" type="image/png">
    <meta charset="UTF-8">
    <script defer src="./editor.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles-init.css"> 
    <script src="./tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body onload="onLoad()">
    <header class="header">
        <img src="css/imagen/logo_fia.png" alt="Logo" class="logo">
        <h1>Editor de boletines</h1>
    </header>
    <div class="contenedor" style="width:1000px; margin:0 auto;margin-top: 50px;">
        <form method="POST" action="guardar.php">
            <input type="hidden" name="id_boletin" value="<?= htmlspecialchars($id_boletin) ?>">
            <textarea id="editor" name="contenido"><?= htmlspecialchars($contenido) ?></textarea>
            <br>
            <button type="submit">Guardar cambios</button>
            <button type="button" onclick="exportarPDF()">Exportar a PDF</button>
        </form>
    </div>

</body>
</html>