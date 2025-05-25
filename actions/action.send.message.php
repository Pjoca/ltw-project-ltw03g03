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

// --- Rate Limiting Configuration ---
const RATE_LIMIT_WINDOW = 10; // seconds (e.g., 60 seconds)
const MAX_MESSAGES_PER_WINDOW = 5; // maximum messages allowed in that window

$currentTime = time();
$senderId = (int)$_SESSION['user_id'];

// Initialize session rate limit data if it doesn't exist for this user
if (!isset($_SESSION['rate_limit'][$senderId])) {
    $_SESSION['rate_limit'][$senderId] = [
        'last_reset_time' => $currentTime,
        'message_count' => 0
    ];
}

// Get user's rate limit data
$rateLimitData = &$_SESSION['rate_limit'][$senderId]; // Use reference to modify directly

// Check if the window has passed, if so, reset the counter
if (($currentTime - $rateLimitData['last_reset_time']) > RATE_LIMIT_WINDOW) {
    $rateLimitData['last_reset_time'] = $currentTime;
    $rateLimitData['message_count'] = 0;
}

// Check if message limit is exceeded within the current window
if ($rateLimitData['message_count'] >= MAX_MESSAGES_PER_WINDOW) {
    $_SESSION['error_message'] = 'You are sending messages too quickly. Please wait a moment.';
    header('Location: ../pages/messages.php?user_id=' . (int)$_POST['receiver_id']); // Redirect back
    exit();
}

// Get the sender and receiver IDs, and the message content
$receiverId = filter_input(INPUT_POST, 'receiver_id', FILTER_VALIDATE_INT);
$messageContent = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Validate inputs
if (!$receiverId || !$messageContent) {
    $_SESSION['error_message'] = 'Invalid message data.';
    header('Location: ../pages/messages.php?user_id=' . $receiverId);
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

    // --- Increment message count after successful send ---
    $rateLimitData['message_count']++;
    // --- End Increment ---

    // Redirect back to the messages page for the specific conversation
    header('Location: ../pages/messages.php?user_id=' . $receiverId);
    exit();

} catch (PDOException $e) {
    // Handle database error
    error_log($e->getMessage()); // Log the error for debugging
    $_SESSION['error_message'] = 'Failed to send message: ' . $e->getMessage();
    header('Location: ../pages/messages.php?user_id=' . $receiverId);
    exit();
}