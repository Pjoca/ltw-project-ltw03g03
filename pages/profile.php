<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();

$stmt = $db->prepare('SELECT * FROM Users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch();

draw_header('Your Profile');
draw_profile($user);
draw_footer();
?>