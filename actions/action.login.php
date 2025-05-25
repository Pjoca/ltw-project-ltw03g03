<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.db.php'); 
require_once(__DIR__ . '/../utils/session.php');

$username = $_POST['username'];
$password = $_POST['password'];


$db = getDatabaseConnection(); 
$stmt = $db->prepare("SELECT * FROM Users WHERE username = :username OR email = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch();


if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    header('Location: ../pages/profile.php');
}

else {
    header('Location: ../pages/login.php?error=1');
}
?>
