<?php
declare(strict_types=1);

function getTransactionDetails(PDO $db, int $transactionId, int $userId): array {
    $stmt = $db->prepare('
        SELECT t.*, s.title as service_title, s.price, u.name as freelancer_name
        FROM Transactions t
        JOIN Services s ON t.service_id = s.id
        JOIN Users u ON t.freelancer_id = u.id
        WHERE t.id = ? AND t.client_id = ?
        LIMIT 1
    ');
    $stmt->execute([$transactionId, $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

function hasReviewed(PDO $db, int $transactionId, int $userId): bool {
    $stmt = $db->prepare('
        SELECT 1 FROM Reviews 
        WHERE transaction_id = ? 
        AND transaction_id IN (SELECT id FROM Transactions WHERE client_id = ?)
        LIMIT 1
    ');
    $stmt->execute([$transactionId, $userId]);
    return (bool)$stmt->fetch();
}
?>
