<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Verificar si el usuario es administrador
    header("Location: ../../login.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_GET['id'])) { // Verificar si proporcionó un ID
    header("Location: ver.php");
    exit();
}

$id = $_GET['id'];
$mensaje = "";
$error = "";

// Obtener datos del estudiante
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ? AND rol = 'estudiante'");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) { // Verificar si el estudiante existe
    $error = "Estudiante no encontrado.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);

    if ($nombre && $email) { // Validar que los campos no estén vacíos
        // Actualizar datos del estudiante
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $stmt->execute([$nombre, $email, $id]);
        $mensaje = "Datos actualizados correctamente.";
        // Refrescar datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<?php require_once '../../components/header.php'; ?>
<?php renderHeader('Editar Estudiante'); ?>

<!-- Esqueleto HTML -->
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Editar Estudiante</h2>

    <?php if ($mensaje): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 p-2 rounded mb-4">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 p-2 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" class="w-full mt-1 border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" class="w-full mt-1 border px-3 py-2 rounded">
        </div>

        <div class="flex justify-between">
            <a href="../../estudiantes.php" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Volver</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
