<?php
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

// Verify transaction is completed and belongs to user
$stmt = $db->prepare('
  INSERT INTO Reviews (transaction_id, rating, comment)
  VALUES (?, ?, ?)
');
$stmt->execute([
  $_POST['transaction_id'],
  $_POST['rating'],
  $_POST['comment']
]);

header('Location: /pages/transaction.php?id=' . $_POST['transaction_id']);
?>
