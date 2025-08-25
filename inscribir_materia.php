<?php
session_start();
require_once 'config/database.php';

// Condicional para verificar si el usuario está logueado y es estudiante
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'estudiante') {
    header("Location: login.php");
    exit();
}

// Registrar estudiante en una materia con las condiciones: Estar logueado, es estudiante y la materia no está inscrita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['materia_id'])) {
    $pdo = Database::getInstance()->getConnection();
    
    try {
        // Verificar si ya está inscrito
        $stmt = $pdo->prepare("SELECT id FROM inscripciones WHERE usuario_id = ? AND materia_id = ?");
        $stmt->execute([$_SESSION['user_id'], $_POST['materia_id']]);
        
        if ($stmt->rowCount() == 0) {
            // Si no está inscrito, realizar la inscripción
            $stmt = $pdo->prepare("INSERT INTO inscripciones (usuario_id, materia_id) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $_POST['materia_id']]);
        }
        
        header("Location: dashboard.php");
        exit();
    } catch(PDOException $e) {
        die("Error al inscribir: " . $e->getMessage());
    }
}

// Si no hay POST, redirigir al dashboard
header("Location: dashboard.php");
exit();
?>