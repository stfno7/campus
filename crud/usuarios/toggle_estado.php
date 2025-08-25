<?php
require_once '../../config/database.php';
session_start();
header('Content-Type: application/json');

if ($_SESSION['user_role'] !== 'admin') { 
    echo json_encode(['success' => false]);
    exit();
}

if (isset($_POST['id'], $_POST['estado'])) { // Chequea si se recibieron los parámetros necesarios
    $id = (int) $_POST['id'];
    $estado = (int) $_POST['estado'];
    $nuevoEstado = $estado === 1 ? 0 : 1; // Invierte el estado

    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
    $stmt->execute([$nuevoEstado, $id]);

    echo json_encode(['success' => true, 'nuevo_estado' => $nuevoEstado]);
    exit();
}

echo json_encode(['success' => false]);
