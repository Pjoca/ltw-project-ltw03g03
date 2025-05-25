<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = getDatabaseConnection();

$stmt = $db->prepare('SELECT role FROM Users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    header('Location: index.php'); 
    exit();
}

draw_header('Admin Panel');
echo '<h2>Administração</h2>';
echo '<ul>';
echo '<li><a href="manage_users.php">Gerir Utilizadores</a></li>';
echo '<li><a href="manage_categories.php">Gerir Categorias</a></li>';
echo '</ul>';
draw_footer();
?>
