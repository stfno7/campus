<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php'; 
require_once 'components/footer.php';

if(!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();

try {
    $stmt = $pdo->prepare("SELECT * FROM materias WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $materia = $stmt->fetch();

    if (!$materia) {
        header("Location: dashboard.php");
        exit();
    }
} catch(PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}
?>

<!-- Esqueleto HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($materia['nombre']); ?> - Campus Educativo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleContenido(claseId) {
            var content = document.getElementById("contenido-clase-" + claseId);
            content.style.display = content.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <?php renderHeader("Materia: " . htmlspecialchars($materia['nombre'])); ?>


    <!-- Contenido principal // Materias -->
    <main class="flex-grow container mx-auto px-4 py-8 flex">
        <div class="bg-white rounded-lg shadow-md p-6 flex-grow">
            <div class="mb-6">
                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($materia['nombre']); ?></h1>
                <p class="text-gray-600">Código: <?php echo htmlspecialchars($materia['codigo']); ?></p>
                <p class="text-gray-600">Semestre: <?php echo htmlspecialchars($materia['semestre']); ?></p>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Descripción</h2>
                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($materia['descripcion'])); ?></p>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Contenido del Curso</h2>
                <div class="prose max-w-none">
                    <?php echo nl2br(htmlspecialchars($materia['contenido'])); ?>
                </div>
            </div>

            <div class="mt-6">
                <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Volver al Inicio
                </a>
            </div>
        </div>

        <!-- Lista de Clases a la derecha -->
        <div class="bg-white rounded-lg shadow-md p-6 ml-8 w-1/3">
            <h2 class="text-xl font-semibold mb-4">Clases</h2>
            <ul class="space-y-4">
                <!-- Clase 1 EJ -->
                <li>
                    <button onclick="toggleContenido(1)" class="w-full text-left font-semibold text-blue-500">
                        Clase 1: Introducción al Curso
                    </button>
                    <div id="contenido-clase-1" class="mt-2 text-gray-600" style="display:none;">
                        <p><strong>Videos:</strong> <a href="https://www.youtube.com/watch?v=BcGAPkjt_IE&ab_channel=midulive" class="text-blue-500" target="_blank">Ver video de introducción</a></p>
                        <p><strong>Documentos:</strong> <a href="https://www.w3schools.com/php/" class="text-blue-500" target="_blank">Descargar PDF</a></p>
                    </div>
                </li>
                <!-- Clase 2 -->
                <li>
                    <button onclick="toggleContenido(2)" class="w-full text-left font-semibold text-blue-500">
                        Clase 2: Fundamentos de Programación
                    </button>
                    <div id="contenido-clase-2" class="mt-2 text-gray-600" style="display:none;">
                        <p><strong>Videos:</strong> <a href="https://www.youtube.com/watch?v=BcGAPkjt_IE&ab_channel=midulive" class="text-blue-500" target="_blank">Ver video de fundamentos</a></p>
                        <p><strong>Documentos:</strong> <a href="https://www.w3schools.com/php/php_syntax.asp" class="text-blue-500" target="_blank">Descargar PDF</a></p>
                    </div>
                </li>
            </ul>
        </div>
    </main>

<?php // Renderizar el footer
renderFooter("Dashboard");
?>
</body>
</html>
