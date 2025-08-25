<?php
session_start();
require_once 'config/database.php';
require_once 'components/header.php';
require_once 'phpmailer/PHPMailer-master/src/Exception.php';
require_once 'phpmailer/PHPMailer-master/src/PHPMailer.php';
require_once 'phpmailer/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

renderHeader('Registrarse');

function validarPasswordSegura($password) {
    return strlen($password) >= 5 && preg_match('/\d/', $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        if (!validarPasswordSegura($password)) {
            $error = "La contraseña debe tener al menos 5 caracteres y contener al menos un número."; // Mensaje de validación para la contraseña
        } else {
            $pdo = Database::getInstance()->getConnection();

            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "El correo ya está registrado."; // Verificar si el correo ya esta registrado en el sistema
            } else {
                // GUARDAR EN TEXTO PLANO (SOLO PARA ENTORNO DE PRUEBA)
                $stmt = $pdo->prepare("INSERT INTO usuarios (email, password) VALUES (?, ?)");
                $stmt->execute([$email, $password]);

                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Username = '88a913eb30ca0e';
                    $mail->Password = 'fec2bd3e0ab243';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom('you@example.com', 'SP Learning');
                    $mail->addAddress($email);
                    $mail->CharSet = 'UTF-8';
                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmación de Registro';
                    $mail->Body = 'Bienvenido a SP Learning. Ahora podes iniciar sesión con tu correo y contraseña.';

                    $mail->send();
                } catch (Exception $e) {
                    $error = "Error al enviar el correo: {$mail->ErrorInfo}";
                }

                header("Location: login.php");
                exit();
            }
        }
    } else {
        $error = "Por favor, completá todos los campos.";
    }
}
?>

<!-- Estructura HTML Formulario -->
<div class="flex items-center justify-center min-h-screen bg-cover bg-center" style="background-image: url('img/Institucional.jpg');">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mt-20">

        <h1 class="text-2xl font-bold mb-6 text-center">Registrarse</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block mb-2">Correo electrónico</label>
                <input type="email" name="email" required class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-6">
                <label class="block mb-2">Contraseña</label>
                <input type="password" name="password" required class="w-full border px-3 py-2 rounded">
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full">
                Crear cuenta
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="login.php" class="text-blue-500 hover:underline">¿Ya tenés cuenta? Inicia sesión</a>
        </div>
    </div>
</div>
