<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view messages.");
}

$db = getDatabaseConnection();
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("
    SELECT m.*, u1.username AS sender_name, u2.username AS receiver_name, s.title AS service_title
    FROM Messages m
    JOIN users u1 ON m.sender_id = u1.id
    JOIN users u2 ON m.receiver_id = u2.id
    LEFT JOIN Services s ON m.service_id = s.id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    ORDER BY m.sent_at DESC
");

$stmt->execute([$user_id, $user_id]);
$messages = $stmt->fetchAll();

draw_header("My Messages");
?>
<div class="message-actions">
  <a href="home.php">Home Page</a>
  <a href="profile.php">Profile</a>
</div>
<section class="messages">
  <h2>Inbox</h2>

  <?php if (empty($messages)) : ?>
    <p>You have no messages yet.</p>
  <?php else : ?>
    <ul class="message-list">
      <?php foreach ($messages as $msg) : ?>
        <li class="message-item" style="border:1px solid #ccc; padding:1rem; margin-bottom:1rem;">
          <strong><?= htmlspecialchars($msg['sender_id'] == $user_id ? "To: {$msg['receiver_name']}" : "From: {$msg['sender_name']}") ?></strong><br>

          <?php if ($msg['service_title']) : ?>
            <em>Regarding: <?= htmlspecialchars($msg['service_title']) ?></em><br>
          <?php endif; ?>

          <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>

          <?php if (!empty($msg['proposed_price']) || !empty($msg['delivery_days'])) : ?>
            <small>
              <?php if ($msg['proposed_price']) : ?>💰 Price: $<?= $msg['proposed_price'] ?><br><?php endif; ?>
              <?php if ($msg['delivery_days']) : ?>⏱ Delivery: <?= $msg['delivery_days'] ?> day(s)<br><?php endif; ?>
            </small>
          <?php endif; ?>

          <small>📅 Sent at: <?= $msg['sent_at'] ?></small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>



<?php draw_footer(); ?>
