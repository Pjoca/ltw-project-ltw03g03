<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

$stmt = $db->prepare('
  SELECT Services.description, Services.created_at, Users.name AS poster_name
  FROM Services
  JOIN Users ON Services.user_id = Users.id
  ORDER BY Services.created_at DESC
');
$stmt->execute();
$services = $stmt->fetchAll();

draw_header('HomePage');
draw_home($services); 
draw_footer();
?>
