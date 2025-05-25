<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/services.tpl.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

draw_header('Edit Service');

$title = $_GET['title'] ?? '';
if (!$title) {
  die('No service specified');
}

$db = getDatabaseConnection();
$stmt = $db->prepare('SELECT * FROM Services WHERE title = ? AND user_id = ?');
$stmt->execute([$title, $_SESSION['user_id']]);
$service = $stmt->fetch();

$service['service_id'] = $service['id'];
if (!$service) {
  die('Service not found or not yours');
}

draw_edit_service($service);

draw_footer();

?>
