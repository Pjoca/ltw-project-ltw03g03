<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$errors = [];

// Get and sanitize form inputs
$category_id = (int) ($_POST['category_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$delivery_time = (int) ($_POST['delivery_time'] ?? 0);
$media = trim($_POST['media'] ?? null);

if (empty($title) || empty($description) || $category_id <= 0 || $price <= 0 || $delivery_time <= 0) {
    $errors[] = 'All fields except media are required and must be valid.';
}

if (!empty($errors)) {
    $_SESSION['error_messages'] = $errors;
    header('Location: ../pages/create_service.php');
    exit;
}

// Insert into Services
$stmt = $db->prepare('
    INSERT INTO Services (user_id, category_id, title, description, price, delivery_time, media)
    VALUES (:user_id, :category_id, :title, :description, :price, :delivery_time, :media)
');

$stmt->execute([
    ':user_id' => $_SESSION['user_id'],
    ':category_id' => $category_id,
    ':title' => $title,
    ':description' => $description,
    ':price' => $price,
    ':delivery_time' => $delivery_time,
    ':media' => $media
]);

header('Location: ../pages/home.php');
exit;
?>
