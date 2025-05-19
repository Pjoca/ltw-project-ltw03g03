<?php function draw_my_services_page() { ?>
    <div class="back-nav">
      <a href="/../pages/home.php" class="nav-button"> Home </a>
      <a href="/../pages/profile.php" class="nav-button"> Profile </a>
      <a href="logout.php"class="nav-button"> Logout</a>
    </div>

    <section class="homepage-banner">
      <h2>My Services</h2>
      <p>Here are the services you've listed.</p>
      <a href="create.service.php" class="create-button">+ Offer a Service</a>
    </section>

    <section id="my-service-list" class="service-list">
      <!-- User's services will load here -->
    </section>

    <div id="loader" style="display: none; text-align: center; padding: 1rem;">
      <div class="spinner"></div>
    </div>
  </div>

  <script>
    const userId = <?= json_encode($_SESSION['user_id']) ?>;
  </script>
  <script src="/../js/load.services.js" defer></script>
<?php } ?>


<?php function draw_create_service() { ?>

<form action="../actions/action.submit.service.php" method="POST" style="max-width:600px; margin:auto; font-family: Arial, sans-serif;">
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
        <option value="2">Graphic Design</option>
        <option value="3">Writing</option>
        <option value="4">Marketing</option>
        <option value="5">Photography</option>
        <option value="6">Other</option>
      </select>
    </div>
  </div>

  <button type="submit" class="submit-button" style="padding: 0.75em 1.5em; font-size: 1em; cursor: pointer;">Submit Service</button>
</form>

  <div class="back-nav">
      <a href="/../pages/home.php" class="nav-button"> Home </a>
  </div>


<?php } ?>

<?php function draw_edit_service(array $service) { ?>
  <form action="/../actions/action.edit.service.php" method="POST">
    <input type="hidden" name="original_title" value="<?= htmlspecialchars($service['title']) ?>">

    <div>
      <label for="title">Title:</label>
      <input type="text" id="title" name="title" value="<?= htmlspecialchars($service['title']) ?>" required>
    </div>

    <div>
      <label for="description">Description:</label>
      <textarea id="description" name="description" required><?= htmlspecialchars($service['description']) ?></textarea>
    </div>

    <div>
      <label for="price">Price:</label>
      <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($service['price']) ?>" required>
    </div>

    <div>
      <label for="delivery_time">Delivery Time (days):</label>
      <input type="number" id="delivery_time" name="delivery_time" value="<?= htmlspecialchars($service['delivery_time']) ?>" required>
    </div>

    <div>
      <input type="submit" value="Update Service">
    </div>
  </form>
<?php } ?>
