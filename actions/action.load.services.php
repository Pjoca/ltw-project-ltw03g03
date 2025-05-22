<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
session_start();

$db = getDatabaseConnection();

$user_id = $_SESSION['user_id'] ?? null;

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3;

// Get filters
$category = $_GET['category'] ?? '';
$price = isset($_GET['price']) && $_GET['price'] !== '' ? (float)$_GET['price'] : null;
$delivery = isset($_GET['delivery']) && $_GET['delivery'] !== '' ? (int)$_GET['delivery'] : null;

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

$params = [];

// Filter out current user's services
if ($user_id !== null) {
    $query .= ' AND Services.user_id != :user_id';
    $params[':user_id'] = $user_id;
}

// Apply filters
if (!empty($category)) {
    $query .= ' AND Categories.name = :category';
    $params[':category'] = $category;
}

if ($price !== null) {
    $query .= ' AND Services.price <= :price';
    $params[':price'] = $price;
}

if ($delivery !== null) {
    $query .= ' AND Services.delivery_time <= :delivery';
    $params[':delivery'] = $delivery;
}

$query .= ' ORDER BY Services.created_at DESC LIMIT :limit OFFSET :offset';

$stmt = $db->prepare($query);

// Bind parameters
foreach ($params as $key => $value) {
    $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($key, $value, $type);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($services);
