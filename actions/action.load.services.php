<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
session_start();  // Ensure session is started

$db = getDatabaseConnection();

$user_id = $_SESSION['id'] ?? null;

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3;

// Base query
$query = '
  SELECT 
    Services.title,
    Services.description,
    Services.price,
    Services.delivery_time,
    Services.media,
    Services.created_at,
    Categories.name AS category,
    Users.name AS poster_name
  FROM Services
  JOIN Users ON Services.user_id = Users.id
  JOIN Categories ON Services.category_id = Categories.id
  WHERE 1=1
';

// Exclude current user's services if logged in
if ($user_id !== null) {
    $query .= ' AND Services.user_id != :user_id';
}

$query .= '
  ORDER BY Services.created_at DESC
  LIMIT :limit OFFSET :offset
';

$stmt = $db->prepare($query);

if ($user_id !== null) {
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($services);
