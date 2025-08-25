<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Buscar al usuario por su email
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['activo'] == 0) {
                $error = "Tu cuenta está deshabilitada. Contactá con un administrador.";
            } elseif ($password == $user['password']) {
                // Contraseña correcta y cuenta activa
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['user_name'] = $user['nombre'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Email o contraseña incorrectos";
            }
        } else {
            $error = "Email o contraseña incorrectos";
        }
    } catch (PDOException $e) {
        $error = "Error al intentar iniciar sesión: " . $e->getMessage();
    }
}

// Renderizar header
renderHeader('Iniciar Sesión');
?>


<!-- Contenido general de la página. Form para loguear -->
<div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('img/Institucional.jpg');">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <div class="text-center mb-8">
            <img src="img/proyectosolologo.png" alt="SP Learning Logo" class="h-16 w-auto mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-800">SP Learning</h2>
            <p class="text-gray-600">Campus Virtual</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?> <!-- Error al iniciar sesión mensaje -->
            </div>
        <?php endif; ?>
        
        <?php if (isset($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?> 
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="email" type="email" name="email" required>
            </div>
            
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Contraseña
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="password" type="password" name="password" required>
            </div>

            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="mr-2">
                    <label for="remember">Recordarme</label>
                </div> 
                <a href="registro.php" class="text-blue-500 hover:text-blue-700">Registrate</a><br> <!-- Registrar nuevo usuario. ABM gestión de usuarios, sprint 1 -->
                <a href="recuperar.php" class="text-blue-500 hover:text-blue-700">Olvidé mi contraseña</a> <!-- Recuperar contraseña -->
            </div>

            <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" type="submit">
            Iniciar Sesión
            </button>
        </form>
        
        <!-- Credenciales de prueba -->
        <div class="mt-6 text-sm text-center text-gray-600">
            <p>Credenciales de prueba:</p>
            <p>Admin: admin@admin.com / admin123</p>
            <p>Estudiante: estudiante@demo.com / 123456</p>
        </div>
    </div>
</div>
