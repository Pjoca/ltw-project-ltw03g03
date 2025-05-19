<?php
session_start();
require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
  header('Location: /login.php');
  exit();
}

$db = getDatabaseConnection();


$mediaPath = null;

// Handle file upload if a new image is provided
if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/../uploads/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $tmpFile = $_FILES['media']['tmp_name'];
  $fileName = basename($_FILES['media']['name']);
  $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
  $allowed = ['jpg', 'jpeg', 'png', 'gif'];

  if (in_array($ext, $allowed)) {
    $newName = uniqid('img_', true) . '.' . $ext;
    $destination = $uploadDir . $newName;

    if (move_uploaded_file($tmpFile, $destination)) {
      $mediaPath = '/uploads/' . $newName;
    }
  }
}

// Update with new media
if ($mediaPath !== null) {
  $stmt = $db->prepare('
    UPDATE Services
    SET title = ?, description = ?, category_id = ?, price = ?, delivery_time = ?, media = ?
    WHERE title = ? AND user_id = ?
  ');

  $stmt->execute([
    $_POST['title'],
    $_POST['description'],
    $_POST['category_id'],
    $_POST['price'],
    $_POST['delivery_time'],
    $mediaPath,
    $_POST['original_title'],
    $_SESSION['user_id']
  ]);
} 
// Update without new media
else {
  $stmt = $db->prepare('
    UPDATE Services
    SET title = ?, description = ?, category_id = ?, price = ?, delivery_time = ?
    WHERE title = ? AND user_id = ?
  ');

  $stmt->execute([
    $_POST['title'],
    $_POST['description'],
    $_POST['category_id'],
    $_POST['price'],
    $_POST['delivery_time'],
    $_POST['original_title'],
    $_SESSION['user_id']
  ]);
}

header('Location: /../pages/my.services.php');
