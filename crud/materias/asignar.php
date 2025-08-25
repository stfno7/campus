<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Verificar si el usuario es administrador
    header("Location: ../../login.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_GET['id'])) { // Verificar si se proporcionó el ID del estudiante
    header('Location: ../usuarios/ver.php?mensaje=ID de estudiante no proporcionado');
    exit();
}

$usuario_id = intval($_GET['id']);

// Obtener todas las materias disponibles
$stmt = $pdo->prepare("SELECT id, nombre FROM materias ORDER BY nombre");
$stmt->execute();
$materias = $stmt->fetchAll(); // Obtener la lista de materias

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materia_id = intval($_POST['materia_id']);

    // Verificar si ya está inscripto en esa materia
    $checkStmt = $pdo->prepare("SELECT * FROM inscripciones WHERE usuario_id = ? AND materia_id = ?");
    $checkStmt->execute([$usuario_id, $materia_id]);
    $existe = $checkStmt->fetch();

    if ($existe) {
        $mensaje = "El estudiante ya está inscrito en esa materia.";
    } else {
        $insertStmt = $pdo->prepare("INSERT INTO inscripciones (usuario_id, materia_id, fecha_inscripcion) VALUES (?, ?, NOW())");
        if ($insertStmt->execute([$usuario_id, $materia_id])) {
            $mensaje = "Materia asignada correctamente.";
        } else {
            $mensaje = "Error al asignar la materia.";
        }
    }
}
?>

<?php require_once '../../components/header.php'; ?>
<?php renderHeader('Asignar Materia'); ?>

<!-- Esqueleto HTML -->
<div class="container mx-auto p-6 max-w-md">
    <h2 class="text-2xl font-bold mb-4">Asignar Materia al Estudiante</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4 bg-white p-6 rounded shadow">
        <label for="materia_id" class="block font-semibold">Seleccione una materia:</label>
        <select id="materia_id" name="materia_id" required class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="">-- Seleccionar materia --</option>
            <?php foreach ($materias as $materia): ?>
                <option value="<?php echo $materia['id']; ?>"><?php echo htmlspecialchars($materia['nombre']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Asignar</button>
        <a href="../../estudiantes.php" class="ml-4 text-gray-600 hover:underline">Volver</a>
    </form>
</div>
