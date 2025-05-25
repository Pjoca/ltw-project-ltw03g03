<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/transactions.helper.php');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $db = getDatabaseConnection();
    
    // Validate transaction ID
    if (!isset($_GET['id']) || $_GET['id'] === '') {
        throw new InvalidArgumentException('Transaction ID is required');
    }
    
    $transactionId = (int)$_GET['id'];
    if ($transactionId <= 0) {
        throw new InvalidArgumentException('Invalid transaction ID');
    }

    $userId = (int)$_SESSION['user_id'];
    $transaction = getTransactionDetails($db, $transactionId, $userId);
    
    if (empty($transaction)) {
        throw new RuntimeException('Transaction not found');
    }

    // Display the transaction page
    draw_header('Transaction #' . $transactionId);
    displayTransaction($transaction);
    draw_footer();

} catch (InvalidArgumentException $e) {
    handleError($e->getMessage(), true);
} catch (RuntimeException $e) {
    handleError($e->getMessage(), true);
} catch (Exception $e) {
    error_log('Transaction error: ' . $e->getMessage());
    handleError('An error occurred while processing your request');
}

function displayTransaction(array $transaction): void {
    // Convert all values to strings for htmlspecialchars
    $serviceTitle = (string)($transaction['service_title'] ?? '[No Title]');
    $freelancerName = (string)($transaction['freelancer_name'] ?? '[Unknown]');
    $price = isset($transaction['price']) ? number_format((float)$transaction['price'], 2) : '0.00';
    $status = (string)($transaction['status'] ?? 'unknown');
    $transactionId = (string)($transaction['id'] ?? '0');
    ?>
    <div class="back-nav">
        <a href="/../pages/profile.php" class="nav-button">Profile</a>
        <a href="/../pages/home.php" class="nav-button">Home</a>
        <a href="/../pages/my.services.php" class="nav-button">My Services</a>
        <a href="/../pages/search.php" class="nav-button">Search</a>
        <a href="logout.php" class="nav-button">Logout</a>
    </div>
    
    <div class="transaction-container">
        <h2>Transaction #<?= htmlspecialchars($transactionId) ?></h2>
        <div class="transaction-details">
            <p><strong>Service:</strong> <?= htmlspecialchars($serviceTitle) ?></p>
            <p><strong>Freelancer:</strong> <?= htmlspecialchars($freelancerName) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($price) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($status)) ?></p>
        </div>
        
    <div class="transaction-actions">
        <?php if ($transaction['status'] === 'pending'): ?>
            <form method="POST" action="/actions/action.complete.transaction.php">
                <input type="hidden" name="transaction_id" value="<?= $transactionId ?>">
                <button type="submit" class="confirm-button">Confirm Payment</button>
            </form>
            <form method="POST" action="/actions/action.cancel.transaction.php">
                <input type="hidden" name="transaction_id" value="<?= $transactionId ?>">
                <button type="submit" class="cancel-button">Cancel Transaction</button>
            </form>
        <?php elseif ($transaction['status'] === 'completed'): ?>
            <?php 
            $db = getDatabaseConnection();
            if (!hasReviewed($db, (int)$transaction['id'], (int)$_SESSION['user_id'])): 
            ?>
                <a href="/pages/review.php?transaction_id=<?= $transactionId ?>" class="review-button">
                    Leave a Review
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}

function handleError(string $message, bool $redirect = false): void {
    if ($redirect && !isset($_GET['error'])) {
        header('Location: transactions.php?error=' . urlencode($message));
        exit();
    }
    
    draw_header('Transaction Error');
    ?>
    <div class="error-container">
        <h2>Error</h2>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="transactions.php" class="back-link">Back to Transactions</a>
    </div>
    <?php
    draw_footer();
}