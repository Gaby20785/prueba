<?php
require_once('config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Se verifica si el email ya existe
    $query_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conexion->prepare($query_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "Error: El correo electrónico ya está registrado.";
        exit();
    }
    $stmt_check->close();

    // se encripta contrasena
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sss", $email, $password_hashed, $role);
    
    if ($stmt->execute()) {
        header("Location: administrador.php");
        exit();
    } else {
        echo "Error al crear usuario.";
    }
}
?>

<!doctype html>
<html lang="es">
<head>
    <link rel="icon" href="media/fia.png" type="image/png">
    <meta charset="utf-8">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Crear nuevo usuario</h1>
    <form method="post">
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required maxlength="255">
        </div>
        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="text" name="password" class="form-control" required minlength="6" maxlength="255" placeholder="Mínimo 6 caracteres">
        </div>
        <div class="mb-3">
            <label>Rol:</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="especialista">Especialista</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Crear</button>
        <a href="administrador.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
