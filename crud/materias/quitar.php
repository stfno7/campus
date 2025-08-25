<?php
session_start();
require_once '../../config/database.php';
// Misma lógica de autenticación y autorización que asignar.php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Verificar si el usuario es administrador
    header("Location: ../../login.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_GET['id'])) { // Verificar si se proporcionó el ID del estudiante
    header('Location: ../../estudiantes.php?mensaje=ID de estudiante no proporcionado');
    exit();
}

$usuario_id = intval($_GET['id']);

// Obtener materias en las que el estudiante está inscrito
$stmt = $pdo->prepare("
    SELECT m.id, m.nombre
    FROM materias m
    INNER JOIN inscripciones i ON m.id = i.materia_id
    WHERE i.usuario_id = ?
    ORDER BY m.nombre
");
$stmt->execute([$usuario_id]);
$materias_inscritas = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materia_id = intval($_POST['materia_id']);

    // Eliminar inscripción
    $deleteStmt = $pdo->prepare("DELETE FROM inscripciones WHERE usuario_id = ? AND materia_id = ?");
    if ($deleteStmt->execute([$usuario_id, $materia_id])) {
        $mensaje = "Materia quitada correctamente.";
        // Actualizar lista después de quitar materia
        $stmt->execute([$usuario_id]);
        $materias_inscritas = $stmt->fetchAll();
    } else {
        $mensaje = "Error al quitar la materia.";
    }
}
?>

<?php require_once '../../components/header.php'; ?>
<?php renderHeader('Quitar Materia'); ?>


<!-- Esqueleto HTML -->
<div class="container mx-auto p-6 max-w-md">
    <h2 class="text-2xl font-bold mb-4">Quitar Materia al Estudiante</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <?php if (count($materias_inscritas) === 0): ?>
        <p>El estudiante no está inscrito en ninguna materia.</p>
        <a href="../../estudiantes.php" class="text-blue-600 hover:underline">Volver</a>
    <?php else: ?>
        <form method="POST" class="space-y-4 bg-white p-6 rounded shadow">
            <label for="materia_id" class="block font-semibold">Seleccione una materia para quitar:</label>
            <select id="materia_id" name="materia_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Seleccionar materia --</option>
                <?php foreach ($materias_inscritas as $materia): ?>
                    <option value="<?php echo $materia['id']; ?>"><?php echo htmlspecialchars($materia['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Quitar</button>
            <a href="../../estudiantes.php" class="ml-4 text-gray-600 hover:underline">Volver</a>
        </form>
    <?php endif; ?>
</div>
