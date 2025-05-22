<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/contact.tpl.php');

$serviceId = $_GET['service_id'];
$db = getDatabaseConnection();

$stmt = $db->prepare("SELECT * FROM Services WHERE id = ?");
$stmt->execute([$serviceId]);
$service = $stmt->fetch();

if (!$service) die("Service not found.");

draw_header("Contact Freelancer");
draw_contact_freelancer($service);
draw_footer();
