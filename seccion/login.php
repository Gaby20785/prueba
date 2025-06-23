<?php
require_once 'config/config.php';

// Recibir los datos del formulario
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$password = $data['password'];

// Buscar al usuario en la base de datos
$stmt = $conexion->prepare("SELECT email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if (password_verify($password, $user['password'])) {
        // Autenticación correcta, devolver datos
        echo json_encode([
            'email' => $user['email'],
            'role' => $user['role']
        ]);
    } else {
        // Contraseña incorrecta
        http_response_code(401);
        echo json_encode(['error' => 'Correo o contraseña incorrectos']);
    }
} else {
    // No se encontró el usuario
    http_response_code(401);
    echo json_encode(['error' => 'Correo o contraseña incorrectos']);
}





