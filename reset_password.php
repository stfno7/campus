<?php
// reset_password.php
session_start();
require_once 'config/database.php';
require_once 'phpmailer/PHPMailer-master/src/PHPMailer.php';
require_once 'phpmailer/PHPMailer-master/src/SMTP.php';
require_once 'phpmailer/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $pdo->prepare("INSERT INTO reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $token, $expira]);

            $enlace = "http://localhost/Campus/reset_form.php?token=$token";

            // Enviar el correo
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Username = '88a913eb30ca0e';
                $mail->Password = 'fec2bd3e0ab243';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('no-reply@campus.com', 'Campus Educativo');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperar contraseña';
                $mail->Body    = "<p>Hacé clic en el siguiente enlace para restablecer tu contraseña:</p><p><a href='$enlace'>$enlace</a></p>";

                $mail->send();
                $mensaje = "Se envió un enlace de recuperación a tu correo.";
            } catch (Exception $e) {
                $error = "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            $error = "No se encontró una cuenta con ese correo.";
        }
    } else {
        $error = "Ingresá un correo válido.";
    }
}
?>

<!-- Formulario HTML -->
<?php require_once 'components/header.php'; renderHeader('Recuperar Contraseña'); ?>
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Recuperar Contraseña</h2>

        <?php if (isset($mensaje)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded"> <?php echo $mensaje; ?> </div>
        <?php elseif (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded"> <?php echo $error; ?> </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" required class="w-full mb-4 p-2 border rounded">
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Enviar enlace</button>
        </form>
        <div class="mt-4 text-center">
            <a href="login.php" class="text-blue-500 hover:underline">Volver al login</a>
        </div>
    </div>
</div>
