<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/transactions.helper.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['transaction_id'])) {
    header('Location: login.php');
    exit();
}

$db = getDatabaseConnection();
$transactionId = (int)$_GET['transaction_id'];
$userId = (int)$_SESSION['user_id'];

// Verify transaction is complete and belongs to user
$transaction = getTransactionDetails($db, $transactionId, $userId);
if (empty($transaction) || $transaction['status'] !== 'completed') {
    header('Location: transactions.php?error=invalid_review_request');
    exit();
}

// Check if already reviewed
if (hasReviewed($db, $transactionId, $userId)) {
    header('Location: transaction.php?id=' . $transactionId);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = 'Please select a valid rating (1-5 stars)';
    } else {
        $stmt = $db->prepare('
            INSERT INTO Reviews (transaction_id, rating, comment)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$transactionId, $rating, $comment]);
        header('Location: /pages/transaction.php?id=' . $transactionId . '&success=Review+submitted');
        exit();
    }
}

draw_header('Leave Review');
?>
<div class="review-container">
    <h2>Review for <?= htmlspecialchars($transaction['service_title']) ?></h2>
    <p>Freelancer: <?= htmlspecialchars($transaction['freelancer_name']) ?></p>
    
    <?php if (isset($error)): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Rating (1-5 stars):</label>
            <select name="rating" required>
                <option value="">Select rating</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> star<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Comments:</label>
            <textarea name="comment" rows="4"></textarea>
        </div>
        
        <button type="submit" class="submit-button">Submit Review</button>
    </form>
</div>
<?php draw_footer(); ?>