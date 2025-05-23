<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

// Optional filters
$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? '';
$price = isset($_GET['price']) ? floatval($_GET['price']) : null;
$delivery = isset($_GET['delivery']) ? intval($_GET['delivery']) : null;

$sql = '
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
  WHERE 1=1';

$params = [];


if ($query !== '') {
  $sql .= ' AND (Services.title LIKE :query OR Services.description LIKE :query OR Users.name LIKE :query)';
  $params[':query'] = '%' . $query . '%';
}

if ($category !== '') {
  $sql .= ' AND Categories.name = :category';
  $params[':category'] = $category;
}

if ($price !== null) {
  $sql .= ' AND Services.price <= :price';
  $params[':price'] = $price;
}

if ($delivery !== null) {
  $sql .= ' AND Services.delivery_time <= :delivery';
  $params[':delivery'] = $delivery;
}

$sql .= ' ORDER BY Services.created_at DESC';

$stmt = $db->prepare($sql);

// Bind values with correct type
foreach ($params as $key => $value) {
  $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
  $stmt->bindValue($key, $value, $type);
}

$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($services);