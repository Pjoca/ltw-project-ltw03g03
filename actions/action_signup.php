<?php
require_once(__DIR__ . '/../db/connection.db.php');

$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO Users (name, username, password, email) VALUES (?, ?, ?, ?)");
try {
    $stmt->execute([$name, $username, $password, $email]);
    header('Location: login.php');
} catch (PDOException $e) {
    header('Location: signup.php?error=1');
}
