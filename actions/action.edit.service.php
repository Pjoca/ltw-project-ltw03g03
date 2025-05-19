<?php
session_start();
require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
  header('Location: /login.php');
  exit();
}

$db = getDatabaseConnection();
$stmt = $db->prepare('
  UPDATE Services
  SET title = ?, description = ?, price = ?, delivery_time = ?
  WHERE title = ? AND user_id = ?
');

$stmt->execute([
  $_POST['title'],
  $_POST['description'],
  $_POST['price'],
  $_POST['delivery_time'],
  $_POST['original_title'],
  $_SESSION['user_id']
]);

header('Location: /../pages/my.services.php');
