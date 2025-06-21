<?php
require_once('config/config.php');

$id = $_GET['id'];
$stmt = $conexion->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update = $conexion->prepare("UPDATE users SET email=?, password=?, role=? WHERE id=?");
        $update->bind_param("sssi", $email, $hashed_password, $role, $id);
    } else {
        $update = $conexion->prepare("UPDATE users SET email=?, role=? WHERE id=?");
        $update->bind_param("ssi", $email, $role, $id);
    }

    if ($update->execute()) {
        header("Location: administrador.php");
        exit();
    } else {
        echo "Error al actualizar usuario: " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="media/fia.png" type="image/png">
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Editar usuario</h1>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="especialista" <?= $user['role'] == 'especialista' ? 'selected' : '' ?>>Especialista</option>
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Usuario</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="administrador.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>