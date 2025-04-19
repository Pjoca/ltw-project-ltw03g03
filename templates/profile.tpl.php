<?php function draw_profile(array $user) { ?>
  <div class="profile-card">
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
  </div>
  <div class="profile-actions">
      <a href="login.php">Back to Login Page</a>
      <a href="logout.php">Logout</a>
  </div>
<?php } ?>
