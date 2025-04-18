<?php function draw_profile(array $user) { ?>
  <div class="profile-card" style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px;">
    <h2><?= htmlspecialchars($user['name']) ?>'s Profile</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
    <p>
      <a href="dashboard.php">← Back to Dashboard</a> | 
      <a href="logout.php">Logout</a>
    </p>
  </div>
<?php } ?>
