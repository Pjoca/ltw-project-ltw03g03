<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

// Pagination parameters
$limit = 5; // Define how many services per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1; // Ensure page is at least 1
$offset = ($page - 1) * $limit;

// Optional filters
$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? '';
$price = isset($_GET['price']) ? floatval($_GET['price']) : null;
$delivery = isset($_GET['delivery']) ? intval($_GET['delivery']) : null;

// Build the base SQL for both counting and fetching
$baseSql = '
  FROM Services
  JOIN Users ON Services.user_id = Users.id
  JOIN Categories ON Services.category_id = Categories.id
  WHERE 1=1';

$params = [];

// Append filters if present (same for both count and fetch queries)
if ($query !== '') {
  $baseSql .= ' AND (Services.title LIKE :query OR Services.description LIKE :query OR Users.name LIKE :query)';
  $params[':query'] = '%' . $query . '%';
}

if ($category !== '') {
  $baseSql .= ' AND Categories.name = :category';
  $params[':category'] = $category;
}

if ($price !== null) {
  $baseSql .= ' AND Services.price <= :price';
  $params[':price'] = $price;
}

if ($delivery !== null) {
  $baseSql .= ' AND Services.delivery_time <= :delivery';
  $params[':delivery'] = $delivery;
}

// --- Query 1: Get Total Count of Matching Services ---
$countSql = 'SELECT COUNT(*) ' . $baseSql;
$countStmt = $db->prepare($countSql);
// Bind values for count query
foreach ($params as $key => $value) {
  $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
  $countStmt->bindValue($key, $value, $type);
}
$countStmt->execute();
$totalResults = $countStmt->fetchColumn();


// --- Query 2: Get Paginated Services ---
$servicesSql = '
  SELECT
    Services.title,
    Services.description,
    Services.price,
    Services.delivery_time,
    Services.media,
    Services.created_at,
    Categories.name AS category,
    Users.name AS poster_name
  ' . $baseSql . '
  ORDER BY Services.created_at DESC
  LIMIT :limit OFFSET :offset';

$servicesParams = $params; // Copy existing params
$servicesParams[':limit'] = $limit;
$servicesParams[':offset'] = $offset;

$servicesStmt = $db->prepare($servicesSql);
// Bind values for services query (including limit and offset)
foreach ($servicesParams as $key => $value) {
  $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
  $servicesStmt->bindValue($key, $value, $type);
}
$servicesStmt->execute();
$services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
// Return all necessary data for the frontend
echo json_encode([
    'services' => $services,
    'totalResults' => $totalResults,
    'currentPage' => $page,
    'itemsPerPage' => $limit
]);
?>
