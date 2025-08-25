<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php';
require_once 'components/footer.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Obtener materias desde la bd. Usuario = estudiante = logueado, busca sus materias
$pdo = Database::getInstance()->getConnection();

try {
    $stmt = $pdo->query("SELECT * FROM materias ORDER BY semestre, nombre");
    $materias = $stmt->fetchAll();

    if ($_SESSION['user_role'] == 'estudiante') {
        $stmt = $pdo->prepare("SELECT materia_id FROM inscripciones WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $inscripciones = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}

// Renderizar el header
renderHeader("Dashboard");
?>

<!-- Contenido general del dashboard -->
<main class="flex-grow container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6"></h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($materias as $materia): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($materia['nombre']); ?></h3>
            <p class="text-gray-600 mb-2">Código: <?php echo htmlspecialchars($materia['codigo']); ?></p>
            <p class="text-gray-600 mb-4">Semestre: <?php echo htmlspecialchars($materia['semestre']); ?></p>
            
            <div class="mt-4 flex justify-between items-center">
                <a href="materia.php?id=<?php echo $materia['id']; ?>" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Ingresar
                </a>
                
                <?php if ($_SESSION['user_role'] == 'estudiante'): ?> <!-- Condicional para verificar si el usuario es estudiante y si se encuentra inscrito en la materia -->
                    <?php if (isset($inscripciones) && in_array($materia['id'], $inscripciones)): ?>
                        <span class="inline-block bg-green-500 text-white px-4 py-2 rounded">Inscrito</span>
                    <?php else: ?> <!-- De no estar inscrito, se muestra el botón de inscripción -->
                        <form method="POST" action="inscribir_materia.php" class="inline">
                            <input type="hidden" name="materia_id" value="<?php echo $materia['id']; ?>">
                            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                Inscribir
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php // Renderizar el footer
renderFooter("Dashboard");
?>

</body>
</html>
