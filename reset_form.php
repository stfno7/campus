<?php
// reset_form.php - Formulario que llega al email para restablecer la contraseña
session_start();
require_once 'config/database.php';

$pdo = Database::getInstance()->getConnection();
$token = $_GET['token'] ?? '';

// Verificar si el token es válido
$stmt = $pdo->prepare("SELECT * FROM reset_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$record = $stmt->fetch();

if (!$record) {
    echo "<h2>Token inválido o expirado.</h2>";
    exit();
}
?>

<!-- Esqueleto HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña - SP Learning</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Restablecer Contraseña</h2>
        <form method="POST" action="reset_process.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="mb-4">
                <label>Nueva contraseña</label>
                <input type="password" name="password" required class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label>Confirmar contraseña</label>
                <input type="password" name="confirm_password" required class="w-full border px-3 py-2 rounded">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Cambiar Contraseña</button>
        </form>
    </div>
</body>
</html>
