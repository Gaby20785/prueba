<?php 
require_once('config/config.php');

$query = "SELECT * FROM users";
$result = $conexion->query($query);
?>

<!doctype html>
<html lang="es">
<head>
  <link rel="icon" href="media/fia.png" type="image/png">
  <meta charset="utf-8">
  <title>Administración de Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Usuarios registrados</h1>
      <a href="crear_usuario.php" class="btn btn-success">Crear nuevo usuario</a>
      <div class="col-auto">
                <a href="init.html" class="btn btn-secondary">Volver</a>
      </div>
    </div>

    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['role']) ?></td>
          <td>
            <a href="editar_usuario.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar_usuario.php?email=<?= $row['email'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

