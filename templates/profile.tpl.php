<?php function draw_profile(array $user) { ?>
  <div class="profile-card">
  <div class="profile-row"><span class="label">Name:</span> <span><?= htmlspecialchars($user['name']) ?></span></div>
  <div class="profile-row"><span class="label">Username:</span> <span><?= htmlspecialchars($user['username']) ?></span></div>
  <div class="profile-row"><span class="label">Email:</span> <span><?= htmlspecialchars($user['email']) ?></span></div>
  <div class="profile-row"><span class="label">Role:</span> <span><?= htmlspecialchars($user['role']) ?></span></div>
  <div class="profile-row"><span class="label">Joined:</span> <span><?= htmlspecialchars($user['created_at']) ?></span></div>
</div>

<div class="profile-actions">
  <a href="home.php">Home Page</a>
  <a href="/../edit/profile.edit.php">Edit Profile</a>
  <a href="logout.php">Logout</a>
</div>
<?php } ?>

<?php function draw_edit_profile(array $user) { ?>
    <form action="../actions/action.edit.profile.php" method="post">
      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
      </div>
      <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      <div>
        <label for="password">New Password (leave blank to keep current):</label>
        <input type="password" id="password" name="password">
      </div>
      <div>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">
      </div>
      <div>
        <input type="submit" value="Save Changes">
      </div>
    </form>
    <div class="profile-actions">
      <a href="/../pages/profile.php">Back to Profile</a>
  </div>
    
<?php } ?>