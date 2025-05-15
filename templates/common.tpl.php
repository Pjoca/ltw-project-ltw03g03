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
  <form action="/../actions/action_login.php" method="post">
  <label>Username or Email: <input type="text" name="username" required></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <button type="submit">Login</button>
</form>
<p> Don't have an account ? <a href="/../pages/signup.php">Sign up</a>
</p>
<?php } ?>

<?php function draw_signup() { ?>
  <form action="../actions/action_signup.php" method="post">
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
<?php } ?>

<?php function draw_home(array $services) { ?>
  <section class="homepage-banner">
    <h2>Available Services</h2>
    <p>Share your skills with the world — post a service and start earning today!</p>
    <a href="create_service.php" class="create-button">+ Offer a Service</a>
  </section>

  <section class="service-list">
    <?php foreach ($services as $service) { ?>
      <article class="service-card">
        <div class="service-header">
          <div>
            <h3><?= htmlspecialchars($service['poster_name']) ?></h3>
            <span class="date"><?= date('M d, Y', strtotime($service['created_at'])) ?></span>
          </div>
        </div>
        
        <h4><?= htmlspecialchars($service['title']) ?></h4>
        <p><strong>Category:</strong> <?= htmlspecialchars($service['category']) ?></p>
        <p><?= htmlspecialchars($service['description']) ?></p>
        <p><strong>Price:</strong> $<?= htmlspecialchars(number_format($service['price'], 2)) ?></p>
        <p><strong>Delivery time:</strong> <?= htmlspecialchars($service['delivery_time']) ?> days</p>

        <?php if (!empty($service['media'])): ?>
          <div class="service-media">
            <img src="/media/<?= htmlspecialchars($service['media']) ?>" alt="Service media" style="max-width: 300px;">
            <!-- Or use <video> if it's a video -->
          </div>
        <?php endif; ?>

        <div class="service-actions">
          <!-- Optional buttons or contact links -->
        </div>
      </article>
    <?php } ?>
  </section>

  <div class="profile-actions">
    <a href="/../pages/profile.php">Back to Profile</a>
  </div>
<?php } ?>


