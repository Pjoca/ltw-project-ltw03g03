<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

$db = getDatabaseConnection();

$stmt = $db->prepare('SELECT name, username, email FROM Users WHERE id = :id');
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    // Handle case where user data couldn't be retrieved
    die("Error fetching user data.");
}

draw_header('Edit Profile');

// Display error messages if any
if (isset($_SESSION['error_messages'])) {
    echo '<ul class="error-messages">';
    foreach ($_SESSION['error_messages'] as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
    unset($_SESSION['error_messages']);
}

draw_edit_profile($user);

draw_footer();
?>