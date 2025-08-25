<?php
session_start();
require_once 'config/database.php';
require_once 'phpmailer/PHPMailer-master/src/PHPMailer.php';
require_once 'phpmailer/PHPMailer-master/src/SMTP.php';
require_once 'phpmailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar token único y fecha de expiración (1 hora)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar el token en la base de datos
        $stmt = $pdo->prepare("INSERT INTO reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires]);

        // Enviar email con el link de restablecimiento
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = '88a913eb30ca0e';
            $mail->Password   = 'fec2bd3e0ab243';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@splearning.com', 'SP Learning');
            $mail->addAddress($email);
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = "
                <p>Recibimos una solicitud para restablecer tu contraseña.</p>
                <p>Hacé clic en el siguiente enlace para establecer una nueva contraseña:</p>
                <p><a href='http://localhost/Campus/reset_form.php?token=$token'>Restablecer contraseña</a></p>
                <p>Este enlace expirará en 1 hora.</p>
            ";

            $mail->send();
            $message = "Se ha enviado un correo para continuar con el restablecimiento.";
        } catch (Exception $e) {
            $error = "No se pudo enviar el correo: " . $mail->ErrorInfo;
        }
    } else {
        $error = "No se encontró ninguna cuenta con ese correo."; // Verificación si el mail existe
    }
}
?>

<?php require_once 'components/header.php'; ?>
<?php renderHeader('Recuperar Contraseña'); ?>

<!-- Estructura HTML Formulario -->
<div class="flex items-center justify-center min-h-screen bg-cover bg-center" style="background-image: url('img/Institucional.jpg');">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Recuperar Contraseña</h2>

        <?php if (isset($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block mb-2">Correo electrónico</label>
                <input type="email" name="email" required class="w-full border px-3 py-2 rounded">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                Recuperar
            </button>
        </form>

        <div class="mt-6 text-sm text-center">
            <a href="login.php" class="text-blue-500 hover:text-blue-700">Volver al login</a>
        </div>
    </div>
</div>
