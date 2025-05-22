<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to send a message.");
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');

$proposed_price = isset($_POST['proposed_price']) && is_numeric($_POST['proposed_price']) ? floatval($_POST['proposed_price']) : null;
$delivery_days = isset($_POST['delivery_days']) && is_numeric($_POST['delivery_days']) ? intval($_POST['delivery_days']) : null;
$service_id = isset($_POST['service_id']) && is_numeric($_POST['service_id']) ? intval($_POST['service_id']) : null;

if (!$receiver_id || $message === '') {
    die("Missing required fields.");
}

$db = getDatabaseConnection();

$stmt = $db->prepare('
  INSERT INTO Messages (sender_id, receiver_id, message, service_id, proposed_price, delivery_days)
  VALUES (?, ?, ?, ?, ?, ?)
');

$stmt->execute([
    $sender_id,
    $receiver_id,
    $message,
    $service_id,
    $proposed_price,
    $delivery_days
]);

header("Location: /../pages/messages.php");
exit();
