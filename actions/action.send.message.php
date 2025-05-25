<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php'); // Redirect to login page if not logged in
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/messages.php'); // Redirect if not a POST request
    exit();
}

// Get the sender and receiver IDs, and the message content
$senderId = (int)$_SESSION['user_id'];
$receiverId = filter_input(INPUT_POST, 'receiver_id', FILTER_VALIDATE_INT);
$messageContent = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Validate inputs
if (!$receiverId || !$messageContent) {
    // Handle error: Invalid receiver ID or empty message
    // You might want to set a session message here to display to the user
    $_SESSION['error_message'] = 'Invalid message data.';
    header('Location: ../pages/messages.php?user_id=' . $receiverId); // Redirect back to messages page
    exit();
}

// Ensure the sender is not trying to message themselves (optional, but good practice)
if ($senderId === $receiverId) {
    $_SESSION['error_message'] = 'Cannot send message to yourself.';
    header('Location: ../pages/messages.php');
    exit();
}

try {
    $db = getDatabaseConnection();

    $stmt = $db->prepare('
        INSERT INTO Messages (sender_id, receiver_id, message)
        VALUES (?, ?, ?)
    ');
    $stmt->execute([$senderId, $receiverId, $messageContent]);

    // Redirect back to the messages page for the specific conversation
    header('Location: ../pages/messages.php?user_id=' . $receiverId);
    exit();

} catch (PDOException $e) {
    // Handle database error
    error_log($e->getMessage()); // Log the error for debugging
    $_SESSION['error_message'] = 'Failed to send message: ' . $e->getMessage();
    header('Location: ../pages/messages.php?user_id=' . $receiverId); // Redirect with error
    exit();
}
