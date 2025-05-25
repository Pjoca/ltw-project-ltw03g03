<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

// Verifica se utilizador atual é admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $db = getDatabaseConnection();
    $stmt = $db->prepare('UPDATE Users SET role = "admin" WHERE id = ?');
    $stmt->execute([$_POST['user_id']]);
}

header('Location: ../pages/admin.php');
exit();
