<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombre = trim($_POST['nombre']);

    if (!empty($nuevoNombre)) {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
        if ($stmt->execute([$nuevoNombre, $_SESSION['user_id']])) {
            $_SESSION['user_name'] = $nuevoNombre; // Actualiza el nombre de la sesión activa.
            $mensaje = "Nombre actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el nombre.";
        }
    } else { // Verificación básica, no se puede tener un nombre vácio (Una vez que se agrego un nombre, ya no puede quedar vacío como cuando se registró)
        $mensaje = "El campo de nombre no puede estar vacío.";
    }
}

// Obtener nombre actual del usuario
$stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch();

renderHeader("Configuración");
?>

<!-- Contenido de la página configuración, HTML -->
<div class="container mx-auto p-6 max-w-md">
    <h2 class="text-2xl font-bold mb-4"></h2>

    <?php if (!empty($mensaje)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded shadow space-y-4">
        <div>
            <label class="block font-semibold mb-2" for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre"
                   value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>"
                   class="w-full border px-3 py-2 rounded" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Guardar Cambios
        </button>
        <a href="perfil.php" class="ml-4 text-gray-600 hover:underline">Volver a perfil</a>
    </form>
</div>

</body>
</html>
