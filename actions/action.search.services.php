<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? '';
$price = isset($_GET['price']) ? floatval($_GET['price']) : null;
$delivery = isset($_GET['delivery']) ? intval($_GET['delivery']) : null;

$baseSql = '
  FROM Services s
  JOIN Users u ON s.user_id = u.id
  JOIN Categories c ON s.category_id = c.id
  WHERE 1=1';

$params = [];

if ($query !== '') {
  $tokens = preg_split('/\s+/', trim($query));
  $ftsQueryParts = [];
  
  foreach ($tokens as $token) {
    $escaped = str_replace('"', '""', $token);
    
    // 1. Exact match (boosted)
    $ftsQueryParts[] = '"' . $escaped . '"^5';
    
    // 2. Prefix match
    $ftsQueryParts[] = '"' . $escaped . '"*^2';
    
    // 3. Alternative spellings (truncated match)
    if (strlen($token) > 3) {
      $ftsQueryParts[] = '"' . substr($escaped, 0, -1) . '"*';
    }
  }

  $ftsQuery = implode(' OR ', $ftsQueryParts);
  
  $baseSql .= ' AND s.id IN (
    SELECT rowid FROM ServicesFTS WHERE ServicesFTS MATCH :query
  )';
  $params[':query'] = $ftsQuery;
  
  // Fallback to simple LIKE if no FTS results found
  $countStmt = $db->prepare('SELECT COUNT(*) ' . $baseSql);
  foreach ($params as $key => $value) {
    $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $countStmt->bindValue($key, $value, $type);
  }
  $countStmt->execute();
  
  if ($countStmt->fetchColumn() == 0) {
    $baseSql .= ' OR s.title LIKE :fallback 
                OR s.description LIKE :fallback 
                OR u.name LIKE :fallback';
    $params[':fallback'] = '%' . $query . '%';
  }
}

// Rest of your filters (category, price, delivery)
if ($category !== '') {
  $baseSql .= ' AND c.name = :category';
  $params[':category'] = $category;
}

if ($price !== null) {
  $baseSql .= ' AND s.price <= :price';
  $params[':price'] = $price;
}

if ($delivery !== null) {
  $baseSql .= ' AND s.delivery_time <= :delivery';
  $params[':delivery'] = $delivery;
}

// Count query
$countSql = 'SELECT COUNT(*) ' . $baseSql;
$countStmt = $db->prepare($countSql);

foreach ($params as $key => $value) {
  $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
  $countStmt->bindValue($key, $value, $type);
}
$countStmt->execute();
$totalResults = $countStmt->fetchColumn();

// Main query
$servicesSql = '
  SELECT
    s.id,         
    s.user_id,          
    s.title,
    s.description,
    s.price,
    s.delivery_time,
    s.media,
    s.created_at,
    c.name AS category,
    u.name AS poster_name
  ' . $baseSql;

// Add ordering
if ($query !== '') {
  $servicesSql .= ' ORDER BY 
    CASE 
      WHEN s.title LIKE :exact THEN 0
      WHEN s.title LIKE :start THEN 1
      WHEN s.title LIKE :contains THEN 2
      ELSE 3
    END,
    s.created_at DESC';
    
  $params[':exact'] = $query;
  $params[':start'] = $query . '%';
  $params[':contains'] = '%' . $query . '%';
} else {
  $servicesSql .= ' ORDER BY s.created_at DESC';
}

$servicesSql .= ' LIMIT :limit OFFSET :offset';

$servicesParams = $params;
$servicesParams[':limit'] = $limit;
$servicesParams[':offset'] = $offset;

$servicesStmt = $db->prepare($servicesSql);

foreach ($servicesParams as $key => $value) {
  $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
  $servicesStmt->bindValue($key, $value, $type);
}

try {
  $servicesStmt->execute();
  $services = $servicesStmt->fetchAll(PDO::FETCH_ASSOC);
  
  header('Content-Type: application/json');
  echo json_encode([
      'services' => $services,
      'totalResults' => $totalResults,
      'currentPage' => $page,
      'itemsPerPage' => $limit
  ]);
} catch (PDOException $e) {
  error_log('Search error: ' . $e->getMessage());
  
  header('Content-Type: application/json');
  http_response_code(500);
  echo json_encode([
      'error' => 'Search failed',
      'services' => [],
      'totalResults' => 0,
      'currentPage' => $page,
      'itemsPerPage' => $limit
  ]);
}
?>
