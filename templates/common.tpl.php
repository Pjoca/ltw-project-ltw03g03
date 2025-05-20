<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');

function draw_header($title) { ?>
  <!DOCTYPE html>
  <html>
    <head>
      <title><?= $title ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta charset="utf-8">
      <link rel="stylesheet" href="../css/style.css">
      <link rel="stylesheet" href="../css/pages.css">
    </head>
    <body>
      <h1><?= $title ?></h1>
      <main>
<?php }

function draw_footer() { ?>
      </main>
      <footer>
        Ultimate Freelancer Site &copy; 2025
      </footer>
    </body>
  </html>
<?php }

function draw_login() { ?>
  <form action="/../actions/action.login.php" method="post">
    <label>Username or Email: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
  </form>
  <p>Don't have an account? <a href="/../pages/signup.php">Sign up</a></p>
<?php }

function draw_signup() { ?>
  <form action="../actions/action.signup.php" method="post">
    <label>Name: <input type="text" name="name" required></label><br>
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Role:
      <select name="role" required>
        <option value="client">Client</option>
        <option value="freelancer">Freelancer</option>
      </select>
    </label><br>
    <button type="submit">Sign Up</button>
  </form>
  <p>Have an account? <a href="/../pages/login.php">Log in</a></p>
<?php }

function draw_home() {
  $db = getDatabaseConnection();
  $cats = $db->query('SELECT name FROM Categories ORDER BY name')->fetchAll(PDO::FETCH_COLUMN);
  ?>
  <div class="homepage-container">
    <div class="back-nav">
      <a href="/../pages/profile.php" class="nav-button">Profile</a>
      <a href="/../pages/my.services.php" class="nav-button">My Services</a>
      <a href="logout.php" class="nav-button">Logout</a>
    </div>

    <section class="homepage-banner">
      <h2>Available Services</h2>
      <p>Share your skills with the world — post a service and start earning today!</p>
      <a href="create.service.php" class="create-button">+ Offer a Service</a>
    </section>

    <section class="filters" style="margin: 0 auto 30px; max-width: 600px;">
      <form id="filter-form" style="display: flex; flex-wrap: wrap; gap: 6px;">
        <label style="flex: 1 1 50px;">
          Category:
          <select name="category" id="category">
            <option value="">All</option>
            <?php foreach ($cats as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
            <?php endforeach; ?>
          </select>
        </label>

        <label style="flex: 1 1 50px;">
          Max&nbsp;Price:
          <input type="number" name="price" id="price" min="0" placeholder="e.g. 100">
        </label>

        <label style="flex: 1 1 70px;">
          Max&nbsp;Delivery&nbsp;(days):
          <input type="number" name="delivery" id="delivery" min="1" placeholder="e.g. 7">
        </label>

        <button type="submit" style="flex: 1 1 100%; text-align: center; margin-top: 6px;">Apply</button>

      </form>
    </section>

    <section id="service-list" class="service-list">
      <!-- Services will be dynamically loaded here -->
    </section>

    <div id="loader" style="display: none; text-align: center; padding: 1rem;">
      <div class="spinner"></div>
    </div>
  </div>

  <script src="/../js/load.services.js" defer></script>
<?php } ?>
