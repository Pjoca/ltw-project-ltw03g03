<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

$stmt = $db->prepare('SELECT role FROM Users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['category_name'])) {
    $stmt = $db->prepare('INSERT INTO Categories (name) VALUES (?)');
    $stmt->execute([trim($_POST['category_name'])]);
}

$stmt = $db->query('SELECT * FROM Categories ORDER BY name');
$categories = $stmt->fetchAll();

draw_header('Gerir Categorias');
echo '<h2>Adicionar Categoria</h2>';
echo '<form method="POST">';
echo '<input type="text" name="category_name" required>';
echo '<button type="submit">Adicionar</button>';
echo '</form>';

echo '<h2>Categorias Existentes</h2>';
foreach ($categories as $cat) {
    echo '<p>' . htmlspecialchars($cat['name']) . '</p>';
}

draw_footer();
?>
