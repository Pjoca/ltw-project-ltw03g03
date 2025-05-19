<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$db = getDatabaseConnection();
$user_id = $_SESSION['user_id'];
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3;

$stmt = $db->prepare('
  SELECT 
    Services.title,
    Services.description,
    Services.price,
    Services.delivery_time,
    Services.media,
    Services.created_at,
    Categories.name AS category
  FROM Services
  JOIN Categories ON Services.category_id = Categories.id
  WHERE Services.user_id = ?
  ORDER BY Services.created_at DESC
  LIMIT :limit OFFSET :offset
');

$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($services);
