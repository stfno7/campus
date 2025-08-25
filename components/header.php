<?php
// components/header.php
function renderHeader($pageTitle) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - SP Learning</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet"> <!-- Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome Icons -->
    <script defer> // Script para manejar el menú desplegable del perfil
        document.addEventListener("DOMContentLoaded", function() {
            const profileButton = document.getElementById("profile-button");
            const dropdownMenu = document.getElementById("dropdown-menu");

            profileButton.addEventListener("click", function() {
                dropdownMenu.classList.toggle("hidden");
            });

            // Cerrar el menú clic por fuera
            document.addEventListener("click", function(event) {
                if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add("hidden");
                }
            });
        });
    </script>
    <style> /** Background image general **/
        body {
            background-image: url('/Campus/img/istockphoto-1419059397-612x612.jpg');
            background-size: auto;
            background-position: center;
        }
    </style>
</head>
<!-- Header para el campus -->
<body class="bg-gray-100 flex flex-col min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div class="flex items-center py-4">
                        <img src="/Campus/img/logo.png" alt="SP Learning Logo" class="h-8 w-auto mr-2">
                        <span class="font-bold text-xl text-blue-600">SP Learning</span>
                    </div>
                    <?php if(isset($_SESSION['user_id'])): ?> <!-- Condicional para mostrar el header cuando se loguea -->
                        <div class="flex items-center space-x-4">
                            <a href="/Campus/dashboard.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">
                                <i class="fas fa-home mr-2"></i>Inicio
                            </a>
                            <a href="/Campus/calendario.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">
                                <i class="fas fa-calendar mr-2"></i>Calendario
                            </a>
                            <a href="/Campus/recursos.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">
                                <i class="fas fa-book mr-2"></i>Recursos
                            </a>
                            <?php if($_SESSION['user_role'] == 'estudiante'): ?> <!-- Condicional para mostrar solo a estudiante -->
                                <a href="/Campus/tareas.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">
                                    <i class="fas fa-tasks mr-2"></i>Tareas
                                </a>
                            <?php endif; ?>
                            <?php if($_SESSION['user_role'] == 'admin'): ?> <!-- Condicional para mostrar solo a admin -->
                                <a href="/Campus/estudiantes.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">
                                    <i class="fas fa-users mr-2"></i>Estudiantes
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-3"> <!-- Header para el login -->
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="/Campus/login.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-blue-500 transition duration-300">
                            Usted no se ha identificado. (Acceder)
                        </a>
                    <?php else: ?> <!-- Header para el dashboard; menú desplegable -->
                        <div class="relative">
                            <button id="profile-button" class="flex items-center space-x-2 text-gray-700 hover:text-blue-500 transition duration-300">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </button>
                            <div id="dropdown-menu" class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-lg shadow-xl hidden">
                                <a href="perfil.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-id-card mr-2"></i>Mi Perfil
                                </a>
                                <a href="configuracion.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Configuración
                                </a>
                                <hr class="my-2">
                                <a href="/Campus/logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
<?php
}
?>
