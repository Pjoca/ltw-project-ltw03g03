<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();
$serviceId = (int)($_GET['service_id'] ?? 0);

// Get service details
$stmt = $db->prepare('
  SELECT s.*, u.name as freelancer_name 
  FROM Services s
  JOIN Users u ON s.user_id = u.id
  WHERE s.id = ?
');
$stmt->execute([$serviceId]);
$service = $stmt->fetch();

if (!$service) {
  header('Location: /pages/home.php?error=Service+not+found');
  exit();
}

// Get reviews
$stmt = $db->prepare('
  SELECT r.*, u.name as client_name
  FROM Reviews r
  JOIN Transactions t ON r.transaction_id = t.id
  JOIN Users u ON t.client_id = u.id
  WHERE t.service_id = ?
  ORDER BY r.created_at DESC
');
$stmt->execute([$serviceId]);
$reviews = $stmt->fetchAll();

draw_header('Reviews for ' . htmlspecialchars($service['title']));
?>

<div class="back-nav">
  <a href="/pages/profile.php">Profile</a>
  <a href="/pages/home.php">Home</a>
</div>

<div class="reviews-container">
  <h1>Reviews for <?= htmlspecialchars($service['title']) ?></h1>
  <p class="freelancer">By <?= htmlspecialchars($service['freelancer_name']) ?></p>
  
  <?php if (empty($reviews)): ?>
    <p class="no-reviews">No reviews yet</p>
  <?php else: ?>
    <div class="reviews-list">
      <?php foreach ($reviews as $review): ?>
        <div class="review-card">
          <div class="review-header">
            <span class="reviewer"><?= htmlspecialchars($review['client_name']) ?></span>
            <span class="rating"><?= str_repeat('★', $review['rating']) ?></span>
            <span class="date"><?= date('M j, Y', strtotime($review['created_at'])) ?></span>
          </div>
          <?php if (!empty($review['comment'])): ?>
            <div class="comment"><?= htmlspecialchars($review['comment']) ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php draw_footer(); ?>