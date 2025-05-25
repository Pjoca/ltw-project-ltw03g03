<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

// Apenas admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['category_name'])) {
    $db = getDatabaseConnection();

    $stmt = $db->prepare('INSERT OR IGNORE INTO Categories (name) VALUES (?)');
    $stmt->execute([trim($_POST['category_name'])]);
}

header('Location: ../pages/admin.php');
exit();
