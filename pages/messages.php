<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = getDatabaseConnection();
$currentUserId = (int)$_SESSION['user_id'];

// Determine the other user ID
$otherUserId = null;
if (isset($_GET['user_id'])) { // For navigating to existing conversations or initiating new ones
    $otherUserId = (int)$_GET['user_id'];
}

// Check if a new conversation needs to be initiated based on the URL parameter
// However, we will NOT insert an initial message here.
// The presence of $otherUserId will still make the message view active.
if ($otherUserId && $otherUserId !== $currentUserId) {
    // This block is now only for ensuring the conversation view is active
    // when a new chat is started, without auto-inserting a message.
    // We still check for existing conversations, but won't insert by default.
    $stmt = $db->prepare('
        SELECT id FROM Messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?)
        LIMIT 1
    ');
    $stmt->execute([$currentUserId, $otherUserId, $otherUserId, $currentUserId]);
    $existing = $stmt->fetch();
}


// Get all conversations
// This query remains the same as it fetches existing conversations
$stmt = $db->prepare('
    SELECT 
        u.id as user_id,
        u.name,
        m.message as latest_message,
        m.sent_at
    FROM Messages m
    JOIN Users u ON u.id = CASE 
        WHEN m.sender_id = ? THEN m.receiver_id 
        ELSE m.sender_id 
    END
    WHERE m.id IN (
        SELECT MAX(id) FROM Messages 
        WHERE sender_id = ? OR receiver_id = ?
        GROUP BY 
          CASE 
            WHEN sender_id < receiver_id THEN CAST(sender_id AS TEXT) || \'-\' || CAST(receiver_id AS TEXT)
            ELSE CAST(receiver_id AS TEXT) || \'-\' || CAST(sender_id AS TEXT)
          END
    )
    ORDER BY m.sent_at DESC
');

$stmt->execute([$currentUserId, $currentUserId, $currentUserId]);
$conversations = $stmt->fetchAll();

// Get messages for active conversation
$messages = [];
$activeUserId = null;
if ($otherUserId) { // Use $otherUserId as the active user for displaying messages
    $activeUserId = $otherUserId;
    $stmt = $db->prepare('
        SELECT m.*, u.name as sender_name
        FROM Messages m
        JOIN Users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND m.receiver_id = ?)
           OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.sent_at ASC
    ');
    $stmt->execute([$currentUserId, $activeUserId, $activeUserId, $currentUserId]);
    $messages = $stmt->fetchAll();
}

draw_header('Messages');
if (isset($_SESSION['error_message'])) {
    echo '<div style="color: red; text-align: center; margin-top: 10px;">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']); // Clear the message after displaying it
}
draw_messages($conversations, $messages, $activeUserId);
draw_footer();
?>
