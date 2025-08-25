<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Condición solo para administrador
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ver.php");
    exit();
}

$pdo = Database::getInstance()->getConnection();
$id = $_GET['id'];

// Eliminar el usuario y sus inscripciones relacionadas
$stmt = $pdo->prepare("DELETE FROM inscripciones WHERE usuario_id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->execute([$id]);

header("Location: /Campus/estudiantes.php");
exit();
?>