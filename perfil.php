<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = Database::getInstance()->getConnection();
$stmt = $pdo->prepare("SELECT nombre, email, rol FROM usuarios WHERE id = ?"); // Seleccionar los campos
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch();

renderHeader("Mi Perfil");
?>
<!-- Formulario de perfil, HTML -->
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4"></h2>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre'] ?? 'No asignado'); ?></p> <!-- Recordar que el registro viene con un default, por eso el null coalesce -->
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['rol']); ?></p>
        <a href="configuracion.php" class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Editar Nombre</a>
    </div>
</div>
</body>
</html>
