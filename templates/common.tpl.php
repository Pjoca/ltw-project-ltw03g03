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

<?php function draw_home() { ?>
  <div class="homepage-container">
    <div class="back-nav">
      <a href="/../pages/profile.php" class="back-button">Back to Profile</a>
    </div>

    <section class="homepage-banner">
      <h2>Available Services</h2>
      <p>Share your skills with the world — post a service and start earning today!</p>
      <a href="create_service.php" class="create-button">+ Offer a Service</a>
    </section>

    <section id="service-list" class="service-list">
      <!-- Services will be dynamically loaded here -->
    </section>

    <div id="loader" style="display: none; text-align: center; padding: 1rem;">
      <div class="spinner"></div>
    </div>
  </div>

  <script src="/../js/load_services.js" defer></script>
<?php } ?>




<?php function draw_create_service() { ?>

<form action="../actions/action_submit_service.php" method="POST" style="max-width:600px; margin:auto; font-family: Arial, sans-serif;">
  <div style="margin-bottom: 1em;">
    <label for="title" style="display:block; font-weight: bold; margin-bottom: 0.3em;">Title</label>
    <input type="text" name="title" id="title" required style="width:100%; padding: 0.5em;">
  </div>

  <div style="margin-bottom: 1em;">
    <label for="description" style="display:block; font-weight: bold; margin-bottom: 0.3em;">Description</label>
    <textarea name="description" id="description" required rows="6" style="width:100%; padding: 0.5em; resize: vertical;"></textarea>
  </div>

  <div style="display: flex; gap: 1em; flex-wrap: wrap; margin-bottom: 1em;">
    <div style="flex: 1 1 120px; min-width:120px;">
      <label for="price" style="display:block; font-weight: bold; margin-bottom: 0.3em;">Price</label>
      <input type="number" name="price" id="price" min="0.01" step="0.01" required placeholder="-- $" style="width:100%; padding: 0.5em;">
    </div>

    <div style="flex: 1 1 120px; min-width:120px;">
      <label for="delivery_time" style="display:block; font-weight: bold; margin-bottom: 0.3em;">Delivery Time (days)</label>
      <input type="number" name="delivery_time" id="delivery_time" min="1" required style="width:100%; padding: 0.5em;">
    </div>

    <div style="flex: 1 1 150px; min-width:150px;">
      <label for="media" style="display:block; font-weight: bold; margin-bottom: 0.3em;">Media (optional)</label>
      <input type="text" name="media" id="media" placeholder="Image URL or path" style="width:100%; padding: 0.5em;">
    </div>

    <div style="flex: 1 1 150px; min-width:150px;">
      <label for="category_id" style="display:block; font-weight: bold; margin-bottom: 0.3em;">Category</label>
      <select name="category_id" id="category_id" required style="width:100%; padding: 0.5em;">
        <option value="" disabled selected>Select a category</option>
        <option value="1">Web Development</option>
        <option value="2">Design</option>
      </select>
    </div>
  </div>

  <button type="submit" class="submit-button" style="padding: 0.75em 1.5em; font-size: 1em; cursor: pointer;">Submit Service</button>
</form>


<?php } ?>
