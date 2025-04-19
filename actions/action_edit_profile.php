<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$errors = [];

// Sanitize and validate inputs
$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($name) || empty($username) || empty($email)) {
    $errors[] = 'Name, username, and email are required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}

if (!empty($password) && $password !== $confirm) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    $_SESSION['error_messages'] = $errors;
    header('Location: ../edit/profile.edit.php');
    exit;
}

// Build update query dynamically
$query = "UPDATE Users SET name = :name, username = :username, email = :email";
$params = [
    ':name' => $name,
    ':username' => $username,
    ':email' => $email,
    ':id' => $_SESSION['user_id']
];

if (!empty($password)) {
    $query .= ", password = :password";
    $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
}

$query .= " WHERE id = :id";

$stmt = $db->prepare($query);
$stmt->execute($params);

// Redirect to profile
header('Location: ../pages/profile.php');
exit;
?>