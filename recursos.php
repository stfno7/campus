<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php';
require_once 'components/footer.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = Database::getInstance()->getConnection(); // Conexión a la base de datos
$stmt = $pdo->prepare("SELECT m.nombre, r.titulo, r.url
                       FROM inscripciones i
                       JOIN materias m ON i.materia_id = m.id
                       JOIN recursos r ON r.materia_id = m.id
                       WHERE i.usuario_id = ?");
$stmt->execute([$_SESSION['user_id']]); // Ejecutar la consulta para obtener los datos del usuario. Uso de alias
$recursos = $stmt->fetchAll();

renderHeader("Recursos");
?>
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4"></h2>
    <div class="bg-white p-6 rounded shadow space-y-4">
        <?php if (count($recursos) > 0): ?>
            <?php foreach ($recursos as $recurso): ?>
                <div class="border p-4 rounded">
                    <h3 class="font-semibold"><?php echo htmlspecialchars($recurso['nombre']); ?> - <?php echo htmlspecialchars($recurso['titulo']); ?></h3>
                    <p>
                        <?php if (filter_var($recurso['url'], FILTER_VALIDATE_URL)): ?>
                            <a href="<?php echo htmlspecialchars($recurso['url']); ?>" class="text-blue-600 underline" target="_blank">Abrir enlace</a>
                        <?php else: ?>
                            <a href="recursos/<?php echo htmlspecialchars($recurso['url']); ?>" class="text-blue-600 underline" download>Descargar archivo</a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay recursos disponibles.</p>
        <?php endif; ?>
    </div>
</div>
</body></html>
<?php // Renderizar el footer
renderFooter("Dashboard");
?>