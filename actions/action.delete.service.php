<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$title = $data['title'] ?? '';

if (!$title) {
    echo json_encode(['success' => false, 'error' => 'No title provided']);
    exit();
}

$db = getDatabaseConnection();
$stmt = $db->prepare('DELETE FROM Services WHERE title = ? AND user_id = ?');
$stmt->execute([$title, $_SESSION['user_id']]);

echo json_encode(['success' => true]);
