<?php function draw_header($title) { ?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/pages.css">

  </head>
  <body>
    <h1><?=$title?></h1>
    <main>
<?php } ?>

<?php function draw_footer() { ?>
    </main>
    <footer>
      Ultimate Freelancer Site &copy; 2025
    </footer>
  </body>
</html>
<?php } ?>

<?php function draw_login() { ?>
  <body class="auth-page">
  <form action="/../actions/action.login.php" method="post">
  <label>Username or Email: <input type="text" name="username" required></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <button type="submit">Login</button>
</form>
<p> Don't have an account ? <a href="/../pages/signup.php">Sign up</a>
</p>
</body>
<?php } ?>

<?php function draw_signup() { ?>
  <body class="auth-page">
  <form action="../actions/action.signup.php" method="post">
    <label>Name: <input type="text" name="name" required></label><br>
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Role: 
      <select name="role" required>
        <option value="client">Client</option>
        <option value="freelancer">Freelancer</option>
      </select></label><br>
  <button type="submit">Sign Up</button>
</form>
<p> Have an account ? <a href="/../pages/login.php">Log in</a>
</p>
</body>
<?php } ?>

<?php function draw_home() { ?>
  <div class="homepage-container">
    <div class="back-nav">
      <a href="/../pages/profile.php" class="nav-button">Profile</a>
      <a href="/../pages/my.services.php" class="nav-button">My Services</a>
      <a href="/../pages/search.php" class="nav-button">Search</a>
      <a href="/../pages/messages.php" class="nav-button">Messages</a>
      <a href="/../pages/logout.php" class="nav-button">Logout</a>

      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
        <a href="/../pages/manage_users.php" class="nav-button admin-button">Manage Roles</a>
      <?php endif; ?>
    </div>

    <section class="homepage-banner">
      <h2>Available Services</h2>
      <p>Share your skills with the world — post a service and start earning today!</p>
      <a href="create.service.php" class="create-button">+ Offer a Service</a>
    </section>

    <section id="service-list" class="service-list">
      <!-- Services will be dynamically loaded here -->
    </section>

    <div id="loader">
      <div class="spinner"></div>
    </div>
  </div>

  <script src="/../js/load.services.js" defer></script>
<?php } ?>

<?php function draw_messages(array $conversations, array $messages = [], ?int $activeUserId = null) { ?>
  <div class="back-nav">
    <a href="/../pages/profile.php" class="nav-button"> Profile </a>
    <a href="/../pages/home.php" class="nav-button"> Home </a>
    <a href="/../pages/my.services.php" class="nav-button"> My Services </a>
    <a href="/../pages/search.php" class="nav-button"> Search </a>
    <a href="logout.php" class="nav-button"> Logout</a>
  </div>

  <div class="messages-container">
    <div class="conversation-list">
      <h2>Conversations</h2>
      <?php if (empty($conversations)): ?>
        <p>No conversations yet.</p>
      <?php else: ?>
        <ul>
          <?php foreach ($conversations as $conv): ?>
            <li class="<?= $conv['user_id'] == $activeUserId ? 'active' : '' ?>">
              <a href="messages.php?user_id=<?= $conv['user_id'] ?>">
                <strong><?= htmlspecialchars($conv['name']) ?></strong>
                <p><?= htmlspecialchars($conv['latest_message']) ?></p>
                <small><?= date('M j, Y', strtotime($conv['sent_at'])) ?></small>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <?php if ($activeUserId): ?>
    <div class="message-view">
      <div class="message-thread">
        <?php if (empty($messages)): ?>
          <p>No messages in this conversation.</p>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
            <div class="message <?= $msg['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
              <div class="message-header">
                <strong><?= htmlspecialchars($msg['sender_name']) ?></strong>
                <span><?= date('g:i a', strtotime($msg['sent_at'])) ?></span>
              </div>
              <p><?= htmlspecialchars($msg['message']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <script>
        // JavaScript to scroll to the bottom of the message thread
        document.addEventListener('DOMContentLoaded', function() {
          const messageThread = document.querySelector('.message-thread');
          if (messageThread) {
            messageThread.scrollTop = messageThread.scrollHeight;
          }
        });
      </script>

      <form class="message-form" method="POST" action="/actions/action.send.message.php">
        <input type="hidden" name="receiver_id" value="<?= $activeUserId ?>">
        <textarea name="message" required placeholder="Type your message..."></textarea>
        <button type="submit">Send</button>
      </form>
    </div>
    <?php endif; ?>
  </div>
<?php } ?>