<?php
session_start();
require_once 'config/database.php';

$pdo = Database::getInstance()->getConnection();

function validarPasswordSegura($password) {
    return strlen($password) >= 5 && preg_match('/\d/', $password);
}

function mostrarMensaje($mensaje, $tipo = 'error') {
    $color = $tipo === 'success' ? 'green' : 'red';
    echo "<div class='bg-{$color}-100 text-{$color}-800 p-4 rounded mt-10 text-center w-full max-w-md mx-auto'>{$mensaje}</div>";
    exit;
}
// Procesar el formulario de restablecimiento de contraseña, lo que se hace acá es cambiar la contraseña del usuario teniendo en cuenta las condicionales
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($password) || empty($confirm)) {
        mostrarMensaje("Todos los campos son obligatorios.");
    }

    if ($password !== $confirm) {
        mostrarMensaje("Las contraseñas no coinciden.");
    }

    if (!validarPasswordSegura($password)) {
        mostrarMensaje("La contraseña debe tener al menos 5 caracteres y contener al menos un número.");
    }

    $stmt = $pdo->prepare("SELECT user_id FROM reset_tokens WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $record = $stmt->fetch();

    if (!$record) { // Si el token expiro (más de una hora pasó) o no es válido
        mostrarMensaje("Token inválido o expirado.");
    }

    $usuarioId = $record['user_id'];
    $passwordHash = $password;

    $update = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
    $update->execute([$passwordHash, $usuarioId]);

    $delete = $pdo->prepare("DELETE FROM reset_tokens WHERE token = ?");
    $delete->execute([$token]);

    mostrarMensaje("Contraseña actualizada correctamente. <a href='login.php' class='text-blue-600 underline'>Iniciar sesión</a>", 'success');
} else {
    mostrarMensaje("Acceso inválido.");
}
?>
