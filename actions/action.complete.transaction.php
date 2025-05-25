<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/home.php');
    exit();
}

if (!isset($_SESSION['user_id']) || !isset($_POST['transaction_id'])) {
    header('Location: /pages/login.php');
    exit();
}

$db = getDatabaseConnection();
$transactionId = (int)$_POST['transaction_id'];
$userId = (int)$_SESSION['user_id'];

try {
    // Verify transaction belongs to user and is pending
    $stmt = $db->prepare('
        UPDATE Transactions 
        SET status = "completed" 
        WHERE id = ? AND client_id = ? AND status = "pending"
    ');
    $stmt->execute([$transactionId, $userId]);

    if ($stmt->rowCount() === 0) {
        throw new Exception('Transaction not found or cannot be confirmed');
    }

    header('Location: /pages/transaction.php?id=' . $transactionId . '&success=Transaction+confirmed');
    exit();
} catch (Exception $e) {
    error_log('Confirm error: ' . $e->getMessage());
    header('Location: /pages/transaction.php?id=' . $transactionId . '&error=' . urlencode($e->getMessage()));
    exit();
}
?>
