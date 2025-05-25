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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $stmt = $db->prepare('UPDATE Users SET role = "admin" WHERE id = ?');
    $stmt->execute([$_POST['user_id']]);
}

$stmt = $db->query('SELECT id, username, role FROM Users ORDER BY username');
$users = $stmt->fetchAll();

draw_header('Gerir Utilizadores');
echo '<a href="index.php" class="back-button">&larr; Back to Home</a>';
echo '<h2>Utilizadores</h2>';

foreach ($users as $user) {
    echo '<form method="POST" style="margin-bottom: 1rem;">';
    echo '<input type="hidden" name="user_id" value="' . $user['id'] . '">';
    echo htmlspecialchars($user['username']) . ' - ' . $user['role'];

    if ($user['id'] == $_SESSION['user_id']) {
    echo ' <button class="admin-button" disabled>You</button>';
    } else if ($user['role'] === 'admin') {
        echo ' <button type="submit" name="action" value="demote">Remover de admin</button>';
    } else {
        echo ' <button type="submit" name="action" value="promote">Promover a admin</button>';
    }

    echo '</form>';
}


draw_footer();
?>
