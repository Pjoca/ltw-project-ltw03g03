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
  <section class="homepage-banner">
    <h2>Available Services</h2>
    <p>Share your skills with the world — post a service and start earning today!</p>
    <a href="create_service.php" class="create-button">+ Offer a Service</a>
  </section>

  <section class="service-list">
    <article class="service-card">
      <div class="service-header">
        <div>
          <h3>John Developer</h3>
          <span class="date">Apr 21, 2025</span>
        </div>
      </div>
      <p>I will build a responsive website for your business or project using modern tools.</p>
      <div class="service-actions">
      </div>
    </article>

    <article class="service-card">
      <div class="service-header">
        
        <div>
          <h3>Jane Designer</h3>
          <span class="date">Apr 20, 2025</span>
        </div>
      </div>
      <p>Offering clean, modern UI/UX designs for web and mobile applications.</p>
      <div class="service-actions">
      </div>
    </article>
  </section>
  <div class="profile-actions">
    <a href="/../pages/profile.php">Back to Profile</a>
</div>
<?php } ?>
