<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');

$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$db = getDatabaseConnection();

// Check if username or email already exists
$stmt = $db->prepare('SELECT id FROM Users WHERE username = :username OR email = :email');
$stmt->execute([
  ':username' => $username,
  ':email' => $email
]);

if ($stmt->fetch()) {
    // User exists redirect back with error
    header('Location: ../pages/signup.php?error=exists');
    exit();
}

// Hash the password 
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user
$stmt = $db->prepare('INSERT INTO Users (name, username, password, email, role) 
                      VALUES (:name, :username, :password, :email, :role)');
$stmt->execute([
    ':name' => $name,
    ':username' => $username,
    ':password' => $hashedPassword,
    ':email' => $email,
    ':role' => $role
]);

// Get the new user's ID
$userId = (int)$db->lastInsertId();

// Log the user in
$_SESSION['user_id'] = $userId;
$_SESSION['role'] = $role;

header('Location: ../pages/profile.php');
exit();
?>