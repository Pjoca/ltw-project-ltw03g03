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
$mediaPath = null;

if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileTmpPath = $_FILES['media']['tmp_name'];
    $fileName = basename($_FILES['media']['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = uniqid('img_', true) . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destination)) {
            $mediaPath = '/uploads/' . $newFileName;
        }
    }
}


if (empty($title) || empty($description) || $category_id <= 0 || $price <= 0 || $delivery_time <= 0) {
    $errors[] = 'All fields except media are required and must be valid.';
}

if (!empty($errors)) {
    $_SESSION['error_messages'] = $errors;
    header('Location: ../pages/create.service.php');
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
    ':media' => $mediaPath
]);

header('Location: ../pages/home.php');
exit;
?>
