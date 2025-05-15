<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

$stmt = $db->prepare('
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
  ORDER BY Services.created_at DESC
');
$stmt->execute();
$services = $stmt->fetchAll();


draw_header('HomePage');
draw_home($services); 
draw_footer();
?>
